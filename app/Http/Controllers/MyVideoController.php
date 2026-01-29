<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyVideoController extends Controller
{
    /**
     * Exibe os vídeos personalizados do cliente.
     */
    public function index()
    {
        $user = Auth::user();
        
        $videos = $user->videos()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.videos', compact('videos'));
    }

    /**
     * Exibe um vídeo específico.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $video = $user->videos()
            ->where('is_active', true)
            ->findOrFail($id);

        return view('dashboard.video-detail', compact('video'));
    }
}
