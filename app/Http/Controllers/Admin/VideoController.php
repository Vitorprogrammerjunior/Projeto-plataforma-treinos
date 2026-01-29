<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Tab;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller CRUD de Vídeos (Admin).
 */
class VideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = Video::query()
            ->with('tab')
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->when($request->tab, fn($q, $t) => $q->where('tab_id', $t))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $categories = Video::getCategories();
        $tabs = Tab::ordered()->get();

        return view('admin.videos.index', compact('videos', 'categories', 'tabs'));
    }

    public function create()
    {
        $categories = Video::getCategories();
        $tabs = Tab::active()->ordered()->get();
        return view('admin.videos.create', compact('categories', 'tabs'));
    }

    public function store(VideoRequest $request)
    {
        $data = $request->validated();

        // Upload de thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Upload de vídeo local
        if ($request->hasFile('video_file')) {
            $data['video_path'] = $request->file('video_file')
                ->store('videos', 'local'); // Storage privado
            $data['video_source'] = 'local';
        }

        // Se tem URL externa, marca como external
        if ($data['video_url'] ?? null) {
            $data['video_source'] = 'external';
        }

        Video::create($data);

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Vídeo criado com sucesso!');
    }

    public function edit(Video $video)
    {
        $categories = Video::getCategories();
        $tabs = Tab::active()->ordered()->get();
        return view('admin.videos.edit', compact('video', 'categories', 'tabs'));
    }

    public function update(VideoRequest $request, Video $video)
    {
        $data = $request->validated();

        // Upload de nova thumbnail
        if ($request->hasFile('thumbnail')) {
            // Remove thumbnail antiga
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Upload de novo vídeo
        if ($request->hasFile('video_file')) {
            // Remove vídeo antigo
            if ($video->video_path) {
                Storage::disk('local')->delete($video->video_path);
            }
            $data['video_path'] = $request->file('video_file')
                ->store('videos', 'local');
            $data['video_source'] = 'local';
            $data['video_url'] = null;
        }

        // Se atualizou URL externa
        if (($data['video_url'] ?? null) && $data['video_source'] === 'external') {
            // Remove vídeo local se existir
            if ($video->video_path) {
                Storage::disk('local')->delete($video->video_path);
            }
            $data['video_path'] = null;
        }

        $video->update($data);

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Vídeo atualizado com sucesso!');
    }

    public function destroy(Video $video)
    {
        // Remove arquivos
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        if ($video->video_path) {
            Storage::disk('local')->delete($video->video_path);
        }

        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Vídeo excluído com sucesso!');
    }

    /**
     * Reordena vídeos via AJAX.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'videos' => 'required|array',
            'videos.*.id' => 'required|exists:videos,id',
            'videos.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->videos as $item) {
            Video::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
