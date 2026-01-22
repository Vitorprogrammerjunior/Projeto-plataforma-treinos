<x-admin-layout>
    <x-slot name="title">Editar Vídeo</x-slot>

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.videos.index') }}" class="text-gray-400 hover:text-white inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para lista
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Editar Vídeo</h2>

            <form action="{{ route('admin.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Título -->
                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Título *</label>
                    <input 
                        type="text" 
                        name="title" 
                        value="{{ old('title', $video->title) }}"
                        required
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Slug (URL)</label>
                    <input 
                        type="text" 
                        name="slug" 
                        value="{{ old('slug', $video->slug) }}"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Descrição</label>
                    <textarea 
                        name="description" 
                        rows="4"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >{{ old('description', $video->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoria e Duração -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Categoria</label>
                        <input 
                            type="text" 
                            name="category" 
                            value="{{ old('category', $video->category) }}"
                            list="categories"
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        >
                        <datalist id="categories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Duração (segundos)</label>
                        <input 
                            type="number" 
                            name="duration_seconds" 
                            value="{{ old('duration_seconds', $video->duration_seconds) }}"
                            min="0"
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        >
                    </div>
                </div>

                <!-- Fonte do Vídeo -->
                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Fonte do Vídeo *</label>
                    <select 
                        name="video_source" 
                        id="video_source"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        onchange="toggleVideoSource()"
                    >
                        <option value="external" {{ old('video_source', $video->video_source) === 'external' ? 'selected' : '' }}>Link Externo (Vimeo/YouTube)</option>
                        <option value="local" {{ old('video_source', $video->video_source) === 'local' ? 'selected' : '' }}>Upload Local</option>
                    </select>
                </div>

                <!-- URL Externa -->
                <div id="video_url_field" class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">URL do Vídeo (Embed)</label>
                    <input 
                        type="url" 
                        name="video_url" 
                        value="{{ old('video_url', $video->video_url) }}"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >
                    @error('video_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Local -->
                <div id="video_file_field" class="mb-6 hidden">
                    <label class="block text-gray-300 font-medium mb-2">Arquivo de Vídeo</label>
                    @if($video->video_path)
                        <p class="text-green-500 text-sm mb-2">✓ Vídeo atual: {{ basename($video->video_path) }}</p>
                    @endif
                    <input 
                        type="file" 
                        name="video_file"
                        accept="video/mp4,video/webm,video/quicktime"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-red-600 file:text-white"
                    >
                    <p class="text-gray-500 text-sm mt-1">Deixe vazio para manter o vídeo atual</p>
                    @error('video_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Thumbnail</label>
                    @if($video->thumbnail)
                        <div class="mb-2">
                            <img src="{{ $video->thumbnail_url }}" alt="" class="w-40 h-24 object-cover rounded">
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="thumbnail"
                        accept="image/*"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-600 file:text-white"
                    >
                    @error('thumbnail')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordem e Flags -->
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Ordem</label>
                        <input 
                            type="number" 
                            name="order" 
                            value="{{ old('order', $video->order) }}"
                            min="0"
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        >
                    </div>
                    <div class="flex items-center pt-8">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }} class="w-5 h-5 bg-gray-700 border-gray-600 rounded text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-gray-300">Ativo</span>
                        </label>
                    </div>
                    <div class="flex items-center pt-8">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_free" value="1" {{ old('is_free', $video->is_free) ? 'checked' : '' }} class="w-5 h-5 bg-gray-700 border-gray-600 rounded text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-gray-300">Gratuito</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Salvar Alterações
                    </button>
                    <a href="{{ route('admin.videos.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleVideoSource() {
            const source = document.getElementById('video_source').value;
            const urlField = document.getElementById('video_url_field');
            const fileField = document.getElementById('video_file_field');
            
            if (source === 'external') {
                urlField.classList.remove('hidden');
                fileField.classList.add('hidden');
            } else {
                urlField.classList.add('hidden');
                fileField.classList.remove('hidden');
            }
        }
        toggleVideoSource();
    </script>
</x-admin-layout>
