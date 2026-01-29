<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Vídeos') }} 🎬
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($videos->count() > 0)
                <!-- Header -->
                <div class="mb-6 bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl p-6 text-white shadow-lg">
                    <h3 class="text-2xl font-bold mb-2">Seus Vídeos Personalizados</h3>
                    <p class="text-pink-100">{{ $videos->count() }} vídeo(s) exclusivo(s) para você assistir e treinar! 🔥</p>
                </div>

                <!-- Grid de Vídeos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($videos as $video)
                        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-shadow">
                            <!-- Thumbnail -->
                            <div class="relative aspect-video bg-gray-100 group">
                                @if($video->thumbnail)
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                                         alt="{{ $video->title }}"
                                         class="w-full h-full object-cover">
                                @elseif($video->video_type === 'youtube' && $video->video_url)
                                    @php
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?]+)/', $video->video_url, $matches);
                                        $youtubeId = $matches[1] ?? null;
                                    @endphp
                                    @if($youtubeId)
                                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg" 
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-400 to-rose-500">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-400 to-rose-500">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Play button overlay -->
                                <a href="{{ $video->video_type === 'youtube' ? $video->video_url : asset('storage/' . $video->file_path) }}" 
                                   target="_blank"
                                   class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-100 opacity-90 group-hover:opacity-100 transition-all">
                                        <svg class="w-8 h-8 text-pink-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    </div>
                                </a>

                                <!-- Badge tipo -->
                                <div class="absolute top-3 left-3">
                                    @if($video->video_type === 'youtube')
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-600 text-white shadow">
                                            ▶ YouTube
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-blue-600 text-white shadow">
                                            📁 Vídeo
                                        </span>
                                    @endif
                                </div>

                                <!-- Categoria -->
                                @if($video->category)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded bg-pink-600 text-white shadow">
                                            {{ $video->category }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="p-5">
                                <h4 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $video->title }}</h4>
                                
                                @if($video->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $video->description }}</p>
                                @endif

                                @if($video->instructions)
                                    <div class="p-3 bg-pink-50 rounded-lg border-l-4 border-pink-400 mb-3">
                                        <p class="text-sm text-pink-700">
                                            <span class="font-bold">💡 Instruções:</span> {{ $video->instructions }}
                                        </p>
                                    </div>
                                @endif

                                <div class="flex justify-between items-center text-xs text-gray-400 pt-3 border-t">
                                    <span>Adicionado em {{ $video->created_at->format('d/m/Y') }}</span>
                                    <a href="{{ $video->video_type === 'youtube' ? $video->video_url : asset('storage/' . $video->file_path) }}" 
                                       target="_blank"
                                       class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Assistir →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- Estado vazio -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-5xl">🎬</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum vídeo disponível ainda</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Sua personal está preparando vídeos exclusivos para você. 
                            Em breve você terá acesso a conteúdos personalizados!
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
