<x-admin-layout>
    <x-slot name="title">Editar Plano</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-white inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para lista
            </a>
        </div>

        <div class="bg-gray-800 rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-6">Editar Plano</h2>

            <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Nome *</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $plan->name) }}"
                        required
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Slug</label>
                    <input 
                        type="text" 
                        name="slug" 
                        value="{{ old('slug', $plan->slug) }}"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Descrição</label>
                    <textarea 
                        name="description" 
                        rows="2"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >{{ old('description', $plan->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Preço (R$) *</label>
                        <input 
                            type="number" 
                            name="price" 
                            value="{{ old('price', $plan->price) }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        >
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Duração (dias) *</label>
                        <input 
                            type="number" 
                            name="duration_days" 
                            value="{{ old('duration_days', $plan->duration_days) }}"
                            min="1"
                            max="365"
                            required
                            class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                        >
                        @error('duration_days')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-medium mb-2">Benefícios (um por linha)</label>
                    <textarea 
                        name="features" 
                        rows="5"
                        class="w-full bg-gray-700 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500"
                    >{{ old('features', is_array($plan->features) ? implode("\n", $plan->features) : '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="w-5 h-5 bg-gray-700 border-gray-600 rounded text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-gray-300">Ativo</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }} class="w-5 h-5 bg-gray-700 border-gray-600 rounded text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-gray-300">Destacar (Mais Popular)</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Salvar Alterações
                    </button>
                    <a href="{{ route('admin.plans.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
