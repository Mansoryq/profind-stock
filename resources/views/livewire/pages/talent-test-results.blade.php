<!-- HTML2Canvas CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="min-h-screen bg-gray-50 py-4 md:py-8 px-4" x-data="{
    activeTab: 'talents',
    canViewSpheresTab: 'true',
    canViewProfessionsTab: 'true',
    modalSphere: null,
    modalProfession: null,
    expandedSpheres: [],
    isExporting: false,

    setActiveTab(tab) {
        // Проверяем права доступа к вкладкам
        if (tab === 'spheres' && !this.canViewSpheresTab) {
            return; // Не переключаем вкладку если нет доступ��
        }
        if (tab === 'professions' && !this.canViewProfessionsTab) {
            return; // Не переключаем вкладку если нет доступа
        }
        this.activeTab = tab;
    },

    toggleSphere(sphereId) {
        if (this.expandedSpheres.includes(sphereId)) {
            this.expandedSpheres = this.expandedSpheres.filter(id => id !== sphereId);
        } else {
            this.expandedSpheres.push(sphereId);
        }
    },

    openSphereModal(sphere) {
        this.modalSphere = sphere;
    },

    closeSphereModal() {
        this.modalSphere = null;
    },

    openProfessionModal(profession) {
        this.modalProfession = profession;
    },

    closeProfessionModal() {
        this.modalProfession = null;
    },

    async exportSection(sectionId, filename = 'talent-results') {
        this.isExporting = true;

        // Проверяем готовность библиотек
        if (!this.checkLibraryReadiness()) {
            this.isExporting = false;
            return;
        }

        // Логируем диагностическую информацию
        console.log('Export diagnostic info:', this.getDiagnosticInfo());

        try {
            const element = document.getElementById(sectionId);
            if (!element) {
                this.showNotification('Секция для экспорта не найдена', 'error');
                return;
            }

            // Показываем уведомление о начале экспорта
            this.showNotification('Подготовка к экспорту...', 'info');

            // Ждем следующий кадр для плавности UI
            await new Promise(resolve => requestAnimationFrame(resolve));

            // Добавляем индикатор прогресса
            let progressNotification = null;
            const showProgress = (message) => {
                if (progressNotification) {
                    progressNotification.textContent = message;
                } else {
                    progressNotification = document.createElement('div');
                    progressNotification.className = 'fixed top-16 right-4 z-50 px-4 py-2 rounded-lg shadow-lg bg-blue-500 text-white';
                    progressNotification.textContent = message;
                    document.body.appendChild(progressNotification);
                }
            };

            let dataUrl;

            // Проверяем доступность библиотек
            const hasDomToImage = typeof domtoimage !== 'undefined';
            const hasHtml2Canvas = typeof html2canvas !== 'undefined';

            if (!hasDomToImage && !hasHtml2Canvas) {
                throw new Error('Библиотеки для экспорта не загружены');
            }

            // Сначала пробуем быстрый метод с dom-to-image
            if (hasDomToImage) {
                try {
                    showProgress('Экспорт (быстрый метод)...');
                    dataUrl = await Promise.race([
                        domtoimage.toJpeg(element, {
                            quality: 0.85,
                            bgcolor: '#ffffff',
                            style: {
                                transform: 'none',
                                transition: 'none',
                                animation: 'none'
                            }
                        }),
                        new Promise((_, reject) =>
                            setTimeout(() => reject(new Error('dom-to-image timeout')), 8000)
                        )
                    ]);
                } catch (domError) {
                    console.warn('dom-to-image failed:', domError);
                    if (!hasHtml2Canvas) {
                        throw domError;
                    }
                    // Продолжаем к html2canvas
                }
            }

            // Fallback к html2canvas если dom-to-image не сработал или недоступен
            if (!dataUrl && hasHtml2Canvas) {
                showProgress('Экспорт через HTML2Canvas...');

                // Подготавливаем элемент для лучшего рендеринга
                const originalStyle = {
                    position: element.style.position,
                    left: element.style.left,
                    top: element.style.top
                };

                element.style.position = 'relative';
                element.style.left = '0';
                element.style.top = '0';

                try {
                    const canvas = await Promise.race([
                        html2canvas(element, {
                            scale: 1.2,
                            useCORS: true,
                            allowTaint: true,
                            backgroundColor: '#ffffff',
                            logging: false,
                            letterRendering: true,
                            removeContainer: true,
                            imageTimeout: 5000,
                            width: element.scrollWidth,
                            height: element.scrollHeight,
                            scrollX: 0,
                            scrollY: 0,
                            windowWidth: window.innerWidth,
                            windowHeight: window.innerHeight,
                            onclone: function(clonedDoc) {
                                const clonedElement = clonedDoc.getElementById(sectionId);
                                if (clonedElement) {
                                    // Оптимизируем клонированный элемент
                                    clonedElement.style.transform = 'none';
                                    clonedElement.style.transition = 'none';
                                    clonedElement.style.animation = 'none';
                                    clonedElement.style.position = 'relative';

                                    // Улучшаем видимость текста
                                    const textElements = clonedElement.querySelectorAll('*');
                                    textElements.forEach(el => {
                                        if (el.style) {
                                            el.style.textShadow = 'none';
                                            el.style.webkitFontSmoothing = 'antialiased';
                                        }
                                    });
                                }
                            }
                        }),
                        new Promise((_, reject) =>
                            setTimeout(() => reject(new Error('HTML2Canvas timeout')), 15000)
                        )
                    ]);

                    // Восстанавливаем исходные стили
                    Object.assign(element.style, originalStyle);

                    dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                } catch (canvasError) {
                    // Восстанавливаем исходные стили в случае ошибки
                    Object.assign(element.style, originalStyle);
                    throw canvasError;
                }
            }

            if (!dataUrl) {
                throw new Error('Не удалось создать изображение');
            }

            showProgress('Подготовка к скачиванию...');

            // Создаем ссылку для скачивания
            const link = document.createElement('a');
            link.download = `${filename}-${new Date().toISOString().split('T')[0]}.jpg`;
            link.href = dataUrl;

            // Скачиваем файл
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            this.showNotification('Изображение успешно экспортировано!', 'success');

        } catch (error) {
            console.error('Ошибка при экспорте:', error);

            let errorMessage = 'Произошла ошибка при экспорте изображения';

            if (error.message.includes('timeout')) {
                errorMessage = 'Экспорт занял слишком много времени. Попробуйте снова.';
            } else if (error.message.includes('библиотеки')) {
                errorMessage = 'Библиотеки для экспорта не загружены. Обновите страницу.';
            } else if (error.message.includes('не найдена')) {
                errorMessage = 'Секция для экспорта не найдена';
            } else if (error.message.includes('создать изображение')) {
                errorMessage = 'Не удалось создать изображение. Попробуйте другой браузер.';
            }

            this.showNotification(errorMessage, 'error');
        } finally {
            // Очищаем индикатор прогресса
            if (progressNotification) {
                document.body.removeChild(progressNotification);
                progressNotification = null;
            }
            this.isExporting = false;
        }
    },

    // Проверка готовности библиотек
    checkLibraryReadiness() {
        const libraries = {
            'dom-to-image': typeof domtoimage !== 'undefined',
            'html2canvas': typeof html2canvas !== 'undefined',
            'html2pdf': typeof html2pdf !== 'undefined'
        };

        console.log('Library readiness check:', libraries);

        if (!libraries['dom-to-image'] && !libraries['html2canvas']) {
            this.showNotification('Библиотеки экспорта не загружены', 'error');
            return false;
        }

        return true;
    },

    // Диагностическая информация
    getDiagnosticInfo() {
        return {
            userAgent: navigator.userAgent,
            screenSize: `${screen.width}x${screen.height}`,
            viewportSize: `${window.innerWidth}x${window.innerHeight}`,
            devicePixelRatio: window.devicePixelRatio,
            libraries: {
                domtoimage: typeof domtoimage !== 'undefined',
                html2canvas: typeof html2canvas !== 'undefined',
                html2pdf: typeof html2pdf !== 'undefined'
            },
            performance: {
                memory: navigator.memory ? {
                    used: Math.round(navigator.memory.usedJSHeapSize / 1024 / 1024) + 'MB',
                    total: Math.round(navigator.memory.totalJSHeapSize / 1024 / 1024) + 'MB',
                    limit: Math.round(navigator.memory.jsHeapSizeLimit / 1024 / 1024) + 'MB'
                } : 'Not available'
            }
        };
    },

    showNotification(message, type = 'info') {
        // Создаем уведомление
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Удаляем уведомление через 3 секунды
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}">
    <div class="max-w-7xl mx-auto bg-white rounded-xl p-4 md:p-8 my-4 md:my-8" id="main-results-container">
        @if (count($userResults) > 0)
            <!-- Tabs Navigation -->
            <div class="mb-6 md:mb-8">
                <div class="border-b border-gray-200">
                    <div class="flex-col md:flex md:flex-row justify-between md:items-center">
                        <div class="flex md:hidden mt-2 mb-2">
                            @php
                                $canDownloadTalents = true; // Таланты доступны всегда
                                $canDownloadSpheres = $this->canViewSpheresTab;
                                $canDownloadProfessions = $this->canViewProfessionsTab;
                            @endphp

                                <!-- Проверяем доступность скачивания для текущей вкладки -->
                            <div x-data="{
                                canDownload: function() {
                                    // Проверяем тариф пользователя
                                    const userPlan = '{{ $testSession->selected_plan ?? '' }}';
                                    const isPaid = {{ $testSession && $testSession->isPaid() ? 'true' : 'false' }};

                                    // Если не оплачено, скачивание недоступно
                                    if (!isPaid) return false;

                                    // Проверяем только тариф (без привязки к табам)
                                    return ['talents', 'talents_spheres', 'talents_spheres_professions'].includes(userPlan);
                                }
                            }">
                                <!-- Кнопка скачивания (активная) -->
                                <a
                                    x-show="canDownload()"
                                    href="{{ route('talent.pdf.download', ['session_id' => $testSessionId ?? request()->get('session_id'), 'plan' => $testSession->selected_plan ?? '']) }}"
                                    class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors group"
                                    title="Скачать результаты в PDF"
                                    target="_blank"
                                >
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600 group-hover:text-gray-800" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm text-gray-600 group-hover:text-gray-800 font-medium">
                                        {{ __('all.test_result.download') }}
                                    </span>
                                </a>

                                <!-- Кнопка скачивания (неактивная) - показывается когда скачивание недоступно -->
                                <div x-show="!canDownload()" class="relative">
                                    <button
                                        class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-3 bg-gray-300 rounded-lg cursor-not-allowed opacity-60"
                                        title="Обновите тариф для скачивания этого раздела"
                                        disabled
                                    >
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-500" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        <span class="text-sm md:text-base text-gray-500 font-medium">
                                            {{ __('all.test_result.download') }}
                                        </span>
                                    </button>

                                    <!-- Подсказка о необходимости обновления тарифа -->
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                                        <span>{{ __('all.test_result.update') }}</span>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-b-gray-800"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <!-- Вкладка "Таланты" - всегда доступна -->
                            <button @click="setActiveTab('talents')"
                                :class="activeTab === 'talents' ? 'border-blue-600 text-blue-600' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="py-3 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                                {{ __('all.test_result.talents.title') }}
                            </button>

                            <!-- Вкладка "Сферы" - доступна для средний и премиум тарифов -->
                            @if ($this->canViewSpheresTab)
                                <button @click="setActiveTab('spheres')"
                                    :class="activeTab === 'spheres' ? 'border-blue-600 text-blue-600' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="py-3 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                                    {{ __('all.test_result.spheres.title') }}
                                </button>
                            @endif

                            <!-- Вкладка "Профессии" - доступна только для премиум тарифа -->
                            @if ($this->canViewProfessionsTab)
                                <button @click="setActiveTab('professions')"
                                    :class="activeTab === 'professions' ? 'border-blue-600 text-blue-600' :
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="py-3 px-1 border-b-2 font-medium text-xs md:text-sm transition-colors duration-200">
                                    {{ __('all.test_result.professions.title') }}
                                </button>
                            @endif
                        </nav>

                        <!-- Кнопка скачать рядом с табами -->
                        <div class="hidden md:flex flex-col items-center">
                            @php
                                $canDownloadTalents = true; // Таланты доступны всегда
                                $canDownloadSpheres = $this->canViewSpheresTab;
                                $canDownloadProfessions = $this->canViewProfessionsTab;
                            @endphp

                            <!-- Проверяем доступность скачивания для текущей вкладки -->
                            <div x-data="{
                                canDownload: function() {
                                    // Проверяем тариф пользователя
                                    const userPlan = '{{ $testSession->selected_plan ?? '' }}';
                                    const isPaid = {{ $testSession && $testSession->isPaid() ? 'true' : 'false' }};

                                    // Если не оплачено, скачивание недоступно
                                    if (!isPaid) return false;

                                    // Проверяем только тариф (без привязки к табам)
                                    return ['talents', 'talents_spheres', 'talents_spheres_professions'].includes(userPlan);
                                }
                            }">
                                <!-- Кнопка скачивания (активная) -->
                                <a
                                    x-show="canDownload()"
                                    href="{{ route('talent.pdf.download', ['session_id' => $testSessionId ?? request()->get('session_id'), 'plan' => $testSession->selected_plan ?? '']) }}"
                                    class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors group"
                                    title="Скачать результаты в PDF"
                                    target="_blank"
                                >
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600 group-hover:text-gray-800" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm md:text-base text-gray-600 group-hover:text-gray-800 hidden md:block font-medium">
                                        {{ __('all.test_result.download') }}
                                    </span>
                                </a>

                                <!-- Кнопка скачивания (неактивная) - показывается когда скачивание недоступно -->
                                <div x-show="!canDownload()" class="relative">
                                    <button
                                        class="flex items-center gap-2 px-3 py-2 md:px-4 md:py-3 bg-gray-300 rounded-lg cursor-not-allowed opacity-60"
                                        title="Обновите тариф для скачивания этого раздела"
                                        disabled
                                    >
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        <span class="text-sm md:text-base text-gray-500 font-medium">
                                            {{ __('all.test_result.download') }}
                                        </span>
                                    </button>

                                    <!-- Подсказка о необходимости обновления тарифа -->
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                                        <span>{{ __('all.test_result.update') }}</span>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-b-gray-800"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tab Content -->
            <div id="talents-section" x-show="activeTab === 'talents'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <!-- Domain Bar Chart -->
                <div class="mb-4 md:mb-6" id="talents-section-pdf">
                <div class="mb-4 md:mb-0">
                    @php
                        // Найдем домен с максимальным счетом
                        $topDomain = array_keys($domainScores, max($domainScores))[0] ?? 'executing';
                        $topDomainName = $localizedDomains[app()->getLocale()][$topDomain] ?? 'EXECUTING';
                    @endphp
                    <h1 class="text-sm md:text-2xl font-medium text-gray-900 mb-2 md:mb-4 leading-relaxed">
                        {{ __('all.test_result.talents.lead') }} <span class="font-extrabold">{{ $topDomainName }}</span>.
                    </h1>
                </div>

                    @php
                        $domainColors = [
                            'executing' => '#702B7C',
                            'relationship' => '#316EC6',
                            'strategic' => '#429162',
                            'influencing' => '#DA782D',
                            // Добавляем русские ключи для совместимости
                            'ИСПОЛНЕНИЕ' => '#702B7C',
                            'исполнение' => '#702B7C',
                            'ОТНОШЕНИЯ' => '#316EC6',
                            'отношения' => '#316EC6',
                            'МЫШЛЕНИЕ' => '#429162',
                            'мышление' => '#429162',
                            'ВЛИЯНИЕ' => '#DA782D',
                            'влияние' => '#DA782D',
                        ];

                        $domainBgColors = [
                            'executing' => 'bg-[#702B7C]',
                            'relationship' => 'bg-[#316EC6]',
                            'strategic' => 'bg-[#429162]',
                            'influencing' => 'bg-[#DA782D]',
                            // Добавляем русские ключи для совместимости
                            'ИСПОЛНЕНИЕ' => 'bg-[#702B7C]',
                            'исполнение' => 'bg-[#702B7C]',
                            'ОТНОШЕНИЯ' => 'bg-[#316EC6]',
                            'отношения' => 'bg-[#316EC6]',
                            'МЫШЛЕНИЕ' => 'bg-[#429162]',
                            'мышление' => 'bg-[#429162]',
                            'ВЛИЯНИЕ' => 'bg-[#DA782D]',
                            'влияние' => 'bg-[#DA782D]',
                        ];

                        // Отладочная информация
                        // dd('Debug info:', [
                        //     'domainScores' => $domainScores,
                        //     'domains' => $domains,
                        //     'total' => array_sum($domainScores)
                        // ]);

                        // Calculate total score and percentages
                        $totalScore = array_sum($domainScores);
                        $totalScore = $totalScore > 0 ? $totalScore : 1;

                        // Гарантируем, что все 4 домена присутствуют в стандартном порядке
                        $allDomains = ['executing', 'influencing', 'relationship', 'strategic'];
                        $completeDomainScores = [];
                        foreach ($allDomains as $domain) {
                            $completeDomainScores[$domain] = $domainScores[$domain] ?? 0;
                        }

                        // Гарантируем наличие всех доменов в массиве названий
                        $defaultDomainNames = [
                            'executing' => 'ИСПОЛНЕНИЕ',
                            'influencing' => 'ВЛИЯНИЕ',
                            'relationship' => 'ОТНОШЕНИЯ',
                            'strategic' => 'МЫШЛЕНИЕ'
                        ];

                        foreach ($defaultDomainNames as $key => $name) {
                            if (!isset($domains[$key])) {
                                $domains[$key] = $name;
                            }
                        }

                        // Sort domains by score (descending) - только для определения топ домена
                        $sortedForTop = $completeDomainScores;
                        arsort($sortedForTop);

                        // Для отображения используем стандартный порядок доменов (исполнение, влияние, отношения, мышление)
                        $sortedDomainScores = $completeDomainScores;

                        // Calculate percentages directly here
                        $domainPercentages = [];
                        foreach ($sortedDomainScores as $domain => $score) {
                            $domainPercentages[$domain] = round(($score / $totalScore) * 100);
                        }

                        $percentages = [40, 30, 20, 10];
                        $i = 0;
                        $j = 0;
                        $k = 0;
                    @endphp

                    <!-- Temporary Debug Info (remove in production) -->
{{--                    <div class="mb-4 p-3 bg-yellow-100 border border-yellow-400 rounded text-sm">--}}
{{--                        <strong>Debug Info:</strong><br>--}}
{{--                        Domain Scores: {{ json_encode($domainScores) }}<br>--}}
{{--                        Complete Domain Scores: {{ json_encode($completeDomainScores) }}<br>--}}
{{--                        Domains: {{ json_encode($domains) }}<br>--}}
{{--                        Sorted Domains: {{ json_encode($sortedForTop) }}<br>--}}
{{--                        Total Score: {{ $totalScore }}--}}
{{--                    </div>--}}


                    <!-- Single horizontal bar -->
                    <div class="flex gap-1 w-full h-6 md:h-8 overflow-hidden mb-3 md:mb-4 rounded hidden md:flex">
                        @foreach ($sortedForTop as $domain => $score)
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
                    <div class="flex gap-1 w-full hidden md:flex">
                        @foreach ($sortedForTop as $domain => $score)
                            @php
                                $percentage = $percentages[$j] ?? 10; // default to 10 if more domains
                                $j++;
                            @endphp
                            <div class="text-left flex-shrink-0" style="width: {{ $percentage }}%; min-width: 10%;">
                                <div class="text-xs md:text-sm font-medium text-gray-700 truncate">
                                    {{ $localizedDomains[app()->getLocale()][$domain] ?? $domain }}
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="flex w-full flex-col md:hidden">
                        @foreach ($sortedForTop as $domain => $score)
                            @php
                                $percentage = $percentages[$k] ?? 10; // default to 10 if more domains
                                $k++;
                            @endphp
                            <div class="flex flex-col text-left flex-shrink-0 mb-2">
                                <div class="text-sm font-medium text-gray-700 truncate">
                                    {{ $localizedDomains[app()->getLocale()][$domain] ?? $domain }}
                                </div>
                                <div class="{{ $domainBgColors[$domain] ?? 'bg-gray-400' }} flex items-center justify-center p-0.5 flex-shrink-0"
                                     style="width: {{ $percentage + 30 }}%; min-width: 10%;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="text-sm md:text-sm text-gray-600 mb-6 md:mb-8 leading-relaxed">
                    {{ $domainsDescription[app()->getLocale()][$topDomain] }}
                </p>

                <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4">
                    {{ __('all.test_result.talents.by_domains') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-1 mb-6 md:mb-8">
                    @foreach ($domains as $domain => $name)
                        <div class="mb-4 md:mb-0">
                            <div class="text-center font-semibold uppercase text-xs md:text-sm mb-2 md:mb-3 pb-1 md:pb-2 text-gray-800 p-1 md:p-2 border-b-4 md:border-b-8"
                                style="border-color: {{ $domainColors[$domain] }}">
                                {{ $localizedDomains[app()->getLocale()][$domain] ?? $name }}
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
                                        <div class="text-lg md:text-xl font-semibold">{{ $talent['rank'] }}</div>
                                        <div class="text-xs md:text-xs text-center leading-tight mt-1">{{ $talent['name'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="space-y-5">
                    <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4">{{ __('all.test_result.talents.desc_title') }}</h2>

                    <span class="block text-sm md:text-base">
                        {{ __('all.test_result.talents.desc_1') }}
                    </span>

                    <span class="block text-sm md:text-base">
                        {{ __('all.test_result.talents.desc_2') }}
                    </span>

                    <span class="block text-sm md:text-base">
                        {{ __('all.test_result.talents.desc_3') }}
                    </span>
                </div>

                <!-- Таланты блок -->
                <div class="mt-6 space-y-6" x-data="{ expandedTalents: [] }">
                    <h2 class="text-lg md:text-xl font-semibold text-center">{{ __('all.test_result.talents.content') }}</h2>

                    @php
                        $topTenTalents = collect($userResults)->take(10)->toArray();
                        $remainingTalents = collect($userResults)->skip(10)->toArray();

                        // Вычисляем максимальный балл для расчета процентов
                        $maxScore = collect($userResults)->max('score');
                        $maxScore = $maxScore > 0 ? $maxScore : 1; // Избегаем деления на 0
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Левая колонка - Топ 10 талантов -->
                        <div>
                            <div class="space-y-1">
                                @foreach ($topTenTalents as $index => $talent)
                                    @php
                                        // Получаем цвет домена для таланта
                                        $talentDomainColor = $domainColors[$talent['domain']] ?? '#6B7280';
                                        $mutedDomainColor = $mutedBgColors[$talent['domain']];
                                        // Вычисляем процент
                                        $percentage = round(($talent['score'] / $maxScore) * 100);
                                        $talentId = 'talent_' . $talent['id'];
                                    @endphp

                                    <!-- Все топ 10 талантов с аккордеоном -->
                                    <div class="border-gray-200 transition-all overflow-hidden"
                                         x-data="{ open: false }"
                                    >
                                        <!-- Заголовок таланта -->
                                        <div class="flex items-center p-3 justify-between cursor-pointer"
                                             style="background-color: {{$talentDomainColor}}"
                                             @click="open = !open">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-2">
                                                    {{ $talent['rank'] }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm text-white font-semibold text-gray-900 truncate">
                                                        {{ $talent['name'] }}
                                                    </h3>
                                                </div>
                                            </div>
                                            <!-- Стрелка -->
                                            <div class="text-white transition-transform duration-200"
                                                 :class="openTalent === '{{ $index }}' ? 'rotate-180' : ''">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Аккордеон -->
                                        <div class="p-3 {{$mutedDomainColor}}"
                                             x-show="open"
                                             x-transition
                                             style="display: none"
                                        >
                                            @if (!empty($talent['description']))
                                                <div class="mb-3">
                                                    <p class="text-xs text-gray-950 leading-tight mb-2">{{ $talent['short_description'] }}</p>
                                                    <h4 class="text-xs text-gray-950 font-semibold mb-1">{{ __('all.test_result.talents.review') }}</h4>
                                                    <p class="text-xs text-gray-950 leading-tight">{{ $talent['description'] }}</p>
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
                                @foreach ($remainingTalents as $ind => $remTalent)
                                    @php
                                        // Получаем цвет домена для таланта
                                        $talentDomainColor = $domainColors[$remTalent['domain']] ?? '#6B7280';
                                        // Вычисляем процент
                                        $percentage = round(($remTalent['score'] / $maxScore) * 100);
                                        $remainingTalentId = 'remaining_talent_' . $remTalent['id'];
                                    @endphp

                                    <!-- Остальные таланты - с аккордеоном для краткого описания -->
                                    <div
                                        class="bg-gray-50 p-3 transition-all hover:bg-gray-100"
                                        style="border: 1px solid {{ $talentDomainColor }}"
                                        x-data="{ open: false }"
                                    >
                                        <!-- Заголовок -->
                                        <div class="flex items-center justify-between cursor-pointer"
                                             @click="open = !open"
                                        >
                                            <div class="flex items-center flex-1 min-w-0">
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-semibold mr-2"
                                                     style="color: {{ $talentDomainColor }}">
                                                    {{ $remTalent['rank'] }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-semibold truncate"
                                                        style="color: {{ $talentDomainColor }}">
                                                        {{ $remTalent['name'] }}
                                                    </h3>
                                                </div>
                                            </div>
                                            <!-- Стрелка -->
                                            <div class="transition-transform duration-200"
                                                 :class="openRemainTalent === {{ $ind }} ? 'rotate-180' : ''"
                                                 style="color: {{ $talentDomainColor }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Контент -->
                                        @if (!empty($talent['short_description']))
                                            <div class="overflow-hidden mt-2"
                                                 x-show="open"
                                            >
                                                <div class="mb-3">
                                                    <h4 class="text-xs font-semibold text-gray-900 mb-1">{{ __('all.test_result.talents.short_desc') }}</h4>
                                                    <p class="text-xs text-gray-700 leading-tight">{{ $remTalent['short_description'] }}</p>
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

            </div>

            <div id="spheres-section" x-show="activeTab === 'spheres'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4">{{ __('all.test_result.spheres.recommendation_title') }}</h2>
                <p class="text-sm md:text-sm text-gray-600 mb-6 leading-relaxed">
                    {{ __('all.test_result.spheres.recommendation_desc') }}
                </p>

                @php
                    $topTenSpheres = collect($topSpheres)->take(10)->toArray();
                    $remainingSpheres = collect($topSpheres)->skip(10)->toArray();
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Левая колонка - Топ 10 сфер -->
{{--                    @if ($this->isFullPlan)--}}
                        <!-- Профессии из ваших топ-10 сфер блок -->
                        @php
                            $topSphereIndex = 0;
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
                        <div class="bg-white rounded-lg overflow-hidden">
                            <!-- Desktop Table View -->
                            <div class="space-y-1">
                                @foreach ($professionsGroupedBySphere as $index => $sphereData)
                                    <div x-data="{ open: false }"
                                         class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                        <!-- Заголовок сферы для мобильной версии -->
                                        <div @click="open = !open" class="p-4 cursor-pointer">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center flex-1 min-w-0">
                                                    <div class="flex items-center space-x-3">
                                                    <span class="text-sm font-medium text-blue-600"
                                                          style="color: #316EC6">{{ $topSphereIndex = $topSphereIndex + 1 }}</span>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $sphereData['sphere']['name'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 flex-shrink-0">
                                                    <!-- Percentage -->
                                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">
                                                    {{ round($sphereData['sphere']['compatibility_percentage']) }}%
                                                </span>

                                                    <!-- Accordion Arrow -->
                                                    @if (count($sphereData['professions']) > 0)
                                                        <div class="text-gray-400 transition-transform duration-200"
                                                             :class="open ? 'rotate-90' : ''">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                      d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Профессии для мобильной версии -->
                                        <div x-show="open"
                                             x-transition
                                             class="p-4 bg-gray-50"
                                             style="display: none;">
                                            @if (count($sphereData['professions']) > 0)
                                                <div class="space-y-2">
                                                    <div class="text-sm md:text-base mb-3">
                                                        {{$sphereData['sphere']['description']}}
                                                    </div>
                                                    @foreach ($sphereData['professions'] as $profession)
                                                        <div class="bg-white border border-gray-200 rounded-lg px-3 py-1 hover:bg-gray-50 transition-colors">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex-1 min-w-0">
                                                                    <h5 class="text-sm font-normal text-gray-900 mb-1">
                                                                        {{ $profession['name'] }}
                                                                    </h5>

                                                                </div>
                                                                @if($this->isFullPlan && isset($profession['compatibility_percentage']))
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="text-xs text-gray-500">
                                                                            {{ round($profession['compatibility_percentage']) }}%
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if (isset($profession['description']) && $profession['description'])
                                                                    <button
                                                                        @click.stop="openProfessionModal({{ json_encode($profession) }})"
                                                                        class="ml-2 p-1 text-gray-400 hover:text-blue-500 transition-colors flex-shrink-0"
                                                                        title="Показать описание профессии">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="py-6 text-center">
                                                    <p class="text-sm text-gray-500">Профессии для этой сферы пока не добавлены</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (empty($professionsGroupedBySphere))
                                <div class="text-center py-8 md:py-12 px-4">
                                    <div class="text-gray-400 mb-3">
                                        <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg md:text-xl font-medium text-gray-900 mb-2">Нет доступных сфер</h3>
                                    <p class="text-sm md:text-base text-gray-500">Сферы профессий пока не созданы.</p>
                                </div>
                            @endif
                        </div>
{{--                    @endif--}}

                    <div>
                        <div class="space-y-1">
                            @php
                                $remainingSpheresIndex = 10;
                            @endphp
                            @foreach ($remainingSpheres as $index => $sphere)
                                @php
                                    $sphereColor = '#316EC6';
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-4">
                                        <div class="flex-1">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-sm font-medium text-blue-600"
                                                          style="color: #316EC6">{{ $remainingSpheresIndex = $remainingSpheresIndex + 1 }}</span>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $sphere['name'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if ($this->isFullPlan)
                                                <!-- Progress Bar для полного тарифа - скрыт на мобильных -->
{{--                                                <div class="hidden md:flex flex-1 min-w-[80px]">--}}
{{--                                                    <div class="w-full bg-gray-200 rounded-full h-1">--}}
{{--                                                        <div class="h-1 rounded-full transition-all duration-500"--}}
{{--                                                            style="width: {{ round($sphere['compatibility_percentage']) }}%; background-color: {{ $sphereColor }}">--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
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

                <!-- Modal for Profession Description -->
                <div x-show="modalProfession" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    style="background-color: rgba(0, 0, 0, 0.5);" @click="closeProfessionModal()">
                    <div @click.stop x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900" x-text="modalProfession?.name"></h3>
                            <button @click="closeProfessionModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed"
                            x-text="modalProfession?.description || 'Описание отсутствует'"></p>
                    </div>
                </div>
            </div>

            <div id="professions-section" x-show="activeTab === 'professions'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                @php
                    $topThirtyProfessions = collect($topProfessions)->take(30)->toArray();
                    $nextTenProfessions = collect($topProfessions)->take(-10)->toArray();

                    // Фиолетовый цвет для профессий
                    $professionColor = '#8B5CF6';
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Топ 30 профессий на полную ширину -->
                @if (count($topThirtyProfessions) > 0)
                    <div class="mb-8">
                        <div class="flex flex-col items-start">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('all.test_result.professions.recommendation') }}</h3>
                            <p class="text-xs md:text-sm text-gray-600 leading-relaxed">
                                {{ __('all.result.professions.desc') }}
                            </p>
                        </div>
                        <!-- Desktop Table View -->
                        <div class="hidden md:block space-y-2" style="margin-top: 1.8rem">
                            @foreach ($topThirtyProfessions as $index => $profession)
                                <div class="bg-white px-4 py-2 transition-all border border-gray-200 rounded-lg" x-data="{ open: false }">
                                    <div class="flex items-center justify-between" @click="open = !open">
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
                                            <div class="text-gray-400 transition-transform duration-200"
                                                 :class="open ? 'rotate-90' : ''">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show="open"
                                         x-transition
                                         class="py-4 pb-2 text-sm"
                                         style="display: none;">
                                        {{$profession['description']}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Mobile Card View -->
                        <div class="md:hidden space-y-2 mt-2">
                            @foreach ($topThirtyProfessions as $index => $profession)
                                <div class="bg-white px-3 py-2 transition-all border border-gray-200 rounded-lg" x-data="{ open: false }">
                                    <div class="flex items-center justify-between space-x-1" @click="open = !open">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-medium text-gray-900 inline-block">{{ $index + 1 }}</span>
                                        <span class="text-xs font-medium text-gray-900 inline-block leading-none">{{ $profession['name'] }}</span>
                                        </div>
                                        <div class="flex space-x-1">
                                            <span class="text-xs text-blue-600 font-base">{{ round($profession['compatibility_percentage']) }}%</span>
                                            <div class="text-gray-400 transition-transform duration-200"
                                                 :class="open ? 'rotate-90' : ''">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="open"
                                         x-transition
                                         class="py-4 text-xs"
                                         style="display: none;">
                                        {{$profession['description']}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Следующие 10 профессий (31-40) - отдельный блок с предупреждением -->
                @if (count($nextTenProfessions) > 0)
                    <div class="">
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
                                <div class="bg-white border border-red-400 px-4 py-2 rounded-lg opacity-60">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs md:text-sm font-medium text-red-600">{{ $professionIndex = $professionIndex + 1 }}</span>
                                                <h4 class="text-xs md:text-sm text-gray-900">{{ $profession['name'] }}</h4>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- Прогресс-бар только для десктопа -->
                                            <div class="hidden lg:block flex-1 min-w-[60px]">
                                                <div class="w-full bg-red-100 rounded-full h-1">
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

                <!-- Modal for Profession Description -->
                <div x-show="modalProfession" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-center justify-center z-50 p-4"
                    style="background-color: rgba(0, 0, 0, 0.5);" @click="closeProfessionModal()">
                    <div @click.stop x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" x-text="modalProfession?.name"></h3>
                                <div class="text-sm text-blue-600 mt-1" x-text="modalProfession?.sphere_name || ''">
                                </div>
                            </div>
                            <button @click="closeProfessionModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed"
                            x-text="modalProfession?.description || 'Описание отсутствует'"></p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h2 class="text-2xl font-bold mb-4">У вас пока нет результатов</h2>
                <p class="text-gray-600 mb-8">Вы еще не прошли тест или не сохранили ответы.</p>
                <a href="{{ route('test') }}"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">Пройти
                    тест</a>
            </div>
        @endif

        <!-- Модальное окно для обновления тарифа -->
        @if (session('show_upgrade_modal'))
            <div class="fixed inset-0 flex items-center justify-center z-50 p-4" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">Обновление тарифа</h3>
                            </div>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-700">{{ session('upgrade_message', 'Для доступа к этой функции необходимо обновить тарифный план.') }}</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                            Закрыть
                        </button>
                        <a href="{{ route('pricing') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                            Посмотреть тарифы
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('talentTestResults', () => ({
        activeTab: 'talents',
        canViewSpheresTab: {{ $this->canViewSpheresTab ? 'true' : 'false' }},
        canViewProfessionsTab: {{ $this->canViewProfessionsTab ? 'true' : 'false' }},
        modalSphere: null,
        modalProfession: null,
        expandedSpheres: [],
        expandedTalents: [],
        isExporting: false,

        init() {
            console.log('Alpine.js initialized for talent test results');
        },

        setActiveTab(tab) {
            if (tab === 'spheres' && !this.canViewSpheresTab) {
                return;
            }
            if (tab === 'professions' && !this.canViewProfessionsTab) {
                return;
            }
            this.activeTab = tab;
        },

        toggleSphere(sphereId) {
            if (this.expandedSpheres.includes(sphereId)) {
                this.expandedSpheres = this.expandedSpheres.filter(id => id !== sphereId);
            } else {
                this.expandedSpheres.push(sphereId);
            }
        },

        openSphereModal(sphere) {
            this.modalSphere = sphere;
        },

        closeSphereModal() {
            this.modalSphere = null;
        },

        openProfessionModal(profession) {
            this.modalProfession = profession;
        },

        closeProfessionModal() {
            this.modalProfession = null;
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    }));
});
</script>
