<div x-data="talentTest(@js($allQuestions), {{ $timePerQuestion }}, '{{ $testSessionId }}')"
     x-init="init()"
     class="min-h-screen bg-gray-50 py-4 md:py-8 px-4">

    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Progress Bar -->
        <div class="bg-gray-100 p-3 md:p-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                <span class="text-sm font-medium text-gray-700 mb-2 sm:mb-0">
                    @if(app()->getLocale() === 'ru')
                        {{ __('all.test.question') }} <span x-text="currentQuestionIndex + 1"></span> {{ __('all.test.from') }} {{ $totalQuestions }}
                    @else
                        <span x-text="currentQuestionIndex + 1"></span>/{{ $totalQuestions }} {{ __('all.test.question') }}
                    @endif
                </span>
                <div class="flex items-center justify-between sm:justify-end">
                    <!-- Timer Display -->
                    <div class="flex items-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-gray-500 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">
                            <span x-text="timeRemaining"></span> сек.
                        </span>
                    </div>
                    <span class="text-sm font-medium text-gray-500">
                        <span x-text="Math.round(progress)"></span>%
                    </span>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                     :style="`width: ${progress}%`"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="p-4 md:p-6">
            <template x-if="currentQuestion">
                <div>
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6 leading-relaxed"
                        x-text="currentQuestion.question"></h2>

                    <!-- Answer Options -->
                    <div class="space-y-3 md:space-y-4 mb-6 md:mb-8">
                        <template x-for="(option, index) in answersOp['{{app()->getLocale()}}']" :key="index">
                            <label class="flex items-center p-3 md:p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200"
                                   :class="selectedAnswer === index + 1 ? 'border-blue-500 bg-blue-50 scale-[1.02]' : 'border-gray-200'"
                                   @click="selectAnswerAndNext(index + 1)">
                                <input type="radio" class="h-4 w-4 md:h-5 md:w-5 text-blue-600 focus:ring-blue-500 flex-shrink-0"
                                       :value="index + 1"
                                       :checked="selectedAnswer === index + 1"
                                       name="question_answer">
                                <span class="ml-3 text-sm md:text-base text-gray-700" x-text="option"></span>
                            </label>
                        </template>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-4 border-t space-y-3 sm:space-y-0">
                        <button @click="previousQuestion()"
                                :disabled="currentQuestionIndex === 0"
                                class="w-full sm:w-auto px-4 md:px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm md:text-base">
                            {{ __('all.test.back') }}
                        </button>

                        <template x-if="currentQuestionIndex === allQuestions.length - 1">
                            <button @click="submitTest()"
                                class="w-full sm:w-auto px-4 md:px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors text-sm md:text-base">
                                {{ __('all.test.end') }}
                            </button>
                        </template>

                        <template x-if="currentQuestionIndex < allQuestions.length - 1">
                            <div class="text-xs md:text-sm text-gray-500 flex items-center justify-center sm:justify-end">
                                <svg class="w-3 h-3 md:w-4 md:h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.122 2.122"></path>
                                </svg>
                                {{ __('all.test.choose') }}
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="!currentQuestion">
                <div class="text-center py-12">
                    <p class="text-gray-600">{{ __('all.test.no_found') }}</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Overlay для блокировки во время отправки -->
    <div x-show="isSubmitting"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         style="background-color: rgba(0, 0, 0, 0.5);"
         class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('all.test.accepting') }}</h3>
                <p class="text-gray-600">{{ __('all.test.wait') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
function talentTest(questions, timePerQuestion, testSessionId) {
    return {
        allQuestions: questions,
        currentQuestionIndex: 0,
        selectedAnswer: null,
        answers: new Array(questions.length).fill(null),
        responseTimes: new Array(questions.length).fill(null),
        timeRemaining: timePerQuestion,
        timePerQuestion: timePerQuestion,
        questionStartTime: null,
        progress: 0,
        timer: null,
        testSessionId: testSessionId,
        isSubmitting: false,
        isTransitioning: false, // Добавляем флаг для предотвращения двойных переходов
        answersOp: {
            'ru': [
                'Нет',
                'Скорее нет, чем да',
                'Не знаю',
                'Скорее да, чем нет',
                'Да',
            ],
            'kk': [
                'Жоқ',
                'Жоққа жақынырақ',
                'Білмеймін',
                'Иәға жақынырақ',
                'Иә',
            ]
        },

        get currentQuestion() {
            return this.allQuestions[this.currentQuestionIndex] || null;
        },

        init() {
            this.selectedAnswer = this.answers[this.currentQuestionIndex];
            this.questionStartTime = Date.now();
            this.updateProgress();
            this.startTimer();
        },

        startTimer() {
            // Останавливаем предыдущий таймер, если он есть
            this.stopTimer();
            this.timer = setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                } else {
                    // Переходим к следующему вопросу только если еще не ответили и не происходит переход
                    if (this.answers[this.currentQuestionIndex] === null && !this.isTransitioning) {
                        this.isTransitioning = true;
                        this.stopTimer();

                        // Автоматический переход по таймеру
                        if (this.currentQuestionIndex < this.allQuestions.length - 1) {
                            this.currentQuestionIndex++;
                            this.selectedAnswer = this.answers[this.currentQuestionIndex] || null;
                            this.timeRemaining = this.timePerQuestion;
                            this.questionStartTime = Date.now();
                            this.updateProgress();
                            this.startTimer();
                        } else {
                            this.submitTest();
                        }
                        this.isTransitioning = false;
                    }
                }
            }, 1000);
        },

        stopTimer() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },

        selectAnswerAndNext(answerValue) {
            // Предотвращаем двойной клик
            if (this.isTransitioning) return;

            // Останавливаем таймер сразу, чтобы избежать двойного перехода
            this.stopTimer();
            this.isTransitioning = true;

            // Записываем ответ и время
            this.selectedAnswer = answerValue;
            this.answers[this.currentQuestionIndex] = answerValue;

            if (this.questionStartTime) {
                const responseTime = Math.round((Date.now() - this.questionStartTime) / 1000);
                this.responseTimes[this.currentQuestionIndex] = responseTime;
            }

            this.updateProgress();

            // Переходим к следующему вопросу или завершаем
            setTimeout(() => {
                if (this.currentQuestionIndex < this.allQuestions.length - 1) {
                    // Переходим на следующий вопрос напрямую без дополнительных проверок
                    this.currentQuestionIndex++;
                    this.selectedAnswer = this.answers[this.currentQuestionIndex] || null;
                    this.timeRemaining = this.timePerQuestion;
                    this.questionStartTime = Date.now();
                    this.updateProgress();
                    this.startTimer();
                } else {
                    this.submitTest();
                }
                this.isTransitioning = false;
            }, 150); // Небольшая задержка для UX
        },

        nextQuestion() {
            if (this.currentQuestionIndex >= this.allQuestions.length - 1) {
                return;
            }

            this.stopTimer();
            this.currentQuestionIndex++;
            this.selectedAnswer = this.answers[this.currentQuestionIndex] || null;
            this.timeRemaining = this.timePerQuestion;
            this.questionStartTime = Date.now();
            this.updateProgress();
            this.startTimer();
        },

        previousQuestion() {
            if (this.currentQuestionIndex <= 0) {
                return;
            }

            this.stopTimer();
            this.currentQuestionIndex--;
            this.selectedAnswer = this.answers[this.currentQuestionIndex] || null;
            this.timeRemaining = this.timePerQuestion;
            this.questionStartTime = Date.now();
            this.updateProgress();
            this.startTimer();
        },

        updateProgress() {
            const answered = this.answers.filter(answer => answer !== null).length;
            const currentProgress = (this.currentQuestionIndex + 1) / this.allQuestions.length * 100;
            const answeredProgress = answered / this.allQuestions.length * 100;
            // Показываем максимальный прогресс между текущим положением и количеством отвеченных вопросов
            this.progress = Math.max(currentProgress, answeredProgress);
        },

        async submitTest() {
            this.stopTimer();
            this.isSubmitting = true;

            try {
                // Записываем время последнего ответа если есть
                if (this.questionStartTime && this.selectedAnswer) {
                    const responseTime = Math.round((Date.now() - this.questionStartTime) / 1000);
                    this.responseTimes[this.currentQuestionIndex] = responseTime;
                }

                // Отправляем результаты на сервер через AJAX
                const response = await fetch('{{ route("api.talent-test.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        testSessionId: this.testSessionId,
                        answers: this.answers,
                        responseTimes: this.responseTimes
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Перенаправляем на страницу оплаты
                    window.location.href = result.redirect_url;
                } else {
                    throw new Error(result.message || 'Ошибка отправки результатов');
                }
            } catch (error) {
                console.error('Ошибка отправки результатов:', error);
                alert('Произошла ошибка при отправке результатов. Попробуйте еще раз.');
                this.isSubmitting = false;
            }
        }
    }
}
</script>
