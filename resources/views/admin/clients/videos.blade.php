<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vídeos de {{ $client->name }}
            </h2>
            <a href="{{ route('admin.clients.show', $client) }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Voltar para perfil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Enviar Novo Vídeo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ showForm: false, videoType: 'youtube' }">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Enviar Novo Vídeo</h3>
                        <button @click="showForm = !showForm" class="text-pink-600 hover:text-pink-800 text-sm font-medium">
                            <span x-show="!showForm">+ Novo Vídeo</span>
                            <span x-show="showForm">- Fechar</span>
                        </button>
                    </div>
                    
                    <form x-show="showForm" x-collapse action="{{ route('admin.clients.videos.store', $client) }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Título do Vídeo *</label>
                                <input type="text" name="title" required 
                                       placeholder="Ex: Execução correta do Agachamento"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                                <input type="text" name="category" 
                                       placeholder="Ex: Treino A, Cardio, Técnica..."
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500">
                            </div>
                        </div>

                        <!-- Tipo de Vídeo -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vídeo *</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="video_type" value="youtube" x-model="videoType" 
                                           class="text-pink-600 focus:ring-pink-500">
                                    <span class="ml-2 text-sm text-gray-700">Link do YouTube</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="video_type" value="upload" x-model="videoType"
                                           class="text-pink-600 focus:ring-pink-500">
                                    <span class="ml-2 text-sm text-gray-700">Upload de Arquivo</span>
                                </label>
                            </div>
                        </div>

                        <!-- YouTube URL -->
                        <div x-show="videoType === 'youtube'" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL do YouTube *</label>
                            <input type="url" name="video_url" 
                                   placeholder="https://www.youtube.com/watch?v=..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500"
                                   :required="videoType === 'youtube'">
                            <p class="text-xs text-gray-500 mt-1">Cole o link completo do vídeo do YouTube</p>
                        </div>

                        <!-- Upload de Arquivo -->
                        <div x-show="videoType === 'upload'" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo de Vídeo *</label>
                            <input type="file" name="video_file" accept="video/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                                   :required="videoType === 'upload'">
                            <p class="text-xs text-gray-500 mt-1">Formatos aceitos: MP4, MOV, AVI, WMV (máx. 500MB)</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <textarea name="description" rows="3" 
                                          placeholder="Descrição do vídeo..."
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Instruções para o Cliente</label>
                                <textarea name="instructions" rows="3" 
                                          placeholder="Ex: Assista este vídeo antes de fazer o Treino A..."
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500"></textarea>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail (opcional)</label>
                            <input type="file" name="thumbnail" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-6 rounded-md transition-colors">
                                Enviar Vídeo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Vídeos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($client->videos as $video)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <!-- Thumbnail -->
                        <div class="relative aspect-video bg-gray-100">
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
                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" 
                                         alt="{{ $video->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Badge tipo -->
                            <div class="absolute top-2 left-2">
                                @if($video->video_type === 'youtube')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-600 text-white">YouTube</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">Upload</span>
                                @endif
                            </div>

                            <!-- Badge status -->
                            <div class="absolute top-2 right-2">
                                @if($video->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-500 text-white">Ativo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-500 text-white">Inativo</span>
                                @endif
                            </div>

                            <!-- Play button overlay -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <a href="{{ $video->video_type === 'youtube' ? $video->video_url : asset('storage/' . $video->file_path) }}" 
                                   target="_blank"
                                   class="w-16 h-16 bg-white bg-opacity-80 rounded-full flex items-center justify-center hover:bg-opacity-100 transition-all">
                                    <svg class="w-8 h-8 text-pink-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $video->title }}</h4>
                            @if($video->category)
                                <span class="text-xs text-pink-600 font-medium">{{ $video->category }}</span>
                            @endif
                            @if($video->description)
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $video->description }}</p>
                            @endif
                            <div class="mt-3 flex justify-between items-center text-xs text-gray-400">
                                <span>{{ $video->created_at->format('d/m/Y') }}</span>
                                <form action="{{ route('admin.clients.videos.destroy', [$client, $video]) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Excluir este vídeo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum vídeo enviado</h3>
                            <p class="mt-1 text-sm text-gray-500">Envie o primeiro vídeo personalizado para este cliente.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
