<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Карта профессий</h1>
                <p class="text-lg text-gray-600">Исследуйте различные сферы деятельности и найдите свой карьерный путь</p>
            </div>
        </div>

        <!-- Spheres Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($spheres as $sphere)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <!-- Sphere Header with Color -->
                    <div class="h-2" style="background-color: {{ $sphere->color }}"></div>
                    
                    <div class="p-6">
                        <!-- Icon and Title -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                                 style="background-color: {{ $sphere->color }}20; border: 2px solid {{ $sphere->color }}">
                                @if($sphere->icon)
                                    <svg class="w-6 h-6" style="color: {{ $sphere->color }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                                    </svg>
                                @else
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $sphere->color }}"></div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $sphere->localized_name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-gray-500">{{ $sphere->professions_count }} профессий</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white"
                                          style="background-color: {{ $sphere->color }}">
                                        Активно
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($sphere->localized_description)
                            <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                {{ Str::limit($sphere->localized_description, 120) }}
                            </p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button class="flex-1 py-2 px-4 text-white font-medium rounded-lg transition-colors duration-200"
                                    style="background-color: {{ $sphere->color }}">
                                Смотреть профессии
                            </button>
                            <button class="py-2 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200"
                                    title="Добавить в избранное">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Call to Action -->
        @guest
            <div class="mt-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-4">Готовы найти свою идеальную профессию?</h2>
                <p class="text-lg mb-6 opacity-90">Пройдите тест талантов и получите персональные рекомендации</p>
                <a href="{{ route('test') }}" 
                   class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Пройти тест талантов
                </a>
            </div>
        @endguest

        @auth
            <div class="mt-12 bg-white rounded-xl shadow-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Изучите больше возможностей</h2>
                <p class="text-gray-600 mb-6">Пройдите тест еще раз или изучите свои результаты</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('test') }}" 
                       class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Пройти тест снова
                    </a>
                    <a href="{{ route('test.history') }}" 
                       class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        История тестов
                    </a>
                    <a href="{{ route('my-professions') }}" 
                       class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Мои профессии
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>
