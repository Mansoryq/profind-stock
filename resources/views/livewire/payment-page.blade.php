<div class="min-h-screen bg-white py-8 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 md:mb-16">
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 mb-4">{{ __('all.payment.top.title') }}</h1>
            @if($testSession)
            @else
                <p class="text-lg md:text-xl text-gray-600 font-light">{{ __('all.payment.top.info_title') }}</p>
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg inline-block">
                    <p class="text-sm text-blue-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('all.payment.top.info_desc') }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12 mb-12 md:mb-16">
            <div class="relative bg-white border border-yellow-300 scale-105 rounded-xl transition-all duration-300 hover:border-gray-300 flex flex-col lg:transform lg:scale-105">

                <!-- Crown for the last plan -->
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full p-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 16L3 6l5.5 4L12 4l3.5 6L21 6l-2 10H5z"/>
                        </svg>
                    </div>
                </div>

                <div class="p-8 md:p-10 flex flex-col flex-grow">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-xl md:text-2xl font-medium text-gray-900 mb-4 mt-4">
                            {{ __('all.payment.cards.top.title') }}
                            <span class="block text-sm text-yellow-600 font-normal mt-1">{{ __('all.payment.cards.top.recommendation') }}</span>
                        </h3>
                        <div class="mb-6">
                            <span class="text-4xl md:text-5xl font-light text-gray-900">{{ number_format((int) __('all.payment.cards.top.price')) }}</span>
                            <span class="text-gray-500 ml-2 text-lg">{{ __('all.payment.cards.top.currency') }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.top.desc_1') }}</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.top.desc_2') }}</span>
                        </li>
                    </ul>

                    <!-- CTA Button -->
                    <button
                        wire:click="selectPlan('talents_spheres_professions')"
                        class="w-full py-4 px-6 rounded-lg font-medium transition-all duration-200 text-sm md:text-base border mt-auto
                                bg-gradient-to-r from-yellow-500 to-yellow-600 text-white border-yellow-500 hover:from-yellow-600 hover:to-yellow-700">
                        @if($testSession)
                            {{ __('all.payment.cards.top.submit_btn') }}
                        @else
                            {{ __('all.payment.cards.top.submit_btn_no_session') }}
                        @endif
                    </button>
                </div>
            </div>

            <div class="relative bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:border-gray-300 flex flex-col">

                <div class="p-8 md:p-10 flex flex-col flex-grow">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-xl md:text-2xl font-medium text-gray-900 mb-4">
                            {{ __('all.payment.cards.middle.title') }}
                        </h3>
                        <div class="mb-6">
                            <span class="text-4xl md:text-5xl font-light text-gray-900">{{ number_format((int) __('all.payment.cards.middle.price')) }}</span>
                            <span class="text-gray-500 ml-2 text-lg">{{ __('all.payment.cards.middle.currency') }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.middle.desc_1') }}</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.middle.desc_2') }}</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.middle.desc_3') }}</span>
                        </li>
                    </ul>

                    <!-- CTA Button -->
                    <button
                        wire:click="selectPlan('talents')"
                        class="w-full py-4 px-6 rounded-lg font-medium transition-all duration-200 text-sm md:text-base border mt-auto
                                bg-blue-500 text-white border-blue-500 hover:bg-blue-600">
                        @if($testSession)
                            {{ __('all.payment.cards.middle.submit_btn') }}
                        @else
                            {{ __('all.payment.cards.middle.submit_btn_no_session') }}
                        @endif
                    </button>
                </div>
            </div>

            <div class="relative bg-white border border-gray-200 rounded-xl transition-all duration-300 hover:border-gray-300 flex flex-col">

                <div class="p-8 md:p-10 flex flex-col flex-grow">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-xl md:text-2xl font-medium text-gray-900 mb-4">
                            {{ __('all.payment.cards.bottom.title') }}
                        </h3>
                        <div class="mb-6">
                            <span class="text-4xl md:text-5xl font-light text-gray-900">{{ number_format((int) __('all.payment.cards.bottom.price')) }}</span>
                            <span class="text-gray-500 ml-2 text-lg">{{ __('all.payment.cards.bottom.currency') }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.bottom.desc_1') }}</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.bottom.desc_2') }}</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2.5 mr-3 flex-shrink-0"></div>
                            <span class="text-sm md:text-base text-gray-700 leading-relaxed">{{ __('all.payment.cards.bottom.desc_4') }}</span>
                        </li>
                    </ul>

                    <!-- CTA Button -->
                    <button
                        wire:click="selectPlan('talents_spheres')"
                        class="w-full py-4 px-6 rounded-lg font-medium transition-all duration-200 text-sm md:text-base border mt-auto
                                bg-blue-500 text-white border-blue-500 hover:bg-blue-600">
                        @if($testSession)
                            {{ __('all.payment.cards.bottom.submit_btn') }}
                        @else
                            {{ __('all.payment.cards.bottom.submit_btn_no_session') }}
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Back to Test Button -->
{{--        <div class="text-center">--}}
{{--            <a href="{{ route('talent-test') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-light text-sm md:text-base transition-colors">--}}
{{--                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>--}}
{{--                </svg>--}}
{{--                {{ __('all.payment.top.back_test') }}--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
</div>
