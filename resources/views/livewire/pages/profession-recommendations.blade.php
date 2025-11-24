<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        @if ($testSession && count($recommendations) > 0)
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Рекомендованные профессии</h1>
                    <p class="text-lg text-gray-600 mb-2">На основе ваших результатов теста талантов</p>
                    <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Сессия: {{ substr($testSessionId, 0, 8) }}...
                        </div>
                        @if($testSession->completed_at)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $testSession->completed_at->format('d.m.Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recommendations by Spheres -->
            @foreach($groupedRecommendations as $sphereName => $sphereData)
                <!-- Sphere Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        @if($sphereData['sphere'])
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                                 style="background-color: {{ $sphereData['sphere']['color'] }}20; border: 2px solid {{ $sphereData['sphere']['color'] }}">
                                @if($sphereData['sphere']['icon'])
                                    <svg class="w-6 h-6" style="color: {{ $sphereData['sphere']['color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                                    </svg>
                                @else
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $sphereData['sphere']['color'] }}"></div>
                                @endif
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $sphereName }}</h2>
                            <p class="text-gray-600">{{ count($sphereData['professions']) }} профессий найдено</p>
                        </div>
                    </div>
                    
                    <!-- Professions Grid for this Sphere -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($sphereData['professions'] as $index => $recommendation)
                            @php
                                $profession = $recommendation['profession'];
                                $score = $recommendation['compatibility_score'];
                                $topTalents = $recommendation['top_matching_talents'];
                                
                                // Color coding based on compatibility score
                                if ($score >= 80) {
                                    $cardClass = 'border-green-500 bg-green-50';
                                    $badgeClass = 'bg-green-500 text-white';
                                    $iconColor = 'text-green-600';
                                } elseif ($score >= 70) {
                                    $cardClass = 'border-blue-500 bg-blue-50';
                                    $badgeClass = 'bg-blue-500 text-white';
                                    $iconColor = 'text-blue-600';
                                } elseif ($score >= 60) {
                                    $cardClass = 'border-yellow-500 bg-yellow-50';
                                    $badgeClass = 'bg-yellow-500 text-white';
                                    $iconColor = 'text-yellow-600';
                                } else {
                                    $cardClass = 'border-gray-300 bg-white';
                                    $badgeClass = 'bg-gray-500 text-white';
                            $iconColor = 'text-gray-600';
                        }
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-lg border-2 {{ $cardClass }} p-6 hover:shadow-xl transition-shadow duration-300">
                        <!-- Rank Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $badgeClass }} text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <div class="text-right">
                                    <div class="text-2xl font-bold {{ $iconColor }}">{{ $score }}%</div>
                                    <div class="text-xs text-gray-500">совместимость</div>
                                </div>
                            </div>
                            
                            <!-- Profession Icon -->
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center {{ $iconColor }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Profession Info -->
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $profession->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-3">{{ $profession->description }}</p>
                            
                            <!-- Talent Match Info -->
                            @if(isset($recommendation['matched_talents_count']))
                                <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $recommendation['matched_talents_count'] }}/{{ $recommendation['total_talents_count'] }} талантов совпадают
                                </div>
                            @endif
                        </div>

                        <!-- Top Matching Talents -->
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Ваши сильные стороны:</h4>
                            <div class="space-y-1">
                                @foreach($topTalents as $talentData)
                                    @php
                                        $talent = $talentData['talent'];
                                        $userScore = $talentData['user_score'];
                                        $coefficient = $talentData['coefficient'];
                                        $matchingScore = $talentData['matching_score'];
                                    @endphp
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-medium text-gray-700">{{ $talent->name }}</span>
                                        <div class="flex items-center gap-1">
                                            <span class="text-gray-500">{{ $userScore }} очков</span>
                                            <div class="w-12 bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(($userScore / 20) * 100, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>Соответствие профессии</span>
                                <span>{{ $score }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full bg-blue-600" style="width: {{ $score }}%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button class="flex-1 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                Подробнее
                            </button>
                            @auth
                                @php
                                    $user = Auth::user();
                                    $favoriteProfessions = $user->favorite_professions ?? [];
                                    $isInFavorites = in_array($profession->id, $favoriteProfessions);
                                @endphp
                                @if($isInFavorites)
                                    <button 
                                        onclick="removeProfessionFromFavorites({{ $profession->id }})"
                                        class="py-2 px-3 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors duration-200"
                                        title="Удалить из избранного">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                        </svg>
                                    </button>
                                @else
                                    <button 
                                        onclick="addProfessionToFavorites({{ $profession->id }})"
                                        class="py-2 px-3 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-200"
                                        title="Добавить в избранное">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Bottom Actions -->
            <div class="mt-8 text-center">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Что дальше?</h3>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('talent-test-results', ['sessionId' => $testSessionId]) }}" 
                           class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Посмотреть результаты теста
                        </a>
                        <button class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Скачать отчет PDF
                        </button>
                    </div>
                </div>
            </div>

        @else
            <!-- No Data State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                @if($testSession && $testSession->payment_status !== 'completed')
                    <!-- Payment Required -->
                    <div class="w-24 h-24 mx-auto mb-6 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Требуется премиум подписка</h2>
                    <p class="text-gray-600 mb-8">Рекомендации профессий доступны только для пользователей с премиум или профессиональным планом.</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('payment', ['sessionId' => $testSession->session_id]) }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Обновить до премиум
                        </a>
                        <a href="{{ route('talent-test-results', ['sessionId' => $testSession->session_id]) }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Посмотреть результаты теста
                        </a>
                    </div>
                @elseif($testSession && in_array($testSession->selected_plan, ['premium', 'professional']) && $testSession->payment_status === 'completed' && count($recommendations) == 0)
                    <!-- No Recommendations Generated -->
                    <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Не удалось сгенерировать рекомендации</h2>
                    <p class="text-gray-600 mb-8">Возникла проблема при создании рекомендаций профессий. Попробуйте обновить страницу или обратитесь в поддержку.</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <button onclick="location.reload()" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Обновить страницу
                        </button>
                        <a href="{{ route('talent-test-results', ['sessionId' => $testSession->session_id]) }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Результаты теста
                        </a>
                    </div>
                @else
                    <!-- No Test Results -->
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Нет результатов теста</h2>
                    <p class="text-gray-600 mb-8">Чтобы получить рекомендации профессий, сначала пройдите тест талантов.</p>
                    <a href="{{ route('test') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Пройти тест талантов
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
function addProfessionToFavorites(professionId) {
    fetch('/profession/add-to-favorites', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            profession_id: professionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to update the UI
            location.reload();
        } else {
            alert('Ошибка при добавлении профессии в избранное');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка при добавлении профессии в избранное');
    });
}

function removeProfessionFromFavorites(professionId) {
    fetch('/profession/remove-from-favorites', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            profession_id: professionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to update the UI
            location.reload();
        } else {
            alert('Ошибка при удалении профессии из избранного');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка при удалении профессии из избранного');
    });
}
</script>
