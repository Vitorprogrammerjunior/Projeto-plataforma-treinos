<x-app-layout>
    <x-slot name="title">{{ $video->title }} - Adri Treinos</x-slot>

    <div class="min-h-screen bg-gray-900">
        <!-- Player Container -->
        <div class="bg-black">
            <div class="max-w-6xl mx-auto">
                <div class="relative aspect-video">
                    @if($video->video_source === 'external' && $video->video_url)
                        <!-- Vimeo ou YouTube Embed -->
                        <iframe 
                            src="{{ $video->video_url }}" 
                            class="absolute inset-0 w-full h-full"
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <!-- Player HTML5 para vídeos locais -->
                        <video 
                            id="videoPlayer"
                            class="w-full h-full"
                            controls
                            controlsList="nodownload"
                            oncontextmenu="return false;"
                            poster="{{ $video->thumbnail_url }}"
                        >
                            <source src="{{ route('videos.stream', $video) }}" type="video/mp4">
                            Seu navegador não suporta vídeos HTML5.
                        </video>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info do Vídeo -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Coluna Principal -->
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">{{ $video->title }}</h1>
                            <div class="flex items-center gap-4 text-gray-400 text-sm">
                                <span>{{ $video->category }}</span>
                                <span>•</span>
                                <span>{{ $video->formatted_duration }}</span>
                                <span>•</span>
                                <span>{{ number_format($video->views_count) }} visualizações</span>
                            </div>
                        </div>
                        @if($video->is_free)
                            <span class="bg-green-600 text-white text-sm font-bold px-3 py-1 rounded-full">GRÁTIS</span>
                        @endif
                    </div>

                    @if($video->description)
                        <div class="bg-gray-800 rounded-xl p-6 mt-6">
                            <h3 class="text-white font-bold mb-3">Sobre este treino</h3>
                            <p class="text-gray-300 whitespace-pre-line">{{ $video->description }}</p>
                        </div>
                    @endif

                    <!-- Navegação -->
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-400 hover:text-white transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar para meus vídeos
                        </a>
                    </div>
                </div>

                <!-- Sidebar - Vídeos Relacionados -->
                @if($relatedVideos->count() > 0)
                    <div class="lg:w-80">
                        <h3 class="text-white font-bold mb-4">Próximos treinos</h3>
                        <div class="space-y-4">
                            @foreach($relatedVideos as $related)
                                <a href="{{ route('videos.watch', $related) }}" class="flex gap-3 group">
                                    <div class="relative w-32 flex-shrink-0">
                                        <div class="aspect-video bg-gray-700 rounded overflow-hidden">
                                            @if($related->thumbnail)
                                                <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                            @endif
                                        </div>
                                        <span class="absolute bottom-1 right-1 bg-black/80 text-white text-xs px-1 rounded">{{ $related->formatted_duration }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-white text-sm font-medium line-clamp-2 group-hover:text-red-500 transition">{{ $related->title }}</h4>
                                        <p class="text-gray-500 text-xs mt-1">{{ $related->category }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Script para prevenir download -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('videoPlayer');
            if (video) {
                // Previne arrastar
                video.addEventListener('dragstart', e => e.preventDefault());
                
                // Previne clique direito
                video.addEventListener('contextmenu', e => e.preventDefault());
            }
        });
    </script>
</x-app-layout>
