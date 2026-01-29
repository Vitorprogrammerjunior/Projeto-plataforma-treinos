<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientVideoController extends Controller
{
    /**
     * Enviar um novo vídeo para o cliente.
     */
    public function store(Request $request, User $client)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_type' => 'required|in:youtube,upload',
            'video_url' => 'required_if:video_type,youtube|nullable|url',
            'video_file' => 'required_if:video_type,upload|nullable|file|mimes:mp4,mov,avi,wmv|max:512000', // 500MB max
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
        ]);

        $video = new Video();
        $video->user_id = $client->id;
        $video->title = $validated['title'];
        $video->slug = Str::slug($validated['title']) . '-' . time();
        $video->description = $validated['description'] ?? null;
        $video->instructions = $validated['instructions'] ?? null;
        $video->video_type = $validated['video_type'];
        $video->category = $validated['category'] ?? null;
        $video->is_active = true;
        $video->order = $client->videos()->count();

        if ($validated['video_type'] === 'youtube') {
            $video->video_url = $validated['video_url'];
            $video->video_source = 'external';
        } else {
            // Upload do vídeo
            if ($request->hasFile('video_file')) {
                $video->file_path = $request->file('video_file')->store('client-videos/' . $client->id, 'public');
                $video->video_source = 'local';
            }
        }

        // Upload da thumbnail
        if ($request->hasFile('thumbnail')) {
            $video->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->save();

        return redirect()
            ->route('admin.clients.videos', $client)
            ->with('success', 'Vídeo enviado com sucesso!');
    }

    /**
     * Atualizar vídeo existente.
     */
    public function update(Request $request, User $client, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_type' => 'required|in:youtube,upload',
            'video_url' => 'required_if:video_type,youtube|nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $video->title = $validated['title'];
        $video->description = $validated['description'] ?? null;
        $video->instructions = $validated['instructions'] ?? null;
        $video->video_type = $validated['video_type'];
        $video->category = $validated['category'] ?? null;
        $video->is_active = $validated['is_active'] ?? true;

        if ($validated['video_type'] === 'youtube') {
            $video->video_url = $validated['video_url'];
            $video->video_source = 'external';
        } else if ($request->hasFile('video_file')) {
            $video->file_path = $request->file('video_file')->store('client-videos/' . $client->id, 'public');
            $video->video_source = 'local';
        }

        if ($request->hasFile('thumbnail')) {
            $video->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->save();

        return redirect()
            ->route('admin.clients.videos', $client)
            ->with('success', 'Vídeo atualizado com sucesso!');
    }

    /**
     * Excluir vídeo.
     */
    public function destroy(User $client, Video $video)
    {
        $video->delete();

        return redirect()
            ->route('admin.clients.videos', $client)
            ->with('success', 'Vídeo excluído com sucesso!');
    }
}
