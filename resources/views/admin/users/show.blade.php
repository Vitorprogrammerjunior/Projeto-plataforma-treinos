<x-admin-layout>
    <x-slot name="title">Detalhes do Usuário</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para lista
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- User Info -->
            <div class="md:col-span-1">
                <div class="bg-gray-800 rounded-xl p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-gray-400">{{ $user->email }}</p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Cadastro</span>
                            <span class="text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            @if($user->hasActiveAccess())
                                <span class="text-green-500">Ativo</span>
                            @else
                                <span class="text-gray-500">Sem acesso</span>
                            @endif
                        </div>
                        @if($user->phone)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Telefone</span>
                                <span class="text-white">{{ $user->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-700 space-y-3">
                        @if($user->hasActiveAccess())
                            <form action="{{ route('admin.users.revoke-access', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja revogar o acesso?')">
                                @csrf
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition">
                                    Revogar Acesso
                                </button>
                            </form>
                        @else
                            <button 
                                onclick="document.getElementById('grant-modal').classList.remove('hidden')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition"
                            >
                                Conceder Acesso
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subscription History -->
            <div class="md:col-span-2">
                <div class="bg-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-white mb-6">Histórico de Assinaturas</h3>

                    @if($subscriptionHistory->isEmpty())
                        <p class="text-gray-400 text-center py-8">Nenhuma assinatura registrada.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($subscriptionHistory as $subscription)
                                <div class="bg-gray-900 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-white font-medium">{{ $subscription->plan->name }}</h4>
                                            <p class="text-gray-500 text-sm">
                                                {{ $subscription->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($subscription->payment_status === 'approved')
                                                <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Aprovado</span>
                                            @elseif($subscription->payment_status === 'pending')
                                                <span class="bg-yellow-600/20 text-yellow-500 text-xs font-bold px-2 py-1 rounded">Pendente</span>
                                            @else
                                                <span class="bg-red-600/20 text-red-500 text-xs font-bold px-2 py-1 rounded">Falhou</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-400">Valor</span>
                                            <p class="text-white">R$ {{ number_format($subscription->amount_paid, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400">Início</span>
                                            <p class="text-white">{{ $subscription->starts_at?->format('d/m/Y') ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-400">Expiração</span>
                                            <p class="{{ $subscription->isActive() ? 'text-green-500' : 'text-white' }}">
                                                {{ $subscription->expires_at?->format('d/m/Y') ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grant Access Modal -->
    <div id="grant-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-white mb-4">Conceder Acesso Manual</h3>
            
            <form action="{{ route('admin.users.grant-access', $user) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-300 font-medium mb-2">Plano</label>
                    <select name="plan_id" required class="w-full bg-gray-700 border-gray-600 text-white rounded-lg">
                        @foreach(\App\Models\Plan::active()->get() as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->duration_days }} dias)</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Duração (dias)</label>
                    <input 
                        type="number" 
                        name="days" 
                        value="30"
                        min="1"
                        max="365"
                        required
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg"
                    >
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition">
                        Conceder
                    </button>
                    <button 
                        type="button" 
                        onclick="document.getElementById('grant-modal').classList.add('hidden')"
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 rounded-lg transition"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
