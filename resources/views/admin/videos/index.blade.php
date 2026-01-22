<x-admin-layout>
    <x-slot name="title">Vídeos</x-slot>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Gerenciar Vídeos</h2>
            <p class="text-gray-400 text-sm">{{ $videos->total() }} vídeos cadastrados</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Vídeo
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Buscar por título..."
                class="flex-1 bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
            >
            <select name="category" class="bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500">
                <option value="">Todas categorias</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Videos Table -->
    <div class="bg-gray-800 rounded-xl overflow-hidden">
        @if($videos->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-white font-bold mb-2">Nenhum vídeo encontrado</h3>
                <p class="text-gray-400">Comece adicionando seu primeiro vídeo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr class="text-gray-400 text-left text-sm">
                            <th class="px-6 py-4">Vídeo</th>
                            <th class="px-6 py-4">Categoria</th>
                            <th class="px-6 py-4">Duração</th>
                            <th class="px-6 py-4">Views</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($videos as $video)
                            <tr class="border-t border-gray-700 hover:bg-gray-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-20 h-12 bg-gray-700 rounded overflow-hidden mr-4 flex-shrink-0">
                                            @if($video->thumbnail)
                                                <img src="{{ $video->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $video->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $video->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $video->category ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $video->formatted_duration }}</td>
                                <td class="px-6 py-4">{{ number_format($video->views_count) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($video->is_active)
                                            <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Ativo</span>
                                        @else
                                            <span class="bg-gray-600/20 text-gray-500 text-xs font-bold px-2 py-1 rounded">Inativo</span>
                                        @endif
                                        @if($video->is_free)
                                            <span class="bg-blue-600/20 text-blue-500 text-xs font-bold px-2 py-1 rounded">Grátis</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.videos.edit', $video) }}" class="text-gray-400 hover:text-white transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este vídeo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
