<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Полный отчет по талантам</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body style="font-family: sans-serif; margin: 0; padding: 0; color: #333; background-color: #fff;">

<header class="bg-white fixed top-0 left-0 right-0">
    <div class="flex w-full justify-between items-center pb-2">

        <h1>PROFGID</h1>

        <h1><b>{{ auth()->user()->name }}</b> | {{ $testDate }}</h1>

    </div>

    <hr>
</header>

<div id="talents-section">
    <!-- Domain Bar Chart -->
    <div class="mb-6 mt-20" id="talents-section-p$df">
        @php
            $domainColors = [
                'executing' => '#702B7C',
                'relationship' => '#316EC6',
                'strategic' => '#429162',
                'influencing' => '#DA782D',
                'ИСПОЛНЕНИЕ' => '#702B7C',
                'ОТНОШЕНИЯ' => '#316EC6',
                'МЫШЛЕНИЕ' => '#429162',
                'ВЛИЯНИЕ' => '#DA782D',
                'Исполнение' => '#702B7C',
                'Отношения' => '#316EC6',
                'Мышление' => '#429162',
                'Влияние' => '#DA782D',
            ];

            $domainBgColors = [
                'executing' => 'bg-[#702B7C]',
                'relationship' => 'bg-[#316EC6]',
                'strategic' => 'bg-[#429162]',
                'influencing' => 'bg-[#DA782D]',
                'ИСПОЛНЕНИЕ' => 'bg-[#702B7C]',
                'ОТНОШЕНИЯ' => 'bg-[#316EC6]',
                'МЫШЛЕНИЕ' => 'bg-[#429162]',
                'ВЛИЯНИЕ' => 'bg-[#DA782D]',
                'Исполнение' => 'bg-[#702B7C]',
                'Отношения' => 'bg-[#316EC6]',
                'Мышление' => 'bg-[#429162]',
                'Влияние' => 'bg-[#DA782D]',
            ];

            $percentages = [40, 30, 20, 10];
            $i = 0;
            $j = 0;
        @endphp

        <div>
            <h1 class="text-2xl font-medium text-gray-900 mb-4 leading-relaxed">
                {{ __('all.test_result.talents.lead') }} <span class="font-extrabold">{{ $localizedDomains[app()->getLocale()][$topDomain] }}</span>.
            </h1>
        </div>

        <!-- Single horizontal bar -->
        <div class="flex gap-1 w-full h-8 overflow-hidden mb-4 bg-gray-200 rounded">
            @foreach ($domainScores as $domain => $score)
                @php
                    $percentage = $percentages[$i] ?? 10; // default to 10 if more domains
                    $i++;
                @endphp
                <div class="{{ $domainBgColors[$domain] ?? 'bg-gray-400' }} flex items-center justify-center text-white font-bold text-xs md:text-sm flex-shrink-0"
                     style="width: {{ $percentage }}%; min-width: 10%;">

                </div>
            @endforeach
        </div>

        <!-- Domain labels with scores and percentages -->
        <div class="flex gap-1 w-full">
            @foreach ($domainScores as $domain => $score)
                @php
                    $percentage = $percentages[$j] ?? 10; // default to 10 if more domains
                    $j++;
                @endphp
                <div class="text-left flex-shrink-0" style="width: {{ $percentage }}%; min-width: 10%;">
                    <div class="text-sm font-medium text-gray-700 truncate">
                        {{ $localizedDomains[app()->getLocale()][$domain] ?? $domain }}
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <p class="text-sm text-gray-600 mb-6 md:mb-8 leading-relaxed">
        {{ $domainsDescription[app()->getLocale()][$topDomain] }}
    </p>

    <h2 class="text-xl font-bold mb-4">{{ __('all.test_result.talents.by_domains') }}</h2>

    <div class="grid grid-cols-4 gap-1 mb-8">
        @foreach ($domains as $domain => $name)
            <div class="mb-0">
                <div class="text-center font-semibold uppercase text-sm mb-2 md:mb-3 pb-1 md:pb-2 text-gray-800 p-1 md:p-2 border-b-4 md:border-b-8"
                    style="border-color: {{ $domainColors[$domain] }}">
                    {{ $localizedDomains[app()->getLocale()][$domain] }}
                </div>

                @php
                    $domainTalents = array_filter($userResultsCopy, function ($talent) use ($domain) {
                        return $talent['domain'] === $domain;
                    });

                    $topTalentsByScore = collect($userResultsCopy)->sortByDesc('score')->take(10);
                    $topTalentIds = $topTalentsByScore->pluck('id')->toArray();
                @endphp

                @php
                    $mutedBgColors = [
                        'executing' => 'bg-[#E9DCEB]',
                        'influencing' => 'bg-[#FDEAD9]',
                        'relationship' => 'bg-[#E1E8F6]',
                        'strategic' => 'bg-[#D8EEE4]',
                    ];
                @endphp

                <div class="grid grid-cols-2 gap-1">
                    @foreach ($domainTalents as $talent)
                        @php
                            $bgColor = in_array($talent['id'], $topTalentIds)
                                ? $domainBgColors[$domain] ?? 'bg-gray-400'
                                : $mutedBgColors[$domain] ?? 'bg-gray-200';
                            $textColor = in_array($talent['id'], $topTalentIds)
                                ? 'text-white'
                                : 'text-black';
                        @endphp
                        <div
                            class="{{ $bgColor }} {{ $textColor }} text-center aspect-square flex flex-col items-center justify-center p-1">
                            <div class="text-lg md:text-xl font-bold">{{ $talent['rank'] }}</div>
                            <div class="text-xs md:text-xs px-1 text-center leading-tight mt-1">{{ $talent['name'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="space-y-5">
        <h2 class="text-lg md:text-xl font-bold mb-3 md:mb-4">{{ __('all.test_result.talents.desc_title') }}</h2>

        <span class="block">
            {{ __('all.test_result.talents.desc_1') }}
        </span>

        <span class="block">
            {{ __('all.test_result.talents.desc_2') }}
        </span>

        <span class="block">
            {{ __('all.test_result.talents.desc_3') }}
        </span>
    </div>

    <div style="page-break-before: always;"></div>

    <!-- Таланты блок -->
    <div class="mt-20 space-y-6" x-data="{ expandedTalents: [] }">
        <h2 class="text-lg md:text-xl font-bold text-center">{{ __('all.test_result.talents.content') }}</h2>

        @php
            $topTenTalents = collect($userResults)->take(10)->toArray();
            $remainingTalents = collect($userResults)->skip(10)->toArray();
        @endphp

        <div class="grid grid-cols-2 gap-6">
            <!-- Левая колонка - Топ 10 талантов -->
            <div>
                <div class="space-y-1">
                    @foreach ($topTenTalents as $index => $talent)
                        @php
                            // Получаем цвет домена для таланта
                            $talentDomainColor = $domainColors[$talent['domain']] ?? '#6B7280';
                            $talentId = 'talent_' . $talent['id'];
                        @endphp

                        <!-- Все топ 10 талантов с аккордеоном -->
                        <div class="border-gray-200 p-1.5 transition-all hover:bg-blue-100 bg-blue-50"
                            style="border-left: 4px solid {{ $talentDomainColor }}">
                            <!-- Заголовок таланта -->
                            <div class="flex items-center justify-between cursor-pointer">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2"
                                        style="background-color: {{ $talentDomainColor }}">
                                        {{ $talent['rank'] }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xs font-bold text-gray-900 truncate">{{ $talent['name'] }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Аккордеон для обзора и советов -->
                            <div class="overflow-hidden mt-2"
                                x-show="expandedTalents.includes('{{ $talentId }}')"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-96"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 max-h-96"
                                x-transition:leave-end="opacity-0 max-h-0">

                                <!-- Краткое описание - показывается в аккордеоне -->
                                @if (!empty($talent['short_description']))
                                    <div class="mb-1">
                                        <p class="text-xs text-gray-700 leading-tight">{{ $talent['short_description'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Правая колонка - Остальные таланты -->
            <div>
                <div class="space-y-1">
                    @foreach ($remainingTalents as $talent)
                        @php
                            // Получаем цвет домена для таланта
                            $talentDomainColor = $domainColors[$talent['domain']] ?? '#6B7280';
                            $remainingTalentId = 'remaining_talent_' . $talent['id'];
                        @endphp

                        <!-- Остальные таланты - с аккордеоном для краткого описания -->
                        <div class="bg-gray-50 p-1.5 transition-all hover:bg-gray-100"
                            style="border-left: 4px solid {{ $talentDomainColor }}">

                            <!-- Заголовок таланта с аккордеоном -->
                            <div class="flex items-center justify-between cursor-pointer"
                                @click="expandedTalents.includes('{{ $remainingTalentId }}') ? expandedTalents.splice(expandedTalents.indexOf('{{ $remainingTalentId }}'), 1) : expandedTalents.push('{{ $remainingTalentId }}')">
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2"
                                        style="background-color: {{ $talentDomainColor }}">
                                        {{ $talent['rank'] }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xs font-bold text-gray-900 truncate">{{ $talent['name'] }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Аккордеон для краткого описания -->
                            @if (!empty($talent['short_description']))
                                <div class="overflow-hidden mt-2"
                                    x-show="expandedTalents.includes('{{ $remainingTalentId }}')"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 max-h-0"
                                    x-transition:enter-end="opacity-100 max-h-96"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 max-h-96"
                                    x-transition:leave-end="opacity-0 max-h-0">

                                    <!-- Краткое описание для остальных талантов -->
                                    <div class="mb-1">
                                        <p class="text-xs text-gray-700 leading-tight">{{ $talent['short_description'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Важно блок -->
    <div class="mt-8 text-left">
        <p class="text-sm text-gray-600">
            <span class="font-bold">{{ __('all.test_result.talents.attention') }}</span><br>
            {{ __('all.test_result.talents.attention_desc') }}
        </p>
    </div>

    @if($plan === 'talents_spheres_professions')
        <!-- Разрыв страницы перед новым разделом -->
        <div style="page-break-before: always;"></div>

        <!-- Топ 5 талантов раздел -->
        <div class="mt-20 space-y-6">
            @php
                // Получаем топ 5 талантов с наивысшими баллами
                $top5Talents = collect($userResults)->sortByDesc('score')->take(5)->values();
                $maxScore = collect($userResults)->max('score');
                $maxScore = $maxScore > 0 ? $maxScore : 1;
            @endphp

            <!-- Краткое описание -->
            <div class="mb-6">
                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ __('all.test_pdf.title') }}
                </p>
            </div>

            <!-- Обзорный текст -->
            <div class="mb-8">
                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ __('all.test_pdf.desc') }}
                </p>
            </div>

            <!-- Отдельная страница для каждого из топ 5 талантов -->
            @foreach($top5Talents as $index => $talent)
                @php
                    $talentDomainColor = $domainColors[$talent['domain']] ?? '#6B7280';
                    $position = $index + 1;
                @endphp

                @if($index > 0)
                    <!-- Разрыв страницы между талантами -->
                    <div style="page-break-before: always;"></div>
                @endif

                <!-- Страница для конкретного таланта -->
                <div class="mt-20 space-y-8">
                    <!-- Заголовок с номером и названием таланта -->
                    <div class="flex items-center space-x-4 mb-8">
                        <!-- Квадрат с номером в цвете домена -->
                        <div class="w-16 h-16 flex items-center justify-center text-white font-bold text-2xl" style="background-color: {{ $talentDomainColor }}">
                            {{ $position }}
                        </div>

                        <!-- Название и балл таланта -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ __('all.test_pdf.talents.review') }} {{ $talent['name'] }}
                            </h1>
                            <div class="flex items-center space-x-4">
                                <div class="text-sm text-gray-600 px-3 py-1 bg-gray-100 rounded">
                                    {{ __('all.test_pdf.talents.domain') }} {{ $localizedDomains[app()->getLocale()][$talent['domain']] ?? $talent['domain'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Краткое описание -->
                    @if(!empty($talent['short_description']))
                        <div class="bg-blue-50 p-6 border-l-4" style="border-left-color: {{ $talentDomainColor }}">
                            <h3 class="text-lg font-semibold mb-3 text-gray-900">{{ __('all.test_pdf.talents.short') }}</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $talent['short_description'] }}
                            </p>
                        </div>
                    @endif

                    <!-- Обзор таланта -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">{{ __('all.test_pdf.talents.review_short') }}</h3>
                        <div class="space-y-3 text-sm text-gray-700 leading-relaxed">
                            @if(!empty($talent['description']))
                                <p>{{ $talent['description'] }}</p>
                            @else
                                <!-- Обобщенный текст для обзора -->
                                <p>Талант "{{ $talent['name'] }}" представляет собой одну из ваших ключевых сильных сторон, которая проявляется в вашем естественном стремлении к определенным видам деятельности и способах взаимодействия с окружающим миром.</p>

                                <p>Этот талант влияет на то, как вы подходите к решению задач, взаимодействуете с людьми и достигаете своих целей. Понимание и развитие этого таланта поможет вам максимально эффективно использовать свои природные способности.</p>

                                <p>Люди с выраженным талантом "{{ $talent['name'] }}" часто демонстрируют высокие результаты в областях, где этот талант может быть полностью реализован. Важно создавать условия и выбирать деятельность, которая позволяет этому таланту проявиться наиболее полно.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Советы для развития таланта -->
                    <div class="bg-white border border-gray-200 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">{{ __('all.test_pdf.talents.advice') }} "{{ $talent['name'] }}"</h3>
                        <div class="space-y-4">
                            @if(isset($talentAdvice[$talent['name']]) && !empty($talentAdvice[$talent['name']]))
                                @php
                                    // Проверяем тип данных advice
                                    $adviceData = $talentAdvice[$talent['name']];

                                    // Если это строка, пытаемся декодировать JSON
                                    if (is_string($adviceData)) {
                                        $decodedData = json_decode($adviceData, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedData)) {
                                            $adviceData = $decodedData;
                                        }
                                    }
                                @endphp

                                @if(is_array($adviceData))
                                    {{-- Если advice - это JSON массив с объектами --}}
                                    @foreach($adviceData as $tipIndex => $adviceItem)
                                        <div class="flex items-start space-x-3">
                                            <div class="w-6 h-6 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5" style="background-color: {{ $talentDomainColor }}">
                                                {{ $tipIndex + 1 }}
                                            </div>
                                            <div class="flex-1">
                                                @if(is_array($adviceItem) && isset($adviceItem['name']))
                                                    @if(app()->getLocale() === 'ru')
                                                        <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $adviceItem['name'] }}</h4>
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $adviceItem['description'] ?? '' }}</p>
                                                    @else
                                                        <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $adviceItem['name_kz'] }}</h4>
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $adviceItem['description_kz'] ?? '' }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-sm text-gray-700 leading-relaxed">{{ is_string($adviceItem) ? $adviceItem : '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Если advice - это обычная строка или HTML --}}
                                    <div class="text-sm text-gray-700 leading-relaxed">
                                        {!! is_string($adviceData) ? $adviceData : '' !!}
                                    </div>
                                @endif
                            @else
                                {{-- Fallback советы, если нет данных в базе --}}
                                @php
                                    $defaultTips = [
                                        "Определите конкретные области, где ваш талант \"" . $talent['name'] . "\" может принести максимальную пользу, и сосредоточьтесь на их развитии.",
                                        "Ищите возможности для практического применения этого таланта в повседневной работе и личной жизни.",
                                        "Изучайте успешных людей, которые эффективно используют схожие таланты, и адаптируйте их подходы под свои потребности.",
                                        "Создавайте среду и условия, которые способствуют проявлению и развитию вашего таланта \"" . $talent['name'] . "\".",
                                        "Регулярно оценивайте прогресс в развитии этого таланта и корректируйте свои подходы для достижения лучших результатов."
                                    ];
                                @endphp

                                @foreach($defaultTips as $tipIndex => $tip)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-6 h-6 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 mt-0.5" style="background-color: {{ $talentDomainColor }}">
                                            {{ $tipIndex + 1 }}
                                        </div>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $tip }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<footer class="bg-white fixed bottom-0 left-0 right-0">
    <div class="flex justify-between items-center px-4 py-2">
        <p class="text-xs text-gray-600">© 2025 Profgid</p>
        <p class="text-xs text-gray-600">Все права защищены</p>
    </div>
</footer>
</body>
</html>
