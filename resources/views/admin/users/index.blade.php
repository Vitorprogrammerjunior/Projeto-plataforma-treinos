<x-admin-layout>
    <x-slot name="title">Usuários</x-slot>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Gerenciar Usuários</h2>
            <p class="text-gray-400 text-sm">{{ $users->total() }} usuários cadastrados</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Buscar por nome ou email..."
                class="flex-1 bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
            >
            <select name="status" class="bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500">
                <option value="">Todos</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Com acesso ativo</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Sem acesso</option>
            </select>
            <button type="submit" class="bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg transition">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-800 rounded-xl overflow-hidden">
        @if($users->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-white font-bold mb-2">Nenhum usuário encontrado</h3>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr class="text-gray-400 text-left text-sm">
                            <th class="px-6 py-4">Usuário</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Plano Atual</th>
                            <th class="px-6 py-4">Expira em</th>
                            <th class="px-6 py-4">Cadastro</th>
                            <th class="px-6 py-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($users as $user)
                            <tr class="border-t border-gray-700 hover:bg-gray-700/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-white">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->activeSubscription)
                                        <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Ativo</span>
                                    @else
                                        <span class="bg-gray-600/20 text-gray-500 text-xs font-bold px-2 py-1 rounded">Sem acesso</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->activeSubscription?->plan?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->activeSubscription)
                                        <span class="{{ $user->activeSubscription->days_remaining < 7 ? 'text-yellow-500' : 'text-gray-300' }}">
                                            {{ $user->activeSubscription->expires_at->format('d/m/Y') }}
                                            ({{ $user->activeSubscription->days_remaining }}d)
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-red-500 hover:text-red-400 font-medium">
                                        Ver detalhes
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
