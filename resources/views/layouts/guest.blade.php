<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Adri Treinos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-900 via-gray-900 to-black">
            <!-- Logo -->
            <div class="mb-6">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-14 h-14 bg-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white">ADRI<span class="text-red-500">TREINOS</span></span>
                </a>
            </div>

            <!-- Card -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-gray-800 shadow-2xl overflow-hidden rounded-2xl border border-gray-700">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-gray-500 text-sm">
                &copy; {{ date('Y') }} Adri Treinos. Todos os direitos reservados.
            </p>
        </div>
    </body>
</html>
