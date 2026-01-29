<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Crie sua conta</h2>
        <p class="text-gray-400 mt-1">Comece sua transformação hoje</p>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg">
            <p class="text-red-400 text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Seleção de Plano -->
        @if(isset($plans) && $plans->count() > 0)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-3">Escolha seu plano</label>
                <div class="space-y-3">
                    @foreach($plans as $plan)
                        <label class="block cursor-pointer">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" 
                                class="sr-only peer" 
                                {{ old('plan_id', request('plan')) == $plan->id ? 'checked' : ($loop->first ? 'checked' : '') }}>
                            <div class="p-4 border-2 border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-gray-500 transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-white">{{ $plan->name }}</h3>
                                        <p class="text-sm text-gray-400">{{ $plan->type_label }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-red-500">R$ {{ number_format($plan->price, 2, ',', '.') }}</span>
                                        @if($plan->isMonthly())
                                            <span class="text-gray-400 text-sm">/mês</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('plan_id')" class="mt-2" />
            </div>
        @elseif(isset($plan))
            <!-- Plano pré-selecionado -->
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <div class="mb-6 p-4 bg-gray-700/50 border border-gray-600 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-400 text-sm">Plano selecionado:</span>
                        <h3 class="font-semibold text-white">{{ $plan->name }}</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-red-500">R$ {{ number_format($plan->price, 2, ',', '.') }}</span>
                        @if($plan->isMonthly())
                            <span class="text-gray-400 text-sm">/mês</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Nome completo</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="Seu nome">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-300">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="seu@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (opcional) -->
        <div class="mt-4">
            <label for="phone" class="block text-sm font-medium text-gray-300">Telefone <span class="text-gray-500">(opcional)</span></label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="(00) 00000-0000">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="Mínimo 8 caracteres">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmar senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="Repita a senha">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02]">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Continuar para Pagamento
                </span>
            </button>
        </div>

        <p class="mt-4 text-xs text-gray-500 text-center">
            Ao continuar, você será redirecionado para a página de pagamento seguro.
            Sua conta será criada após a confirmação do pagamento.
        </p>

        <div class="mt-6 text-center">
            <p class="text-gray-400">
                Já tem uma conta? 
                <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 font-medium transition">
                    Faça login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
