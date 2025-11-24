<div>

    {{-- hero section --}}
    <section class="relative py-6 sm:py-8 md:py-12 lg:py-16 xl:py-20">
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="text-xl sm:text-2xl md:text-4xl lg:text-5xl xl:text-6xl font-light leading-tight text-gray-900">
                    <span id="typed-output" class="text-black font-bold"></span>
                </h1>

                <div class="group relative mt-6 md:mt-10 inline-flex">
                    <a href="{{ route('test-preparation') }}" title=""
                        class="w-full sm:w-auto rounded-xl bg-blue-700 px-8 md:px-10 py-3 md:py-4 font-medium text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 hover:bg-blue-600 text-base md:text-lg">
                        {{ __('all.home.top.pass_test') }}
                    </a>
                    <div class="-scale-x-100 absolute left-0 -bottom-10 hidden h-10 w-10 -rotate-12 text-blue-600 lg:inline-flex">
                        <svg viewBox="0 0 82 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0 21.3963C0.189514 19.1422 0.475057 16.717 0.554355 14.2852C0.582363 13.435 0.32301 12.6326 1.24839 12.1517C1.43863 12.053 1.7169 11.8956 1.85767 11.9661C4.2446 13.1626 6.90906 13.1934 9.41312 13.8814C11.09 14.3423 12.6519 15.089 13.7134 16.5797C13.9251 16.8774 13.9105 17.3427 14 17.7305C13.6228 17.8077 13.2227 18.01 12.8727 17.9421C10.3283 17.4477 7.78825 16.9245 5.25946 16.353C4.46612 16.1737 4.32244 16.4862 4.22859 17.1961C4.0118 18.8342 3.66769 20.4541 3.43198 22.0899C3.33086 22.7891 3.36905 23.509 3.35123 24.2197C3.34977 24.2791 3.44107 24.3474 3.43052 24.3989C3.32213 24.9318 3.2712 25.8796 3.07114 25.9142C2.49387 26.0144 1.77655 25.8915 1.25603 25.5961C-0.352473 24.6832 0.143681 23.0129 0 21.3963Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M33.9279 29.9296C33.9687 30.0252 34.0103 30.1211 34.0512 30.2167L36.776 28.708C36.7189 28.6018 36.6613 28.4961 36.6041 28.3903C35.7123 28.9033 34.8197 29.4166 33.9279 29.9296ZM55.213 27.9357C55.2513 28.0076 55.2895 28.0795 55.3278 28.1513C56.8382 27.5018 58.3486 26.8518 59.8593 26.2019C59.8129 26.092 59.7661 25.9821 59.7197 25.8726C58.2175 26.5602 56.7153 27.2481 55.213 27.9357ZM40.7384 18.9565C40.5279 17.8215 40.3393 16.6815 40.0998 15.5525C39.952 14.8551 39.5106 14.6711 38.8099 14.825C36.6153 15.3082 34.9909 17.2686 34.7963 19.6189C34.584 22.1806 36.0472 23.7605 37.8517 25.1395C37.9927 25.2475 38.5155 25.0604 38.6986 24.8591C40.2045 23.1998 40.6396 21.163 40.7384 18.9565ZM47.8846 27.7513C53.9169 27.9699 58.9887 25.6539 63.5351 21.8258C68.7108 17.4677 72.7252 12.1435 76.2413 6.39113C77.3061 4.64903 78.3271 2.87833 79.4328 1.16371C79.7291 0.70344 80.2137 0.234515 80.706 0.0824723C81.0457 -0.0225277 81.5473 0.410268 81.9765 0.603333C81.8444 0.859247 81.7237 1.12306 81.5774 1.37032C81.1827 2.03645 80.7194 2.66758 80.3867 3.36306C79.3033 5.6264 78.3041 7.93113 77.1981 10.1824C76.4525 11.6998 75.639 13.1905 74.7457 14.6225C74.1814 15.5269 73.3694 16.269 72.7471 17.1414C71.7606 18.5237 70.9604 20.0611 69.8622 21.3395C68.1531 23.33 66.4111 25.3434 64.4139 27.0174C59.9989 30.718 54.9038 32.5263 49.0801 32.1605C46.3701 31.9904 43.6835 31.9283 41.122 30.8655C40.842 30.7492 40.3185 30.9333 40.0448 31.1527C37.2899 33.3656 34.1239 34.5277 30.6632 34.7456C28.0734 34.909 25.4198 35.1171 22.8828 34.7219C20.7546 34.3908 18.675 33.3742 16.7198 32.3694C14.9819 31.4756 13.3686 30.2773 11.8348 29.0418C9.65017 27.2812 8.09522 24.9795 7.06601 22.3556C6.91824 21.9789 7.17257 21.2819 7.46774 20.9267C7.79559 20.5315 8.26675 20.7212 8.80326 20.9647C10.4826 21.7271 11.1635 23.3172 12.0776 24.6916C13.809 27.2959 16.297 28.8333 19.144 29.6515C24.0015 31.0477 28.8342 30.4606 33.5239 28.7422C36.0572 27.8134 36.0323 27.708 34.1863 25.8643C31.7566 23.438 30.4122 20.5417 30.5982 17.0518C30.8143 13.0012 34.1347 10.1538 38.1338 10.4515C39.3892 10.5452 40.2439 11.3239 41.0648 12.1255C42.9294 13.9466 43.9712 16.2194 44.3347 18.7977C44.7112 21.4648 44.7806 24.1113 43.5286 26.6189C43.2264 27.2244 43.5171 27.489 44.1483 27.5187C45.3947 27.5778 46.6393 27.6719 47.8846 27.7513Z"
                                fill="currentColor" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        <div
            class="mt-6 md:mt-16 mb-6 md:mb-16 flex flex-col items-center justify-center space-y-6 sm:flex-row sm:space-y-0 sm:space-x-4 divide-y divide-gray-300 sm:divide-x sm:divide-y-0">
            <div class="flex max-w-xs px-4 py-4 text-center">
                <p class="text-sm md:text-base text-gray-600">{{ __('all.home.top.text_1') }}</p>
            </div>
            <div class="flex max-w-xs px-4 py-4 text-center">
                <p class="text-sm md:text-base text-gray-600">{{ __('all.home.top.text_2') }}</p>
            </div>
            <div class="flex max-w-xs px-4 py-4 text-center">
                <p class="text-sm md:text-base text-gray-600">{{ __('all.home.top.text_3') }}</p>
            </div>
        </div>
    </section>

    <section class="py-8 md:py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl py-8 md:py-12 sm:py-16 lg:py-20">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="mt-2 text-xl md:text-2xl font-semibold sm:text-4xl xl:text-4xl text-gray-900 uppercase">
                        {{ __('all.home.middle.title') }}
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-4 border border-gray-100">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl mb-2">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __('all.home.middle.accordion_1.title') }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            {{ __('all.home.middle.accordion_1.desc') }}
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-4 border border-gray-100">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl mb-2">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __('all.home.middle.accordion_2.title') }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            {{ __('all.home.middle.accordion_2.desc') }}
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-4 border border-gray-100">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl mb-2">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ __('all.home.middle.accordion_3.title') }}
                        </h3>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            {{ __('all.home.middle.accordion_3.desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8 md:py-12 bg-white">
        <div class="mx-auto max-w-7xl py-8 md:py-12 text-blue-700 sm:py-16 lg:py-20 space-y-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="mt-2 text-2xl md:text-3xl font-bold sm:text-4xl xl:text-4xl text-gray-900 uppercase">{{ __('all.home.middle.accordion_4.title') }}</h2>
            </div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-8 md:flex align-middle items-center">
                <div class="w-full">
                    <div class="text-gray-600 text-md md:text-lg">
                        <ul>
                            <li class="flex items-center gap-3 mb-2">
                                <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>{{ __('all.home.middle.accordion_4.desc_1') }}</span>
                            </li>
                            <li class="flex items-center gap-3 mb-2">
                                <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>{{ __('all.home.middle.accordion_4.desc_2') }}</span>
                            </li>
                            <li class="flex items-center gap-3 mb-2">
                                <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>{{ __('all.home.middle.accordion_4.desc_3') }}</span>
                            </li>
                            <li class="flex items-center gap-3 mb-2">
                                <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>{{ __('all.home.middle.accordion_4.desc_4') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span>{{ __('all.home.middle.accordion_4.desc_5') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                    <img src="{{ asset('assets/images/screens.png') }}"
                         class="w-full h-auto"
                         alt="">
                </div>
            </div>
        </div>
    </section>


    <!-- Profession Map Section -->
    <section class="py-12 md:py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold">
                    {{ __('all.home.bottom.title') }}
                </h2>
                <p class="mt-3 md:mt-4 text-sm md:text-base text-gray-600 max-w-2xl mx-auto">
                    {{ __('all.home.bottom.desc') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="rounded-2xl p-4 md:p-8 transition-all duration-300 transform">
                        <div class="space-y-6 md:space-y-8">
                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">{{ __('all.home.bottom.content.title_1') }}</h3>
                                    <p class="text-sm md:text-base text-gray-600">{{ __('all.home.bottom.content.desc_1') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 md:space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-1 md:mb-2">{{ __('all.home.bottom.content.title_2') }}</h3>
                                    <p class="text-sm md:text-base text-gray-600">{{ __('all.home.bottom.content.desc_2') }}</p>
                                </div>
                            </div>

                            <div class="pt-4 md:pt-6">
                                <a href="{{ route('profession-map') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-6 md:px-8 py-3 text-sm md:text-base font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl hover:bg-blue-700 transform transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <span>{{ __('all.home.bottom.btn') }}</span>
                                    <svg class="ml-2 -mr-1 w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 md:order-2">
                    <div class="relative mx-auto max-w-sm md:max-w-none">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-blue-50 transform rounded-3xl"></div>
                        <div class="relative">
                            <img src="{{ asset('assets/images/profession.svg') }}"
                                 class="w-full h-auto rounded-2xl"
                                 alt="Карта профессий">
                        </div>

                        <div class="absolute -bottom-4 -right-4 w-16 h-16 md:w-24 md:h-24 bg-gradient-to-br from-blue-600 to-blue-400 rounded-full opacity-20 blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
