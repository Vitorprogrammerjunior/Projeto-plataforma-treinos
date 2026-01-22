<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Bem-vindo de volta!</h2>
        <p class="text-gray-400 mt-1">Entre na sua conta para continuar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition" 
                placeholder="seu@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="w-4 h-4 rounded bg-gray-700 border-gray-600 text-red-600 focus:ring-red-500 focus:ring-offset-gray-800">
                <span class="ms-2 text-sm text-gray-400">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-red-500 hover:text-red-400 transition" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02]">
                Entrar
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-gray-400">
                Não tem uma conta? 
                <a href="{{ route('register') }}" class="text-red-500 hover:text-red-400 font-medium transition">
                    Cadastre-se grátis
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
