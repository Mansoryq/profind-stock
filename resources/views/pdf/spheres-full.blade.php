<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Полный отчет по сферам</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>

    </style>
</head>
<body>
<header class="bg-white fixed top-0 left-0 right-0">
    <div class="flex w-full justify-between items-center pb-2">

        <h1>PROFGID</h1>

        <h1><b>{{ auth()->user()->name }}</b> | {{ $testDate }}</h1>

    </div>

    <hr>
</header>

<div class="mt-20">
    <div id="spheres-section">
        <h2 class="text-lg md:text-xl font-bold mb-3 md:mb-4">{{ __('all.test_pdf.spheres.title') }}</h2>
        <p class="text-xs md:text-sm text-gray-600 mb-6 leading-relaxed">
            {{ __('all.test_pdf.spheres.desc') }}
        </p>

        @php
            $isFullPlan = $isFullPlan ?? true; // Default to true for PDF view
            $topTenSpheres = collect($topSpheres)->take(10)->toArray();
            $remainingSpheres = collect($topSpheres)->skip(10)->toArray();
        @endphp

        <div class="grid grid-cols-2 gap-6 sphere-grid">
            <!-- Левая колонка - Топ 10 сфер -->
            <div class="sphere-column">
                <div class="space-y-1">
                    @php
                        // Используем синий цвет для всех сфер
                        $topTenSpheresIndex = 0;
                    @endphp
                    @foreach ($topTenSpheres as $index => $sphere)
                        @php
                            // Используем синий цвет для всех сфер
                            $sphereColor = '#316EC6';
                        @endphp

                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 md:space-x-3">
                                        <span class="text-xs md:text-sm font-medium text-gray-600"
                                            style="color: {{ $sphereColor }}">{{ $topTenSpheresIndex = $topTenSpheresIndex + 1 }}</span>
                                        <h4 class="text-xs font-medium text-gray-900 break-words leading-snug sphere-name">
                                            {{ $sphere['name'] }}</h4>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    @if ($isFullPlan)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">
                                            {{ round($sphere['compatibility_percentage']) }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="sphere-column">
                <div class="space-y-1">
                    @php
                        $remainingSpheresIndex = 10;
                    @endphp
                    @foreach ($remainingSpheres as $index => $sphere)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 md:space-x-3">
                                        <span class="text-xs md:text-sm font-medium text-gray-600"
                                            style="color: {{ $sphereColor }}">{{ $remainingSpheresIndex = $remainingSpheresIndex + 1 }}</span>
                                        <h4 class="text-xs font-medium text-gray-900 break-words leading-snug sphere-name">
                                            {{ $sphere['name'] }}</h4>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    @if ($isFullPlan)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">
                                            {{ round($sphere['compatibility_percentage']) }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="page-break-before: always;"></div>

            <!-- Профессии из ваших топ-10 сфер блок -->
        <div class="space-y-4 mt-20">
            <h2 class="text-lg font-bold text-center section-header">{{ __('all.test_pdf.spheres.professions') }}</h2>
            <p class="text-xs text-gray-600 mb-4 text-center leading-relaxed">
                {{ __('all.test_pdf.spheres.professions_desc') }}
            </p>

            @php
                // Получаем топ-10 сфер пользователя и сразу ограничиваем до 10 элементов
                $topTenSpheresForTable = collect($topTenSpheres)->take(10);

                // Создаем индексированный массив профессий для быстрого поиска
                $professionsIndexedBySphereId = collect($topProfessions)
                    ->filter(function ($profession) {
                        return isset($profession['sphere_id']);
                    })
                    ->groupBy('sphere_id')
                    ->toArray();

                // Группируем профессии по сферам более эффективно
                $professionsGroupedBySphere = $topTenSpheresForTable->map(function ($sphere) use ($professionsIndexedBySphereId) {
                    return [
                        'sphere' => $sphere,
                        'professions' => $professionsIndexedBySphereId[$sphere['id']] ?? [],
                    ];
                })->toArray();
            @endphp

            <!-- Responsive Accordion Layout -->
            <div class="bg-white rounded-lg overflow-hidden table-section">
                <div class="block">
                    <div class="w-full">
                        <div class="grid grid-cols-3 gap-5">
                            @foreach (array_slice($professionsGroupedBySphere, 0, 9) as $index => $sphereData)
                                @php
                                    $sphere = $sphereData['sphere'];
                                    $professions = $sphereData['professions'];
                                    $hasProfessions = !empty($professions);
                                    $sphereId = $sphere['id'];
                                    $compatibilityPercentage = round($sphere['compatibility_percentage']);
                                @endphp

                                <div>
                                    <!-- Основная строка сферы -->
                                    <div>
                                        <div class="py-2 px-2 bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center flex-1 min-w-0 cursor-pointer">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-sm font-medium text-blue-600"
                                                            style="color: #316EC6">{{ $loop->index + 1 }}</span>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $sphere['name'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Percentage Display -->
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-600 rounded">
                                                        {{ $compatibilityPercentage }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Раскрывающийся список профессий (Desktop) -->
                                    <div>
                                        <div class="px-2 py-0">
                                            @if ($hasProfessions)
                                                <div class="grid grid-cols-1 gap-1">
                                                    @foreach ($professions as $profession)
                                                        @php
                                                            $professionCompatibility = isset($profession['compatibility_percentage'])
                                                                ? round($profession['compatibility_percentage'])
                                                                : 0;
                                                            $hasDescription = isset($profession['description']) && $profession['description'];
                                                        @endphp
                                                        <div class="bg-white pb-1 text-sm text-gray-700 transition-colors border-b border-gray-200">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center flex-1 space-x-2">
                                                                    <span class="flex-1">{{ $profession['name'] }}</span>
                                                                    @if(isset($profession['compatibility_percentage']))
                                                                        <div class="flex items-center space-x-2">
                                                                            <span class="text-xs text-gray-500 min-w-[30px]">
                                                                                {{ $professionCompatibility }}%
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
