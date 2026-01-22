<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Adri Treinos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Styles -->
        <style>
            :root {
                --color-primary: #dc2626;
                --color-primary-dark: #b91c1c;
                --color-dark: #1f2937;
                --color-darker: #111827;
            }
            .bg-primary { background-color: var(--color-primary); }
            .bg-primary-dark { background-color: var(--color-primary-dark); }
            .text-primary { color: var(--color-primary); }
            .border-primary { border-color: var(--color-primary); }
            .btn-primary {
                @apply bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg;
            }
            .btn-secondary {
                @apply bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-900">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-600 text-white px-4 py-3 text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-600 text-white px-4 py-3 text-center">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="bg-yellow-600 text-white px-4 py-3 text-center">
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-600 text-white px-4 py-3 text-center">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900 border-t border-gray-800 py-8 mt-16">
                <div class="max-w-7xl mx-auto px-4 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Adri Treinos. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
