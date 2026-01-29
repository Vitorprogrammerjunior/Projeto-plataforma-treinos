<x-admin-layout>
    <x-slot name="title">Nova Aba</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.tabs.index') }}" class="text-gray-400 hover:text-white transition inline-flex items-center mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para Abas
        </a>
        <h2 class="text-xl font-bold text-white">Criar Nova Aba</h2>
        <p class="text-gray-400 text-sm">Adicione uma nova aba para organizar vídeos</p>
    </div>

    <!-- Form -->
    <div class="bg-gray-800 rounded-xl p-6">
        <form action="{{ route('admin.tabs.store') }}" method="POST">
            @csrf

            <div class="grid gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nome da Aba *
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                        placeholder="Ex: Treinos para Iniciantes"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                        placeholder="Descrição opcional da aba..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ícone e Ordem -->
                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Ícone -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-300 mb-2">
                            Ícone (emoji)
                        </label>
                        <input 
                            type="text" 
                            name="icon" 
                            id="icon" 
                            value="{{ old('icon') }}"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                            placeholder="💪 🏋️ 🔥"
                            maxlength="10"
                        >
                        <p class="text-gray-500 text-xs mt-1">Use um emoji para identificar a aba</p>
                        @error('icon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ordem -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-300 mb-2">
                            Ordem de Exibição
                        </label>
                        <input 
                            type="number" 
                            name="order" 
                            id="order" 
                            value="{{ old('order', 0) }}"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                            min="0"
                        >
                        <p class="text-gray-500 text-xs mt-1">Menor número aparece primeiro</p>
                        @error('order')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Ativa -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-red-600 focus:ring-red-500 focus:ring-offset-gray-800"
                        >
                        <span class="text-gray-300">Aba ativa (visível para usuários)</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a href="{{ route('admin.tabs.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Criar Aba
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
