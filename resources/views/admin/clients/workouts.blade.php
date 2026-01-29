<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Treinos de {{ $client->name }}
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

            <!-- Criar Novo Treino -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Criar Novo Treino</h3>
                    <form action="{{ route('admin.clients.workouts.store', $client) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Treino *</label>
                                <input type="text" name="title" required 
                                       placeholder="Ex: Treino A - Peito e Tríceps"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dia da Semana</label>
                                <select name="day_of_week" class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    <option value="">Selecione...</option>
                                    <option value="segunda">Segunda-feira</option>
                                    <option value="terca">Terça-feira</option>
                                    <option value="quarta">Quarta-feira</option>
                                    <option value="quinta">Quinta-feira</option>
                                    <option value="sexta">Sexta-feira</option>
                                    <option value="sabado">Sábado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md transition-colors">
                                    + Criar Treino
                                </button>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição / Observações</label>
                            <textarea name="description" rows="2" 
                                      placeholder="Observações gerais sobre o treino..."
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500"></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Treinos -->
            @forelse($client->workouts as $workout)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ open: true, addExercise: false }">
                    <div class="p-6">
                        <!-- Header do Treino -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center cursor-pointer" @click="open = !open">
                                <svg class="w-5 h-5 mr-2 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $workout->title }}</h4>
                                    @if($workout->day_of_week)
                                        <span class="text-sm text-purple-600 font-medium">{{ $workout->day_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($workout->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inativo</span>
                                @endif
                                <form action="{{ route('admin.clients.workouts.destroy', [$client, $workout]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este treino?')">
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

                        @if($workout->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $workout->description }}</p>
                        @endif

                        <!-- Exercícios do Treino -->
                        <div x-show="open" x-collapse>
                            <div class="border-t pt-4 mt-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exercício</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Séries</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reps</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descanso</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Peso</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Obs</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($workout->exercises as $exercise)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        @if($exercise->image)
                                                            <img src="{{ asset('storage/' . $exercise->image) }}" class="w-10 h-10 rounded object-cover mr-3">
                                                        @endif
                                                        <div>
                                                            <span class="font-medium text-gray-900">{{ $exercise->name }}</span>
                                                            @if($exercise->video_url)
                                                                <a href="{{ $exercise->video_url }}" target="_blank" class="ml-2 text-pink-600 text-xs">
                                                                    🎬 Ver vídeo
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $exercise->sets ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $exercise->reps ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $exercise->rest ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $exercise->weight ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate" title="{{ $exercise->notes }}">
                                                    {{ $exercise->notes ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <form action="{{ route('admin.clients.workouts.exercises.destroy', [$client, $workout, $exercise]) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('Excluir este exercício?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 text-sm">
                                                    Nenhum exercício adicionado ainda.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Adicionar Exercício -->
                                <div class="mt-4">
                                    <button @click="addExercise = !addExercise" 
                                            class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                        + Adicionar Exercício
                                    </button>

                                    <div x-show="addExercise" x-collapse class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <form action="{{ route('admin.clients.workouts.exercises.store', [$client, $workout]) }}" 
                                              method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Exercício *</label>
                                                    <input type="text" name="name" required 
                                                           placeholder="Ex: Supino Reto"
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Séries</label>
                                                    <input type="number" name="sets" min="1" placeholder="4"
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Repetições</label>
                                                    <input type="text" name="reps" placeholder="12-15"
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descanso</label>
                                                    <input type="text" name="rest" placeholder="60s"
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso Sugerido</label>
                                                    <input type="text" name="weight" placeholder="20kg"
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Link do Vídeo</label>
                                                    <input type="url" name="video_url" placeholder="https://youtube.com/..."
                                                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                </div>
                                            </div>
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                                    <textarea name="notes" rows="2" placeholder="Dicas de execução..."
                                                              class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagem do Exercício</label>
                                                    <input type="file" name="image" accept="image/*"
                                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                                </div>
                                            </div>
                                            <div class="mt-4 flex justify-end space-x-2">
                                                <button type="button" @click="addExercise = false" 
                                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                                                    Cancelar
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700">
                                                    Adicionar Exercício
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum treino criado</h3>
                        <p class="mt-1 text-sm text-gray-500">Comece criando o primeiro treino para este cliente.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
