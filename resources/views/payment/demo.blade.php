<x-app-layout>
    <x-slot name="title">Confirmar Pagamento - Adri Treinos</x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-2xl p-8 text-center">
                <!-- Ícone -->
                <div class="w-20 h-20 bg-yellow-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-white mb-2">Confirmar Pagamento</h1>
                <p class="text-gray-400 mb-6">Modo demonstração ativo</p>

                <!-- Detalhes do Plano -->
                <div class="bg-gray-900 rounded-xl p-6 mb-6 text-left">
                    <h3 class="text-white font-bold mb-4">Resumo do pedido</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Plano</span>
                            <span class="text-white">{{ $subscription->plan->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Duração</span>
                            <span class="text-white">{{ $subscription->plan->duration_days }} dias</span>
                        </div>
                        <hr class="border-gray-700">
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-400 font-bold">Total</span>
                            <span class="text-red-600 font-bold">{{ $subscription->plan->formatted_price }}</span>
                        </div>
                    </div>
                </div>

                <!-- Aviso Demo -->
                <div class="bg-blue-900/30 border border-blue-600/50 rounded-lg p-4 mb-6">
                    <p class="text-blue-400 text-sm">
                        <strong>Modo Demonstração:</strong> Em produção, você seria redirecionado para o gateway de pagamento (Stripe/Mercado Pago).
                    </p>
                </div>

                <!-- Botão de Confirmação -->
                <form action="{{ route('payment.demo.confirm', $subscription) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition-all mb-4">
                        Simular Pagamento Aprovado
                    </button>
                </form>

                <a href="{{ route('plans.index') }}" class="text-gray-400 hover:text-white transition">
                    Cancelar e voltar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
