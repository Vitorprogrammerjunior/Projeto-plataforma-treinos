<x-app-layout>
    <x-slot name="title">Planos - Adri Treinos</x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Escolha seu <span class="text-red-600">plano</span>
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Invista em você. Escolha o plano ideal para sua jornada de transformação.
                </p>
            </div>

            <!-- Planos - Grid dinâmico baseado na quantidade -->
            <div class="flex justify-center">
                <div class="grid gap-8 {{ count($plans) === 1 ? 'max-w-md' : (count($plans) === 2 ? 'md:grid-cols-2 max-w-3xl' : 'md:grid-cols-3 max-w-5xl') }}">
                    @foreach($plans as $plan)
                    <div class="relative bg-gray-800 rounded-2xl p-8 {{ $plan->is_featured ? 'ring-2 ring-red-600 transform md:scale-105' : '' }}">
                        @if($plan->is_featured)
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-red-600 text-white text-sm font-bold px-4 py-1 rounded-full">MAIS POPULAR</span>
                            </div>
                        @endif

                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-400 text-sm">{{ $plan->description }}</p>
                        </div>

                        <div class="text-center mb-8">
                            <span class="text-5xl font-extrabold text-white">{{ $plan->formatted_price }}</span>
                            <span class="text-gray-400">/{{ $plan->duration_days }} dias</span>
                        </div>

                        <ul class="space-y-4 mb-8">
                            @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-center text-gray-300">
                                <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>

                        @auth
                            @if(auth()->user()->hasActiveAccess())
                                <button disabled class="w-full bg-gray-600 text-gray-400 font-bold py-4 rounded-xl cursor-not-allowed">
                                    Você já tem acesso ativo
                                </button>
                            @else
                                <form action="{{ route('plans.checkout', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white font-bold py-4 rounded-xl transition-all">
                                        Assinar Agora
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="block w-full {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white font-bold py-4 rounded-xl text-center transition-all">
                                Criar Conta
                            </a>
                        @endauth
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Garantia -->
            <div class="mt-16 text-center">
                <div class="inline-flex items-center bg-gray-800 rounded-lg px-6 py-4">
                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-gray-300">Garantia de 7 dias ou seu dinheiro de volta</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
