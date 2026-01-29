<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Treinos') }} 💪
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($workouts->count() > 0)
                <!-- Resumo -->
                <div class="mb-6 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">Seu Plano de Treino</h3>
                    <p class="text-purple-100">Você tem {{ $workouts->count() }} treino(s) ativo(s). Bora treinar! 🔥</p>
                </div>

                <!-- Lista de Treinos -->
                <div class="space-y-6">
                    @foreach($workouts as $workout)
                        <div class="bg-white overflow-hidden shadow-lg rounded-xl" x-data="{ open: true }">
                            <!-- Header do Treino -->
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 cursor-pointer" @click="open = !open">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-white">
                                        <svg class="w-6 h-6 mr-3 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <div>
                                            <h3 class="text-xl font-bold">{{ $workout->title }}</h3>
                                            @if($workout->day_of_week)
                                                <span class="text-purple-200 text-sm">{{ $workout->day_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-medium text-white">
                                        {{ $workout->exercises->count() }} exercícios
                                    </span>
                                </div>
                            </div>

                            <!-- Descrição -->
                            @if($workout->description)
                                <div class="bg-purple-50 px-6 py-3 border-b">
                                    <p class="text-purple-800 text-sm">📝 {{ $workout->description }}</p>
                                </div>
                            @endif

                            <!-- Lista de Exercícios -->
                            <div x-show="open" x-collapse class="p-6">
                                @if($workout->exercises->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($workout->exercises as $index => $exercise)
                                            <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                                <!-- Número -->
                                                <div class="flex-shrink-0 w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                                    {{ $index + 1 }}
                                                </div>

                                                <!-- Imagem (se tiver) -->
                                                @if($exercise->image)
                                                    <div class="flex-shrink-0 mr-4">
                                                        <img src="{{ asset('storage/' . $exercise->image) }}" 
                                                             alt="{{ $exercise->name }}"
                                                             class="w-20 h-20 rounded-lg object-cover shadow-md">
                                                    </div>
                                                @endif

                                                <!-- Info do Exercício -->
                                                <div class="flex-1">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <h4 class="font-bold text-gray-900 text-lg">{{ $exercise->name }}</h4>
                                                            
                                                            <!-- Tags de info -->
                                                            <div class="flex flex-wrap gap-2 mt-2">
                                                                @if($exercise->sets)
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                                        🔄 {{ $exercise->sets }} séries
                                                                    </span>
                                                                @endif
                                                                @if($exercise->reps)
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                                                        💪 {{ $exercise->reps }} reps
                                                                    </span>
                                                                @endif
                                                                @if($exercise->rest)
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                                        ⏱️ {{ $exercise->rest }} descanso
                                                                    </span>
                                                                @endif
                                                                @if($exercise->weight)
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                                        🏋️ {{ $exercise->weight }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @if($exercise->video_url)
                                                            <a href="{{ $exercise->video_url }}" target="_blank"
                                                               class="flex-shrink-0 ml-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                                ▶️ Ver Vídeo
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @if($exercise->notes)
                                                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                                                            <p class="text-sm text-yellow-800">💡 {{ $exercise->notes }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>Aguardando exercícios serem adicionados pela personal.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vazio -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-5xl">🏋️</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nenhum treino disponível ainda</h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Sua personal está preparando seu plano de treino personalizado. 
                            Em breve você verá seus exercícios aqui!
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
