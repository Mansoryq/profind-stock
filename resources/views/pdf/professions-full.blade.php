<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Полный отчет по профессиям</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* PDF page break optimization */
        .profession-item {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-bottom: 4px;
        }
        .section-header {
            page-break-after: avoid;
            break-after: avoid;
        }
        .profession-section {
            page-break-before: auto;
            break-before: auto;
            margin-top: 20px;
        }
        .profession-name {
            white-space: normal !important;
            word-wrap: break-word;
            line-height: 1.3;
        }
        .top-professions-section {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .warning-section {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        @media print {
            .hidden {
                display: block !important;
            }
            .truncate {
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

<header class="bg-white fixed top-0 left-0 right-0">
    <div class="flex w-full justify-between items-center pb-2">

        <h1>PROFGID</h1>

        <h1><b>{{ auth()->user()->name }}</b> | {{ $testDate }}</h1>

    </div>

    <hr>
</header>

    <div class="mx-auto p-4 mt-20">
        <div id="professions-section">
            @php
                $topThirtyProfessions = collect($topProfessions)->take(30)->toArray();
                $nextTenProfessions = collect($topProfessions)->take(-10)->toArray();

                // Фиолетовый цвет для профессий
                $professionColor = '#8B5CF6';
            @endphp

            <div class="grid grid-cols-2 gap-6">
            <!-- Топ 30 профессий на полную ширину -->
            @if (count($topThirtyProfessions) > 0)
                <div class="mb-8 top-professions-section">
                    <div class="flex flex-col items-start">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('all.test_result.professions.recommendation') }}</h3>
                        <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                            {{ __('all.result.professions.desc') }}
                        </p>
                    </div>
                    <!-- Desktop Table View -->
                    <div class="space-y-2 mt-2">
                        @foreach ($topThirtyProfessions as $index => $profession)
                            <div class="bg-white px-4 py-2 transition-all border border-gray-200 rounded-lg profession-item">
                                <div class="flex items-center justify-between">
                                    <div class="flex align-middle space-x-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $index + 1 }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $profession['name'] }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-12 bg-gray-200 rounded-full h-1">
                                            <div class="h-1 rounded-full transition-all duration-500 bg-blue-500"
                                                 style="width: {{ round($profession['compatibility_percentage']) }}%"></div>
                                        </div>
                                        <span class="text-xs text-blue-600 min-w-[30px]">{{ round($profession['compatibility_percentage']) }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Следующие 10 профессий (31-40) - отдельный блок с предупреждением -->
            @if (count($nextTenProfessions) > 0)
                <div class="warning-section">
                    <div class="flex flex-col items-start">
                        <h3 class="text-lg font-semibold text-black">{{ __('all.result.professions.least.title') }}</h3>
                        <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                            {{ __('all.result.professions.least.desc') }}
                        </p>
                    </div>

                    <div class="mt-2 space-y-2">

                        @php
                            // Устанавливаем индекс для нумерации профессий
                            $professionIndex = 0; // Начинаем с 31, так как первые 30 уже отображены
                        @endphp

                        @foreach ($nextTenProfessions as $index => $profession)
                            <div class="bg-white border border-red-200 p-2 rounded-lg opacity-60 profession-item">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-red-600">{{ $professionIndex = $professionIndex + 1 }}</span>
                                            <h4 class="text-sm text-gray-900">{{ $profession['name'] }}</h4>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Прогресс-бар -->
                                        <div class="flex flex-1 min-w-[60px]">
                                            <div class="w-full bg-gray-200 rounded-full h-1">
                                                <div class="h-1 rounded-full transition-all duration-500 bg-red-500"
                                                    style="width: {{ round($profession['compatibility_percentage']) }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Процент совместимости -->
                                        <span class="text-xs px-1.5 py-0.5 text-red-600 rounded">
                                            {{ round($profession['compatibility_percentage']) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <footer class="bg-white mt-8 pt-4 border-t fixed bottom-0 left-0 right-0">
        <div class="flex justify-between items-center px-4 py-2">
            <p class="text-xs text-gray-600">© 2025 Profgid</p>
            <p class="text-xs text-gray-600">Все права защищены</p>
        </div>
    </footer>

</body>
</html>
