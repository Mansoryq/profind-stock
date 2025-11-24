<div class="bg-white flex items-center justify-center px-4 py-24 md:py-12">
    <div class="max-w-4xl mx-auto text-center my-4">

        <!-- Заголовок -->
        <div class="mb-12">
            <h1 class="text-xl md:text-4xl lg:text-5xl font-light text-gray-900 mb-2 md:mb-4">
                {{ __('all.test-preparation.title') }}
            </h1>
            <p class="text-sm md:text-xl text-gray-600 font-light">
                {{ __('all.test-preparation.desc') }}
            </p>
        </div>

        <!-- Инструкции -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-8 mb-12">
            <!-- Время -->
            <div class="text-left">
                <div class="flex items-center mb-1 md:mb-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-600 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                        <span class="text-white text-xs md:text-sm font-medium">1</span>
                    </div>
                    <h3 class="text-sm md:text-lg font-medium text-gray-900">{{ __('all.test-preparation.instruction_1') }}</h3>
                </div>
            </div>

            <!-- Количество вопросов -->
            <div class="text-left">
                <div class="flex items-center mb-1 md:mb-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-600 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                        <span class="text-white text-xs md:text-sm font-medium">2</span>
                    </div>
                    <h3 class="text-sm md:text-lg font-medium text-gray-900">{{ __('all.test-preparation.instruction_2') }}</h3>
                </div>
            </div>

            <!-- Время на вопрос -->
            <div class="text-left">
                <div class="flex items-center mb-1 md:mb-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-600 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                        <span class="text-white text-xs md:text-sm font-medium">3</span>
                    </div>
                    <h3 class="text-sm md:text-lg font-medium text-gray-900">{{ __('all.test-preparation.instruction_3') }}</h3>
                </div>
            </div>

            <!-- Честность -->
            <div class="text-left">
                <div class="flex items-center mb-1 md:mb-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-600 rounded-full flex items-center justify-center mr-2 md:mr-3 flex-shrink-0">
                        <span class="text-white text-xs md:text-sm font-medium">4</span>
                    </div>
                    <h3 class="text-sm md:text-lg font-medium text-gray-900">{{ __('all.test-preparation.instruction_4') }}</h3>
                </div>
            </div>
        </div>

        <!-- Кнопка начать тест -->
        <div>
            <button wire:click="startTest"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 md:px-12 py-3 md:py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-base md:text-lg rounded-xl transition-colors duration-200 hover:shadow-xl cursor-pointer">
                {{ __('all.test-preparation.btn') }}
            </button>
        </div>

    </div>
</div>
