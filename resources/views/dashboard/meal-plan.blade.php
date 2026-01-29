<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minha Dieta') }} 🥗
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($mealPlan)
                <!-- Header do Plano -->
                <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
                    <h3 class="text-2xl font-bold mb-2">{{ $mealPlan->title }}</h3>
                    @if($mealPlan->objective)
                        <p class="text-green-100 mb-4">🎯 {{ $mealPlan->objective }}</p>
                    @endif

                    <!-- Macros -->
                    @if($mealPlan->daily_calories || $mealPlan->protein_grams || $mealPlan->carbs_grams || $mealPlan->fat_grams)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            @if($mealPlan->daily_calories)
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                                    <span class="block text-3xl font-bold">{{ number_format($mealPlan->daily_calories) }}</span>
                                    <span class="text-sm text-green-100">kcal/dia</span>
                                </div>
                            @endif
                            @if($mealPlan->protein_grams)
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                                    <span class="block text-3xl font-bold">{{ $mealPlan->protein_grams }}g</span>
                                    <span class="text-sm text-green-100">Proteína</span>
                                </div>
                            @endif
                            @if($mealPlan->carbs_grams)
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                                    <span class="block text-3xl font-bold">{{ $mealPlan->carbs_grams }}g</span>
                                    <span class="text-sm text-green-100">Carboidratos</span>
                                </div>
                            @endif
                            @if($mealPlan->fat_grams)
                                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                                    <span class="block text-3xl font-bold">{{ $mealPlan->fat_grams }}g</span>
                                    <span class="text-sm text-green-100">Gorduras</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Descrição/Observações Gerais -->
                @if($mealPlan->description || $mealPlan->notes)
                    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-xl p-4">
                        <div class="flex">
                            <span class="text-2xl mr-3">💡</span>
                            <div>
                                <h4 class="font-bold text-yellow-800">Observações Importantes</h4>
                                <p class="text-yellow-700 text-sm mt-1">{{ $mealPlan->description ?? $mealPlan->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Refeições -->
                <div class="space-y-6">
                    @foreach($mealPlan->meals as $meal)
                        <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                            <!-- Header da Refeição -->
                            <div class="bg-gradient-to-r from-green-400 to-green-500 px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-white">
                                        @php
                                            $emoji = match(true) {
                                                str_contains(strtolower($meal->name), 'café') => '☕',
                                                str_contains(strtolower($meal->name), 'manhã') && str_contains(strtolower($meal->name), 'lanche') => '🍎',
                                                str_contains(strtolower($meal->name), 'almoço') => '🍽️',
                                                str_contains(strtolower($meal->name), 'tarde') => '🥤',
                                                str_contains(strtolower($meal->name), 'jantar') => '🥗',
                                                str_contains(strtolower($meal->name), 'ceia') => '🌙',
                                                default => '🍴'
                                            };
                                        @endphp
                                        <span class="text-3xl mr-3">{{ $emoji }}</span>
                                        <div>
                                            <h3 class="text-xl font-bold">{{ $meal->name }}</h3>
                                            @if($meal->time)
                                                <span class="text-green-100 text-sm">⏰ {{ $meal->formatted_time }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($meal->total_calories)
                                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium text-white">
                                            {{ $meal->total_calories }} kcal
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Itens da Refeição -->
                            <div class="p-6">
                                @if($meal->items->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($meal->items as $item)
                                            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-green-50 transition-colors">
                                                <!-- Imagem -->
                                                @if($item->image)
                                                    <img src="{{ $item->image_url }}" 
                                                         alt="{{ $item->name }}"
                                                         class="w-20 h-20 rounded-xl object-cover shadow-md mr-4">
                                                @else
                                                    <div class="w-20 h-20 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                                        <span class="text-3xl">🥗</span>
                                                    </div>
                                                @endif

                                                <!-- Info -->
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-gray-900">{{ $item->name }}</h4>
                                                    @if($item->quantity)
                                                        <span class="text-sm text-green-600 font-medium">{{ $item->quantity }}</span>
                                                    @endif
                                                    
                                                    <!-- Macros do item -->
                                                    @if($item->calories || $item->protein || $item->carbs || $item->fat)
                                                        <div class="flex flex-wrap gap-1 mt-2">
                                                            @if($item->calories)
                                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">{{ $item->calories }} kcal</span>
                                                            @endif
                                                            @if($item->protein)
                                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">P: {{ $item->protein }}g</span>
                                                            @endif
                                                            @if($item->carbs)
                                                                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">C: {{ $item->carbs }}g</span>
                                                            @endif
                                                            @if($item->fat)
                                                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">G: {{ $item->fat }}g</span>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($item->notes)
                                                        <p class="text-xs text-gray-500 mt-2 italic">{{ $item->notes }}</p>
                                                    @endif

                                                    @if($item->alternatives)
                                                        <p class="text-xs text-green-600 mt-1">🔄 Substitutos: {{ $item->alternatives }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-gray-400">
                                        <p>Aguardando alimentos serem adicionados</p>
                                    </div>
                                @endif

                                @if($meal->description)
                                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                                        <p class="text-sm text-green-700">📝 {{ $meal->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Histórico de Planos -->
                @if($allMealPlans->count() > 1)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">📋 Histórico de Planos</h3>
                        <div class="bg-white rounded-xl shadow p-4">
                            <div class="space-y-2">
                                @foreach($allMealPlans as $plan)
                                    <div class="flex justify-between items-center p-3 rounded-lg {{ $plan->is_active ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                        <div>
                                            <span class="font-medium {{ $plan->is_active ? 'text-green-800' : 'text-gray-700' }}">
                                                {{ $plan->title }}
                                            </span>
                                            @if($plan->is_active)
                                                <span class="ml-2 text-xs bg-green-500 text-white px-2 py-0.5 rounded">Atual</span>
                                            @endif
                                            <span class="text-sm text-gray-500 ml-2">{{ $plan->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        @if(!$plan->is_active)
                                            <a href="{{ route('my.meal-plan.show', $plan->id) }}" class="text-green-600 hover:text-green-800 text-sm">
                                                Ver →
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            @else
                <!-- Estado vazio -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-5xl">🥗</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum plano alimentar disponível ainda</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Sua personal está preparando seu plano alimentar personalizado. 
                            Em breve você verá sua dieta completa aqui!
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
