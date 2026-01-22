<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller para área do usuário (dashboard e vídeos).
 */
class DashboardController extends Controller
{
    /**
     * Dashboard do usuário.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $subscription = $user->activeSubscription;

        // Vídeos disponíveis para o usuário
        $videos = $user->hasActiveAccess()
            ? Video::active()->ordered()->get()
            : Video::active()->free()->ordered()->get();

        // Agrupa por categoria
        $videosByCategory = $videos->groupBy('category');

        return view('dashboard', compact('user', 'subscription', 'videos', 'videosByCategory'));
    }

    /**
     * Exibe player de vídeo.
     */
    public function watch(Request $request, Video $video)
    {
        $user = $request->user();

        // Verifica acesso: precisa ser gratuito OU ter assinatura ativa
        if (!$video->is_free && !$user->hasActiveAccess()) {
            return redirect()
                ->route('plans.index')
                ->with('warning', 'Você precisa de uma assinatura para assistir este vídeo.');
        }

        if (!$video->is_active) {
            abort(404);
        }

        // Incrementa views
        $video->incrementViews();

        // Vídeos relacionados (mesma categoria)
        $relatedVideos = Video::active()
            ->where('id', '!=', $video->id)
            ->when($video->category, fn($q) => $q->where('category', $video->category))
            ->ordered()
            ->take(4)
            ->get();

        return view('videos.watch', compact('video', 'relatedVideos'));
    }

    /**
     * Stream de vídeo protegido (para vídeos locais).
     * Evita acesso direto via URL do storage.
     */
    public function stream(Request $request, Video $video): StreamedResponse
    {
        $user = $request->user();

        // Validação de acesso
        if (!$video->is_free && !$user->hasActiveAccess()) {
            abort(403, 'Acesso negado');
        }

        if ($video->video_source !== 'local' || !$video->video_path) {
            abort(404);
        }

        $path = Storage::disk('local')->path($video->video_path);

        if (!file_exists($path)) {
            abort(404);
        }

        $size = filesize($path);
        $mimeType = mime_content_type($path) ?: 'video/mp4';

        // Suporte a Range Requests (streaming progressivo)
        $start = 0;
        $end = $size - 1;
        $statusCode = 200;
        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => $size,
        ];

        if ($request->hasHeader('Range')) {
            $range = $request->header('Range');
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = $matches[2] !== '' ? intval($matches[2]) : $size - 1;

                if ($start > $end || $start >= $size) {
                    abort(416); // Range Not Satisfiable
                }

                $statusCode = 206;
                $headers['Content-Length'] = $end - $start + 1;
                $headers['Content-Range'] = "bytes $start-$end/$size";
            }
        }

        return response()->stream(function () use ($path, $start, $end) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);

            $bufferSize = 8192;
            $bytesRemaining = $end - $start + 1;

            while ($bytesRemaining > 0 && !feof($stream)) {
                $bytesToRead = min($bufferSize, $bytesRemaining);
                echo fread($stream, $bytesToRead);
                $bytesRemaining -= $bytesToRead;
                flush();
            }

            fclose($stream);
        }, $statusCode, $headers);
    }

    /**
     * Histórico de assinaturas do usuário.
     */
    public function subscriptions(Request $request)
    {
        $subscriptions = $request->user()
            ->subscriptions()
            ->with('plan')
            ->latest()
            ->paginate(10);

        return view('dashboard.subscriptions', compact('subscriptions'));
    }

    /**
     * Perfil do usuário.
     */
    public function profile(Request $request)
    {
        return view('dashboard.profile', [
            'user' => $request->user(),
        ]);
    }
}
