<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total de Usuários</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Assinaturas Ativas</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['active_subscriptions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Videos -->
        <div class="bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total de Vídeos</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($stats['total_videos']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Receita Total</p>
                    <p class="text-3xl font-bold text-white mt-1">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- NOVO: Gerenciar Clientes (PRINCIPAL) -->
        <a href="{{ route('admin.clients') }}" class="bg-gradient-to-br from-pink-600 to-purple-600 hover:from-pink-500 hover:to-purple-500 rounded-xl p-6 flex items-center transition shadow-lg">
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold">Gerenciar Clientes</h3>
                <p class="text-white/70 text-sm">Treinos, dietas e vídeos</p>
            </div>
        </a>

        <a href="{{ route('admin.videos.create') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 flex items-center transition">
            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold">Adicionar Vídeo</h3>
                <p class="text-gray-400 text-sm">Vídeo geral da plataforma</p>
            </div>
        </a>

        <a href="{{ route('admin.plans.create') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 flex items-center transition">
            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold">Criar Plano</h3>
                <p class="text-gray-400 text-sm">Novo plano de assinatura</p>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 flex items-center transition">
            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-bold">Ver Usuários</h3>
                <p class="text-gray-400 text-sm">Acessos e assinaturas</p>
            </div>
        </a>
    </div>

    <!-- Recent Subscriptions -->
    <div class="bg-gray-800 rounded-xl p-6">
        <h2 class="text-white font-bold text-lg mb-6">Últimas Assinaturas</h2>
        
        @if($recentSubscriptions->isEmpty())
            <p class="text-gray-400 text-center py-8">Nenhuma assinatura ainda.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-gray-400 text-left text-sm">
                            <th class="pb-4">Usuário</th>
                            <th class="pb-4">Plano</th>
                            <th class="pb-4">Valor</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Data</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($recentSubscriptions as $subscription)
                            <tr class="border-t border-gray-700">
                                <td class="py-4">
                                    <div>
                                        <p class="font-medium text-white">{{ $subscription->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $subscription->user->email }}</p>
                                    </div>
                                </td>
                                <td class="py-4">{{ $subscription->plan->name }}</td>
                                <td class="py-4">R$ {{ number_format($subscription->amount_paid, 2, ',', '.') }}</td>
                                <td class="py-4">
                                    @if($subscription->payment_status === 'approved')
                                        <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Aprovado</span>
                                    @elseif($subscription->payment_status === 'pending')
                                        <span class="bg-yellow-600/20 text-yellow-500 text-xs font-bold px-2 py-1 rounded">Pendente</span>
                                    @else
                                        <span class="bg-red-600/20 text-red-500 text-xs font-bold px-2 py-1 rounded">Falhou</span>
                                    @endif
                                </td>
                                <td class="py-4">{{ $subscription->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin-layout>
