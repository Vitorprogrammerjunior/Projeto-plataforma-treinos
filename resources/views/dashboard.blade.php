<x-app-layout>
    <x-slot name="title">Meus Vídeos - Adri Treinos</x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header com Status da Assinatura -->
            <div class="bg-gray-800 rounded-2xl p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Olá, {{ $user->name }}! 👋</h1>
                        @if($subscription)
                            <p class="text-green-500 mt-1">
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Acesso ativo até {{ $subscription->expires_at->format('d/m/Y') }}
                                    ({{ $subscription->days_remaining }} dias restantes)
                                </span>
                            </p>
                        @else
                            <p class="text-yellow-500 mt-1">
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Você está visualizando apenas conteúdo gratuito
                                </span>
                            </p>
                        @endif
                    </div>
                    @if(!$subscription)
                        <a href="{{ route('plans.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all text-center">
                            Liberar Todos os Vídeos
                        </a>
                    @endif
                </div>
            </div>

            <!-- Lista de Vídeos -->
            @if($videos->isEmpty())
                <div class="bg-gray-800 rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">Nenhum vídeo disponível</h3>
                    <p class="text-gray-400">Novos conteúdos em breve!</p>
                </div>
            @else
                @foreach($videosByCategory as $category => $categoryVideos)
                    <div class="mb-12">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                            <span class="w-2 h-8 bg-red-600 rounded mr-3"></span>
                            {{ $category ?? 'Outros' }}
                        </h2>
                        
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($categoryVideos as $video)
                                <div class="bg-gray-800 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group">
                                    <a href="{{ route('videos.watch', $video) }}" class="block">
                                        <div class="relative aspect-video bg-gray-700">
                                            @if($video->thumbnail)
                                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                            @endif
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @if($video->is_free)
                                                <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">GRÁTIS</span>
                                            @elseif(!$user->hasActiveAccess())
                                                <span class="absolute top-2 left-2 bg-yellow-600 text-white text-xs font-bold px-2 py-1 rounded">PREMIUM</span>
                                            @endif
                                            <span class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">{{ $video->formatted_duration }}</span>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-white font-bold text-sm mb-1 line-clamp-2">{{ $video->title }}</h3>
                                            <p class="text-gray-500 text-xs">{{ $video->views_count }} visualizações</p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
