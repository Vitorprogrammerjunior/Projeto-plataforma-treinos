<x-admin-layout>
    <x-slot name="title">Planos</x-slot>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-white">Gerenciar Planos</h2>
            <p class="text-gray-400 text-sm">Configure os planos de assinatura</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Plano
        </a>
    </div>

    <!-- Plans Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
            <div class="bg-gray-800 rounded-xl p-6 {{ $plan->is_featured ? 'ring-2 ring-red-600' : '' }}">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ $plan->slug }}</p>
                    </div>
                    <div class="flex gap-2">
                        @if($plan->is_active)
                            <span class="bg-green-600/20 text-green-500 text-xs font-bold px-2 py-1 rounded">Ativo</span>
                        @else
                            <span class="bg-gray-600/20 text-gray-500 text-xs font-bold px-2 py-1 rounded">Inativo</span>
                        @endif
                        @if($plan->is_featured)
                            <span class="bg-red-600/20 text-red-500 text-xs font-bold px-2 py-1 rounded">Destaque</span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-3xl font-bold text-white">{{ $plan->formatted_price }}</span>
                    <span class="text-gray-400">/{{ $plan->duration_days }} dias</span>
                </div>

                <p class="text-gray-400 text-sm mb-4">{{ $plan->description }}</p>

                <div class="text-gray-500 text-sm mb-4">
                    {{ $plan->subscriptions_count }} assinaturas
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white text-center py-2 rounded-lg transition">
                        Editar
                    </a>
                    <form action="{{ route('admin.plans.toggle-active', $plan) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full {{ $plan->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 rounded-lg transition">
                            {{ $plan->is_active ? 'Desativar' : 'Ativar' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $plans->links() }}
    </div>
</x-admin-layout>
