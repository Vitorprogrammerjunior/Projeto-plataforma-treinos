<x-app-layout>
    <x-slot name="title">Minhas Assinaturas - Adri Treinos</x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-white mb-8">Minhas Assinaturas</h1>

            @if($subscriptions->isEmpty())
                <div class="bg-gray-800 rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">Nenhuma assinatura</h3>
                    <p class="text-gray-400 mb-6">Você ainda não possui nenhuma assinatura.</p>
                    <a href="{{ route('plans.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Ver Planos
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($subscriptions as $subscription)
                        <div class="bg-gray-800 rounded-xl p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-white">{{ $subscription->plan->name }}</h3>
                                        @if($subscription->isActive())
                                            <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">ATIVO</span>
                                        @elseif($subscription->payment_status === 'pending')
                                            <span class="bg-yellow-600/20 text-yellow-500 text-xs font-bold px-2 py-1 rounded">PENDENTE</span>
                                        @elseif($subscription->payment_status === 'failed')
                                            <span class="bg-red-600/20 text-red-500 text-xs font-bold px-2 py-1 rounded">FALHOU</span>
                                        @else
                                            <span class="bg-gray-600/20 text-gray-500 text-xs font-bold px-2 py-1 rounded">EXPIRADO</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-400 text-sm">
                                        Comprado em {{ $subscription->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-white font-bold">R$ {{ number_format($subscription->amount_paid, 2, ',', '.') }}</p>
                                    @if($subscription->expires_at)
                                        <p class="text-sm {{ $subscription->isActive() ? 'text-green-500' : 'text-gray-500' }}">
                                            {{ $subscription->isActive() ? 'Expira' : 'Expirou' }} em {{ $subscription->expires_at->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $subscriptions->links() }}
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('plans.index') }}" class="text-red-500 hover:text-red-400 font-medium">
                    Ver todos os planos disponíveis →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
