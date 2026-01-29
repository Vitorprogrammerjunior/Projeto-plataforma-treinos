<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Modo Demonstração</h2>
        <p class="text-gray-400 mt-2">Gateway de pagamento em modo de teste</p>
    </div>

    <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-white mb-4">Resumo do Registro</h3>
        
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Nome:</span>
                <span class="text-white">{{ $pendingRegistration->name }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-400">E-mail:</span>
                <span class="text-white">{{ $pendingRegistration->email }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Plano:</span>
                <span class="text-white">{{ $pendingRegistration->plan->name }}</span>
            </div>

            <hr class="border-gray-600 my-4">

            <div class="flex justify-between items-center text-lg">
                <span class="font-medium text-gray-300">Total:</span>
                <span class="font-bold text-red-500">
                    R$ {{ number_format($pendingRegistration->plan->price, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-blue-300 text-sm">
                    <strong>Ambiente de Teste:</strong> Nenhuma cobrança real será efetuada.
                    Clique no botão abaixo para simular um pagamento aprovado.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('payment.register.demo.confirm', $pendingRegistration) }}">
        @csrf

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02]">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simular Pagamento Aprovado
            </span>
        </button>
    </form>

    <a href="{{ route('register') }}" class="block w-full mt-4 text-center text-gray-400 hover:text-white transition">
        ← Voltar ao registro
    </a>
</x-guest-layout>
