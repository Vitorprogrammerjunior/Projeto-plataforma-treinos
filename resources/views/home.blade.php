<x-app-layout>
    <x-slot name="title">Adri Treinos - Transforme seu corpo em casa</x-slot>

    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <!-- Red Accent Line -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-700 via-red-600 to-red-700"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center bg-red-600/20 border border-red-600/30 rounded-full px-4 py-2 mb-8">
                <span class="text-red-500 text-sm font-semibold">🔥 +{{ $stats['students'] ?? 500 }} alunas transformadas</span>
            </div>

            <!-- Main Headline -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-6">
                Transforme seu corpo<br>
                <span class="text-red-600">em casa</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-xl sm:text-2xl text-gray-300 max-w-3xl mx-auto mb-10">
                Treinos de <span class="text-red-500 font-semibold">15 a 45 minutos</span> sem equipamentos. 
                Resultados reais com acompanhamento profissional.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="#planos" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-10 rounded-xl text-lg transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-600/30">
                    QUERO COMEÇAR AGORA
                </a>
                <a href="#preview" class="w-full sm:w-auto bg-transparent border-2 border-gray-600 hover:border-white text-white font-bold py-4 px-10 rounded-xl text-lg transition-all">
                    Ver vídeos grátis
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-white">{{ $stats['videos'] ?? 50 }}+</div>
                    <div class="text-gray-400 text-sm">Treinos</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-white">15-45</div>
                    <div class="text-gray-400 text-sm">Minutos</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-white">0</div>
                    <div class="text-gray-400 text-sm">Equipamentos</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- BENEFÍCIOS -->
    <section class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-white text-center mb-16">
                Por que escolher o <span class="text-red-600">Adri Treinos</span>?
            </h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-gray-900 rounded-2xl p-8 text-center hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Treinos Rápidos</h3>
                    <p class="text-gray-400">De 15 a 45 minutos. Encaixe na sua rotina sem desculpas.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-gray-900 rounded-2xl p-8 text-center hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Treino em Casa</h3>
                    <p class="text-gray-400">Sem academia, sem equipamentos. Só você e sua determinação.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-gray-900 rounded-2xl p-8 text-center hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Resultados Reais</h3>
                    <p class="text-gray-400">Metodologia comprovada por centenas de alunas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PREVIEW DE VÍDEOS GRÁTIS -->
    @if($freeVideos->count() > 0)
    <section id="preview" class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-white text-center mb-4">
                Experimente <span class="text-red-600">grátis</span>
            </h2>
            <p class="text-gray-400 text-center mb-12 max-w-2xl mx-auto">
                Assista alguns treinos de graça e veja a qualidade do conteúdo
            </p>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($freeVideos as $video)
                <div class="bg-gray-800 rounded-xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 group">
                    <div class="relative aspect-video bg-gray-700">
                        @if($video->thumbnail)
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                        <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">GRÁTIS</span>
                        <span class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">{{ $video->formatted_duration }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-white font-bold mb-2">{{ $video->title }}</h3>
                        <p class="text-gray-400 text-sm line-clamp-2">{{ $video->description }}</p>
                        @auth
                            <a href="{{ route('videos.watch', $video) }}" class="mt-4 block w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-2 rounded-lg transition">
                                Assistir
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="mt-4 block w-full bg-red-600 hover:bg-red-700 text-white text-center font-bold py-2 rounded-lg transition">
                                Criar conta para assistir
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- PLANOS -->
    <section id="planos" class="py-20 bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-white text-center mb-4">
                Escolha seu <span class="text-red-600">plano</span>
            </h2>
            <p class="text-gray-400 text-center mb-12 max-w-2xl mx-auto">
                Invista em você. Escolha o plano ideal para sua jornada de transformação.
            </p>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($plans as $plan)
                <div class="relative bg-gray-900 rounded-2xl p-8 {{ $plan->is_featured ? 'ring-2 ring-red-600 transform md:scale-105' : '' }}">
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
                        <form action="{{ route('plans.checkout', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white font-bold py-4 rounded-xl transition-all">
                                Assinar Agora
                            </button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="block w-full {{ $plan->is_featured ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white font-bold py-4 rounded-xl text-center transition-all">
                            Criar Conta
                        </a>
                    @endauth
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- GARANTIA -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-r from-gray-800 to-gray-800 rounded-2xl p-8 sm:p-12 border border-gray-700">
                <div class="w-20 h-20 bg-green-600/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4">Garantia de 7 dias</h3>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Se você não gostar do conteúdo nos primeiros 7 dias, devolvemos 100% do seu dinheiro. 
                    Sem perguntas, sem burocracia.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section class="py-20 bg-gradient-to-b from-gray-800 to-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Pronta para começar sua <span class="text-red-600">transformação</span>?
            </h2>
            <p class="text-xl text-gray-400 mb-10">
                Junte-se a mais de {{ $stats['students'] ?? 500 }} mulheres que já estão transformando seus corpos
            </p>
            <a href="#planos" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-12 rounded-xl text-lg transition-all transform hover:scale-105 shadow-lg shadow-red-600/30">
                COMEÇAR AGORA
            </a>
        </div>
    </section>
</x-app-layout>
