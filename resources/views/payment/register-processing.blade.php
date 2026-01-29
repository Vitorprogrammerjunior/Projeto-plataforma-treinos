<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Processando Pagamento</h2>
        <p class="text-gray-400 mt-2">Aguarde enquanto confirmamos seu pagamento</p>
    </div>

    <div class="bg-gray-700/50 border border-gray-600 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-white mb-4">Informações do Registro</h3>
        
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
        </div>
    </div>

    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <div>
                <p class="text-blue-300 text-sm">
                    <strong>Aguardando confirmação:</strong> Estamos verificando seu pagamento junto ao processador.
                    Isso pode levar alguns minutos para pagamentos via PIX ou boleto.
                </p>
            </div>
        </div>
    </div>

    <div class="text-center">
        <p class="text-gray-400 text-sm mb-4">
            Caso o pagamento seja confirmado, você receberá um e-mail com suas credenciais de acesso.
        </p>
        
        <a href="{{ route('login') }}" class="inline-block text-red-500 hover:text-red-400 font-medium transition">
            Ir para página de login →
        </a>
    </div>

    <hr class="border-gray-700 my-6">

    <div class="text-center">
        <p class="text-gray-500 text-xs">
            Se você pagou via PIX, o processamento geralmente leva alguns segundos.
            <br>
            Para boleto, pode levar até 3 dias úteis.
        </p>
    </div>
</x-guest-layout>
