<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cliente: {{ $client->name }}
            </h2>
            <a href="{{ route('admin.clients') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Voltar para lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info do Cliente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0 h-20 w-20 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h3>
                            <p class="text-gray-600">{{ $client->email }}</p>
                            @if($client->phone)
                                <p class="text-gray-600">{{ $client->phone }}</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-2">Cliente desde {{ $client->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            @if($client->activeSubscription)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Assinatura Ativa
                                </span>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $client->activeSubscription->plan->name ?? 'Plano' }} - 
                                    até {{ $client->activeSubscription->expires_at->format('d/m/Y') }}
                                </p>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Sem Assinatura Ativa
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('admin.clients.workouts', $client) }}" 
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Treinos</h4>
                                <p class="text-sm text-gray-500">{{ $client->workouts->where('is_active', true)->count() }} treinos ativos</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.clients.meal-plans', $client) }}" 
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Plano Alimentar</h4>
                                <p class="text-sm text-gray-500">
                                    @if($client->mealPlans->where('is_active', true)->first())
                                        Dieta configurada
                                    @else
                                        Não configurada
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.clients.videos', $client) }}" 
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-pink-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Vídeos</h4>
                                <p class="text-sm text-gray-500">{{ $client->videos->count() }} vídeos enviados</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Histórico de Assinaturas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Assinaturas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plano</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Início</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expira</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($client->subscriptions as $subscription)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $subscription->plan->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($subscription->payment_status === 'approved' && $subscription->expires_at > now())
                                                <span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativa</span>
                                            @elseif($subscription->payment_status === 'pending')
                                                <span class="px-2 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                            @else
                                                <span class="px-2 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Expirada</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subscription->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subscription->expires_at?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            R$ {{ number_format($subscription->amount_paid ?? 0, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                            Nenhuma assinatura encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
