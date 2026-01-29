<x-admin-layout>
    <x-slot name="title">Abas</x-slot>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Gerenciar Abas</h2>
            <p class="text-gray-400 text-sm">Organize os vídeos em abas/categorias</p>
        </div>
        <a href="{{ route('admin.tabs.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Aba
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-600/20 border border-green-600 text-green-400 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs Table -->
    <div class="bg-gray-800 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="text-left text-gray-300 font-semibold px-6 py-4">Ordem</th>
                    <th class="text-left text-gray-300 font-semibold px-6 py-4">Nome</th>
                    <th class="text-left text-gray-300 font-semibold px-6 py-4">Vídeos</th>
                    <th class="text-left text-gray-300 font-semibold px-6 py-4">Status</th>
                    <th class="text-right text-gray-300 font-semibold px-6 py-4">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700" id="tabs-sortable">
                @forelse($tabs as $tab)
                    <tr class="hover:bg-gray-700/50 transition" data-id="{{ $tab->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 cursor-move handle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                                <span class="text-gray-400 font-mono">{{ $tab->order }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($tab->icon)
                                    <span class="text-2xl">{{ $tab->icon }}</span>
                                @else
                                    <div class="w-8 h-8 bg-red-600/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-white font-medium">{{ $tab->name }}</p>
                                    <p class="text-gray-500 text-sm">{{ $tab->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-300">{{ $tab->videos_count }} vídeos</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($tab->is_active)
                                <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Ativa</span>
                            @else
                                <span class="bg-gray-600/20 text-gray-500 text-xs font-bold px-2 py-1 rounded">Inativa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tabs.edit', $tab) }}" class="text-gray-400 hover:text-white transition" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.tabs.toggle-active', $tab) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-yellow-500 transition" title="{{ $tab->is_active ? 'Desativar' : 'Ativar' }}">
                                        @if($tab->is_active)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.tabs.destroy', $tab) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza? Os vídeos desta aba ficarão sem categoria.')">
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
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <p class="text-lg font-medium">Nenhuma aba criada</p>
                                <p class="text-sm">Crie abas para organizar seus vídeos</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Dica -->
    <div class="mt-6 bg-gray-800/50 border border-gray-700 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-gray-400">
                <p class="font-medium text-gray-300 mb-1">Dica:</p>
                <p>Arraste as linhas para reordenar as abas. A ordem definida aqui será a mesma exibida para os usuários.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortable = new Sortable(document.getElementById('tabs-sortable'), {
                animation: 150,
                handle: '.handle',
                onEnd: function() {
                    const order = Array.from(document.querySelectorAll('#tabs-sortable tr[data-id]'))
                        .map(row => row.dataset.id);
                    
                    fetch('{{ route('admin.tabs.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order })
                    });
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
