<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Plano Alimentar de {{ $client->name }}
            </h2>
            <a href="{{ route('admin.clients.show', $client) }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Voltar para perfil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Criar Novo Plano Alimentar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ showForm: false }">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Criar Novo Plano Alimentar</h3>
                        <button @click="showForm = !showForm" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            <span x-show="!showForm">+ Novo Plano</span>
                            <span x-show="showForm">- Fechar</span>
                        </button>
                    </div>
                    
                    <form x-show="showForm" x-collapse action="{{ route('admin.clients.meal-plans.store', $client) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Título do Plano *</label>
                                <input type="text" name="title" required 
                                       placeholder="Ex: Dieta para Definição"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
                                <input type="text" name="objective" 
                                       placeholder="Ex: Perder gordura mantendo massa muscular"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Calorias/dia</label>
                                <input type="number" name="daily_calories" placeholder="2000"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Proteína (g)</label>
                                <input type="number" name="protein_grams" placeholder="150"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carboidratos (g)</label>
                                <input type="number" name="carbs_grams" placeholder="200"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gorduras (g)</label>
                                <input type="number" name="fat_grams" placeholder="60"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição / Observações Gerais</label>
                            <textarea name="description" rows="2" 
                                      placeholder="Instruções gerais sobre a dieta..."
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md transition-colors">
                                Criar Plano (com refeições padrão)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Planos Alimentares Existentes -->
            @forelse($client->mealPlans as $mealPlan)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ open: {{ $mealPlan->is_active ? 'true' : 'false' }}, addingMeal: null }">
                    <div class="p-6">
                        <!-- Header do Plano -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center cursor-pointer" @click="open = !open">
                                <svg class="w-5 h-5 mr-2 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $mealPlan->title }}</h4>
                                    @if($mealPlan->objective)
                                        <span class="text-sm text-green-600">{{ $mealPlan->objective }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($mealPlan->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inativo</span>
                                @endif
                                <form action="{{ route('admin.clients.meal-plans.destroy', [$client, $mealPlan]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este plano alimentar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Macros -->
                        @if($mealPlan->daily_calories || $mealPlan->protein_grams || $mealPlan->carbs_grams || $mealPlan->fat_grams)
                            <div class="flex flex-wrap gap-4 mb-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg">
                                @if($mealPlan->daily_calories)
                                    <div class="text-center">
                                        <span class="block text-2xl font-bold text-green-600">{{ number_format($mealPlan->daily_calories) }}</span>
                                        <span class="text-xs text-gray-500">kcal/dia</span>
                                    </div>
                                @endif
                                @if($mealPlan->protein_grams)
                                    <div class="text-center border-l pl-4">
                                        <span class="block text-2xl font-bold text-blue-600">{{ $mealPlan->protein_grams }}g</span>
                                        <span class="text-xs text-gray-500">Proteína</span>
                                    </div>
                                @endif
                                @if($mealPlan->carbs_grams)
                                    <div class="text-center border-l pl-4">
                                        <span class="block text-2xl font-bold text-yellow-600">{{ $mealPlan->carbs_grams }}g</span>
                                        <span class="text-xs text-gray-500">Carboidratos</span>
                                    </div>
                                @endif
                                @if($mealPlan->fat_grams)
                                    <div class="text-center border-l pl-4">
                                        <span class="block text-2xl font-bold text-red-500">{{ $mealPlan->fat_grams }}g</span>
                                        <span class="text-xs text-gray-500">Gorduras</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Refeições -->
                        <div x-show="open" x-collapse>
                            <div class="border-t pt-4 mt-4 space-y-6">
                                @foreach($mealPlan->meals as $meal)
                                    <div class="border rounded-lg overflow-hidden">
                                        <!-- Header da Refeição -->
                                        <div class="bg-gradient-to-r from-green-100 to-green-50 px-4 py-3 flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-green-800">{{ $meal->name }}</span>
                                                @if($meal->time)
                                                    <span class="text-sm text-green-600 ml-2">⏰ {{ $meal->formatted_time }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($meal->total_calories)
                                                    <span class="text-sm text-gray-600">{{ $meal->total_calories }} kcal</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Itens da Refeição -->
                                        <div class="p-4">
                                            @if($meal->items->count() > 0)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($meal->items as $item)
                                                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                                            @if($item->image)
                                                                <img src="{{ $item->image_url }}" class="w-16 h-16 rounded-lg object-cover mr-3">
                                                            @else
                                                                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                                    <span class="text-2xl">🥗</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1">
                                                                <div class="flex justify-between">
                                                                    <span class="font-medium text-gray-900">{{ $item->name }}</span>
                                                                    <form action="{{ route('admin.clients.meal-plans.items.destroy', [$client, $mealPlan, $meal, $item]) }}" 
                                                                          method="POST" class="inline"
                                                                          onsubmit="return confirm('Remover este alimento?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-red-400 hover:text-red-600">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                @if($item->quantity)
                                                                    <span class="text-sm text-gray-500">{{ $item->quantity }}</span>
                                                                @endif
                                                                @if($item->calories || $item->protein || $item->carbs || $item->fat)
                                                                    <div class="text-xs text-gray-400 mt-1">
                                                                        @if($item->calories)<span class="mr-2">{{ $item->calories }} kcal</span>@endif
                                                                        {{ $item->macros_text }}
                                                                    </div>
                                                                @endif
                                                                @if($item->notes)
                                                                    <p class="text-xs text-gray-500 mt-1 italic">{{ $item->notes }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-gray-400 text-sm text-center py-2">Nenhum alimento adicionado</p>
                                            @endif

                                            <!-- Botão Adicionar Alimento -->
                                            <div class="mt-3 pt-3 border-t">
                                                <button @click="addingMeal = addingMeal === {{ $meal->id }} ? null : {{ $meal->id }}" 
                                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                    + Adicionar Alimento
                                                </button>

                                                <!-- Form Adicionar Alimento -->
                                                <div x-show="addingMeal === {{ $meal->id }}" x-collapse class="mt-4 p-4 bg-green-50 rounded-lg">
                                                    <form action="{{ route('admin.clients.meal-plans.items.store', [$client, $mealPlan, $meal]) }}" 
                                                          method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Alimento *</label>
                                                                <input type="text" name="name" required placeholder="Ex: Frango grelhado"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                                                                <input type="text" name="quantity" placeholder="Ex: 150g"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Calorias</label>
                                                                <input type="number" name="calories" placeholder="200"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-3 gap-3 mb-3">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Proteína (g)</label>
                                                                <input type="number" name="protein" placeholder="30"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Carboidratos (g)</label>
                                                                <input type="number" name="carbs" placeholder="0"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Gorduras (g)</label>
                                                                <input type="number" name="fat" placeholder="5"
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                                                <input type="text" name="notes" placeholder="Sem sal, grelhado..."
                                                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto do Alimento</label>
                                                                <input type="file" name="image" accept="image/*"
                                                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200">
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="addingMeal = null" 
                                                                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-3 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                                                Adicionar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum plano alimentar criado</h3>
                        <p class="mt-1 text-sm text-gray-500">Crie o primeiro plano alimentar para este cliente.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
