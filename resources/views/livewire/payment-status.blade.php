<div class="min-h-screen bg-gray-50 py-8 md:py-16 px-4" wire:poll.30s="refreshPaymentStatus" x-data="{ showPromoForm: @entangle('showPromoCodeForm') }">
    <div class="max-w-4xl mx-auto">
        <!-- Status Header -->
        <div class="text-center mb-8 md:mb-12">
{{--            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6 {{ $this->getStatusColor() }}">--}}
{{--                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">--}}
{{--                    @if($testSession->payment_status === 'pending')--}}
{{--                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>--}}
{{--                    @elseif($testSession->payment_status === 'processing')--}}
{{--                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>--}}
{{--                    @elseif($testSession->payment_status === 'review')--}}
{{--                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>--}}
{{--                    @elseif($testSession->payment_status === 'completed')--}}
{{--                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>--}}
{{--                    @else--}}
{{--                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>--}}
{{--                    @endif--}}
{{--                </svg>--}}
{{--                {{ $this->getStatusText() }}--}}
{{--            </div>--}}

{{--            <h1 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 mb-4">{{ __('all.payment_status.title') }}</h1>--}}
{{--            <h2 class="text-xl md:text-2xl font-medium text-gray-800 mb-2">{{ $plans[$plan]['name'] }}</h2>--}}
{{--            <p class="text-2xl md:text-3xl font-normal text-gray-900">{{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}</p>--}}
            @if(!$halfDiscount && !$paymentConfirmed)
            <div class="mx-auto max-w-2xl">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-4 shadow-sm">
                    <p class="text-gray-800 text-base leading-relaxed">
                        {{ __('all.payment_status.promo.title') }}<br>
                        <span class="text-base font-bold text-blue-600">{{ __('all.payment_status.promo.price') }}</span> {{ __('all.payment_status.promo.text') }}
                        <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded-lg font-mono font-semibold text-base mx-1">{{ __('all.payment_status.promo.code') }}</span>
                    </p>
                </div>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($halfDiscount)
            <div class="hidden md:flex items-center gap-3 bg-blue-50 rounded-xl p-4">
                <div class="text-blue-600 flex-shrink-0">
                    <x-simpleline-present class="w-6 h-6"/>
                </div>
                <div class="flex flex-col">
                    <p class="font-medium">
                        {{ __('all.payment_status.discount.title' , ['promo_code' => $promoCodeGlobal]) }}
                    </p>
                    <span class="text-sm">
                            {{ __('all.payment_status.discount.desc', ['percent' => $promoCodeGlobalPercent]) }}
                        </span>
                </div>
            </div>
        @endif

        <div class="hidden md:flex items-center justify-between p-6">
            <div class="text-gray-900 text-base md:text-xl font-normal">
                {{ __('all.payment_status.pay') }}
            </div>
            <div class="flex items-center gap-3">
                @if($halfDiscount)
                    <span class="text-gray-400 text-sm md:text-xl line-through font-normal">
                            18,990 {{ $plans[$plan]['currency'] }}
                        </span>
                    <span class="text-gray-900 text-base md:text-xl font-bold">
                            {{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}
                        </span>
                @else
                    <span class="text-gray-900 text-sm md:text-xl font-bold">
                            {{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}
                        </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Payment Confirmation -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <h3 class="text-lg font-medium text-center text-gray-900 mb-6">{{ __('all.payment_status.activation.title') }}</h3>

                @if(!$paymentConfirmed && $testSession->payment_status != 'completed' && !$halfDiscount)
                    <form wire:submit.prevent="confirmPayment">
                        <!-- PIN Input Fields -->
                        <div x-data="{
                            handleInput(event, index) {
                                if (event.target.value.length === 1 && index < 6) {
                                    this.$refs['pin' + (index + 1)]?.focus();
                                }
                            },
                            handleKeydown(event, index) {
                                if (event.key === 'Backspace' && event.target.value === '' && index > 1) {
                                    this.$refs['pin' + (index - 1)]?.focus();
                                }
                            },
                            handlePaste(event) {
                                event.preventDefault();
                                const paste = (event.clipboardData || window.clipboardData).getData('text');
                                const digits = paste.replace(/\D/g, '').slice(0, 6);
                                for (let i = 0; i < digits.length && i < 6; i++) {
                                    this.$refs['pin' + (i + 1)].value = digits[i];
                                    this.$refs['pin' + (i + 1)].dispatchEvent(new Event('input'));
                                }
                                if (digits.length > 0) {
                                    this.$refs['pin' + Math.min(digits.length, 6)]?.focus();
                                }
                            }
                        }">
                            <div class="flex justify-center gap-3 mb-4">
                                <input type="text"
                                       wire:model="pin1"
                                       x-ref="pin1"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @input="handleInput($event, 1)"
                                       @keydown="handleKeydown($event, 1)"
                                       @paste="handlePaste($event)">
                                <input type="text"
                                       wire:model="pin2"
                                       x-ref="pin2"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @input="handleInput($event, 2)"
                                       @keydown="handleKeydown($event, 2)"
                                       @paste="handlePaste($event)">
                                <input type="text"
                                       wire:model="pin3"
                                       x-ref="pin3"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @input="handleInput($event, 3)"
                                       @keydown="handleKeydown($event, 3)"
                                       @paste="handlePaste($event)">
                                <input type="text"
                                       wire:model="pin4"
                                       x-ref="pin4"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @input="handleInput($event, 4)"
                                       @keydown="handleKeydown($event, 4)"
                                       @paste="handlePaste($event)">
                                <input type="text"
                                       wire:model="pin5"
                                       x-ref="pin5"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @input="handleInput($event, 5)"
                                       @keydown="handleKeydown($event, 5)"
                                       @paste="handlePaste($event)">
                                <input type="text"
                                       wire:model="pin6"
                                       x-ref="pin6"
                                       maxlength="1"
                                       class="w-10 h-10 md:w-12 md:h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       @keydown="handleKeydown($event, 6)"
                                       @paste="handlePaste($event)">
                            </div>
                            <div class="text-center">
                                <button type="submit"
                                        class="px-8 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('all.payment_status.activation.btn') }}
                                </button>
                            </div>
                            @error('pin1')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                            @error('pin2')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                            @error('pin3')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                            @error('pin4')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                            @error('pin5')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                            @error('pin6')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No Code Section -->
{{--                        <div class="text-center">--}}
{{--                            <p class="text-gray-600 mb-4">{{ __('all.payment_status.activation.no_code') }}</p>--}}
{{--                            <button type="button"--}}
{{--                                    wire:click="getPromoCode"--}}
{{--                                    class="w-full py-3 px-6 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">--}}
{{--                                {{ __('all.payment_status.activation.get_code') }}--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </form>

                    {{--                @elseif($testSession->payment_status === 'processing')--}}
                    {{--                    <div class="text-center py-8">--}}
                    {{--                        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">--}}
                    {{--                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
                    {{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>--}}
                    {{--                            </svg>--}}
                    {{--                        </div>--}}
                    {{--                        <h4 class="text-lg font-medium text-gray-900 mb-2">Обработка оплаты</h4>--}}
                    {{--                        <p class="text-gray-600 mb-4">Ваша оплата обрабатывается. Пожалуйста, подождите...</p>--}}
                    {{--                        <button wire:click="checkPaymentStatusWithForteBank"--}}
                    {{--                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">--}}
                    {{--                            Проверить статус--}}
                    {{--                        </button>--}}
                    {{--                    </div>--}}

                @elseif($testSession->payment_status === 'review')
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('all.payment_status.review.title') }}</h4>
                        <p class="text-gray-600">{{ __('all.payment_status.review.desc') }}</p>
                    </div>

                @elseif($testSession->payment_status === 'completed')
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('all.payment_status.complete.title') }}</h4>
                        <p class="text-gray-600 mb-4">{{ __('all.payment_status.complete.desc') }}</p>
                        <a href="{{ route('talent-test-results') }}"
                           class="inline-flex items-center px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            {{ __('all.payment_status.complete.btn') }}
                        </a>
                    </div>

                @elseif($testSession->payment_status === 'failed')
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('all.payment_status.fail.title') }}</h4>
                        <p class="text-gray-600 mb-4">{{ __('all.payment_status.fail.desc') }}</p>
                        <button wire:click="processCardPayment"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm mr-2">
                            {{ __('all.payment_status.fail.retry_btn') }}
                        </button>
                        <button wire:click="getPromoCode"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                            {{ __('all.payment_status.fail.contact_btn') }}
                        </button>
                    </div>

                @elseif($halfDiscount)
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('all.payment_status.half_discount.title') }}</h4>
                        <p class="text-gray-600 mb-4">{{ __('all.payment_status.half_discount.desc') }}</p>
                    </div>
                @endif
            </div>

            @if($halfDiscount)
                <div class="flex md:hidden items-center gap-3 bg-blue-50 rounded-xl p-4">
                    <div class="text-blue-600 flex-shrink-0">
                        <x-simpleline-present class="w-6 h-6"/>
                    </div>
                    <div class="flex flex-col">
                        <p class="font-medium">
                            {{ __('all.payment_status.discount.title' , ['promo_code' => $promoCodeGlobal]) }}
                        </p>
                        <span class="text-sm">
                            {{ __('all.payment_status.discount.desc', ['percent' => $promoCodeGlobalPercent]) }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="flex md:hidden items-center justify-between p-6"
                style="margin-top: -25px; margin-bottom: -25px">
                <div class="text-gray-900 text-base md:text-xl font-normal">
                    {{ __('all.payment_status.pay') }}
                </div>
                <div class="flex items-center gap-3">
                    @if($halfDiscount)
                        <span class="text-gray-400 text-sm md:text-xl line-through font-normal">
                            18,990 {{ $plans[$plan]['currency'] }}
                        </span>
                        <span class="text-gray-900 text-base md:text-3xl font-bold">
                            {{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}
                        </span>
                    @else
                        <span class="text-gray-900 text-sm md:text-xl font-bold">
                            {{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Payment Options -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100" x-data="{
                kaspiOpen: false,
                cardOpen: false,
                openKaspi() {
                    this.kaspiOpen = !this.kaspiOpen;
                    if (this.kaspiOpen) this.cardOpen = false;
                },
                openCard() {
                    this.cardOpen = !this.cardOpen;
                    if (this.cardOpen) this.kaspiOpen = false;
                }
            }">
                <!-- Kaspi QR Accordion -->
{{--                <div class="mb-4">--}}
{{--                    <button @click="openKaspi()"--}}
{{--                            class="w-full flex items-center justify-between px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 text-gray-700">--}}
{{--                        <div class="flex items-center">--}}
{{--                            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 4h5.5M12 8V4"></path>--}}
{{--                            </svg>--}}
{{--                            <span class="font-medium">Оплата через Kaspi QR</span>--}}
{{--                        </div>--}}
{{--                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': kaspiOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                    --}}
{{--                    <div x-show="kaspiOpen" --}}
{{--                         x-transition:enter="transition ease-out duration-300"--}}
{{--                         x-transition:enter-start="opacity-0 transform scale-95"--}}
{{--                         x-transition:enter-end="opacity-100 transform scale-100"--}}
{{--                         x-transition:leave="transition ease-in duration-200"--}}
{{--                         x-transition:leave-start="opacity-100 transform scale-100"--}}
{{--                         x-transition:leave-end="opacity-0 transform scale-95"--}}
{{--                         class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">--}}
{{--                        <!-- QR Code -->--}}
{{--                        <div class="text-center mb-4">--}}
{{--                            <h4 class="text-base font-medium text-gray-900 mb-3">QR-код для оплаты</h4>--}}
{{--                            <div class="flex justify-center mb-3">--}}
{{--                                <img src="{{ asset('assets/images/kaspi-qr.png') }}"--}}
{{--                                     alt="Kaspi QR Code"--}}
{{--                                     class="w-40 h-40 rounded-lg border">--}}
{{--                            </div>--}}
{{--                            <p class="text-sm text-gray-600 mb-4">Сканируйте QR-код в приложении Kaspi</p>--}}
{{--                        </div>--}}

{{--                        <!-- Divider -->--}}
{{--                        <div class="relative mb-4">--}}
{{--                            <div class="absolute inset-0 flex items-center">--}}
{{--                                <div class="w-full border-t border-gray-300"></div>--}}
{{--                            </div>--}}
{{--                            <div class="relative flex justify-center text-xs">--}}
{{--                                <span class="px-2 bg-gray-50 text-gray-500">или</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <!-- Payment Link -->--}}
{{--                        <a href="https://pay.kaspi.kz/pay/d9gpkmul"--}}
{{--                           target="_blank"--}}
{{--                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium text-sm">--}}
{{--                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>--}}
{{--                            </svg>--}}
{{--                            Перейти к оплате--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- Card Payment Accordion -->
                <div>
{{--                    <button @click="openCard()"--}}
{{--                            class="w-full flex items-center justify-between px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 text-gray-700">--}}
{{--                        <div class="flex items-center">--}}
{{--                            <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>--}}
{{--                            </svg>--}}
{{--                            <span class="font-medium">Оплата банковской картой</span>--}}
{{--                        </div>--}}
{{--                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': cardOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
{{--                        </svg>--}}
{{--                    </button>--}}

                    <div
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="rounded-lg">

                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">{{ __('all.payment_status.bank_card.title') }}</p>
                            <button wire:click="processCardPayment"
                                    class="w-full py-2.5 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                {{ __('all.payment_status.bank_card.pay') }} {{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-center space-x-3">
                            <img src="{{ asset('assets/images/visa.webp') }}" alt="Visa" class="h-5 opacity-60">
                            <img src="{{ asset('assets/images/mastercard.jpg') }}" alt="Mastercard" class="h-5 opacity-60">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="py-4 md:py-8 bg-white mb-8">
            <div class="mx-auto max-w-7xl text-blue-700 space-y-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="mt-2 text-lg md:text-2xl font-bold sm:text-4xl xl:text-4xl text-gray-900 uppercase">{{ __('all.home.middle.accordion_4.title') }}</h2>
                </div>
                <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-8 md:flex align-middle items-center">
                    <div class="w-full">
                        <div class="text-gray-600 text-sm md:text-lg">
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

        <section class="py-4 md:py-8 bg-white mb-8">
            <div class="mx-auto max-w-7xl text-blue-700 space-y-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="mt-2 text-lg md:text-2xl font-bold sm:text-4xl xl:text-4xl text-gray-900 uppercase">{{ __('all.payment_status.bottom.title') }}</h2>
                </div>
                <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-8 md:flex align-middle items-center">
                    <div class="w-full">
                        <div class="text-gray-600 text-sm md:text-lg">
                            <ul>
                                <li class="flex items-center gap-3 mb-2">
                                    <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span>{{ __('all.payment_status.bottom.desc_1') }}</span>
                                </li>
                                <li class="flex items-center gap-3 mb-2">
                                    <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span>{{ __('all.payment_status.bottom.desc_2') }}</span>
                                </li>
                                <li class="flex items-center gap-3 mb-2">
                                    <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span>{{ __('all.payment_status.bottom.desc_3') }}</span>
                                </li>
                                <li class="flex items-center gap-3 mb-2">
                                    <div class="flex-shrink-0 w-6 h-6 mt-0.5 bg-blue-50 rounded-md flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span>{{ __('all.payment_status.bottom.desc_4') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 md:py-8 bg-white mb-8" x-data="{ openItem: null }">
            <div class="mx-auto max-w-7xl text-blue-700">
                <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
                    <div class="mx-auto max-w-2xl text-center">
                        <h2 class="mt-2 text-lg md:text-2xl font-bold text-gray-900">
                            {{ __('all.payment_status.faq.title') }}
                        </h2>
                    </div>

                    <div class="w-full max-w-3xl mx-auto space-y-0 divide-y divide-gray-200">
                        <!-- Accordion item 1 -->
                        <div class="bg-white">
                            <button
                                @click="openItem === 1 ? openItem = null : openItem = 1"
                                class="flex justify-between items-center w-full py-5 text-left group hover:bg-gray-50 transition-colors">
                            <span class="text-gray-900 font-normal text-sm md:text-lg pr-4">
                                {{ __('all.payment_status.faq.accordion_1.title') }}
                            </span>
                                <svg
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                    :class="openItem === 1 ? 'rotate-90' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div
                                x-show="openItem === 1"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="overflow-hidden pb-5 text-gray-600 text-sm md:text-base leading-relaxed pr-10"
                            >
                                {{ __('all.payment_status.faq.accordion_1.desc') }}
                            </div>
                        </div>

                        <!-- Accordion item 2 -->
                        <div class="bg-white">
                            <button
                                @click="openItem === 2 ? openItem = null : openItem = 2"
                                class="flex justify-between items-center w-full py-5 text-left group hover:bg-gray-50 transition-colors">
                            <span class="text-gray-900 font-normal text-sm md:text-lg pr-4">
                                {{ __('all.payment_status.faq.accordion_2.title') }}
                            </span>
                                <svg
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                    :class="openItem === 2 ? 'rotate-90' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div
                                x-show="openItem === 2"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="overflow-hidden pb-5 text-gray-600 text-sm md:text-base leading-relaxed pr-10">
                                {{ __('all.payment_status.faq.accordion_2.desc') }}
                            </div>
                        </div>

                        <!-- Accordion item 3 -->
                        <div class="bg-white">
                            <button
                                @click="openItem === 3 ? openItem = null : openItem = 3"
                                class="flex justify-between items-center w-full py-5 text-left group hover:bg-gray-50 transition-colors">
                            <span class="text-gray-900 font-normal text-sm md:text-lg pr-4">
                                {{ __('all.payment_status.faq.accordion_3.title') }}
                            </span>
                                <svg
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                    :class="openItem === 3 ? 'rotate-90' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div
                                x-show="openItem === 3"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="overflow-hidden pb-5 text-gray-600 text-sm md:text-base leading-relaxed pr-10">
                                {{ __('all.payment_status.faq.accordion_3.desc') }}
                            </div>
                        </div>

                        <!-- Accordion item 4 -->
                        <div class="bg-white">
                            <button
                                @click="openItem === 4 ? openItem = null : openItem = 4"
                                class="flex justify-between items-center w-full py-5 text-left group hover:bg-gray-50 transition-colors">
                            <span class="text-gray-900 font-normal text-sm md:text-lg pr-4">
                                {{ __('all.payment_status.faq.accordion_4.title') }}
                            </span>
                                <svg
                                    class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                                    :class="openItem === 4 ? 'rotate-90' : ''"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div
                                x-show="openItem === 4"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="overflow-hidden pb-5 text-gray-600 text-sm md:text-base leading-relaxed pr-10">
                                {{ __('all.payment_status.faq.accordion_4.desc') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('profile') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-light text-sm md:text-base transition-colors">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('all.payment_status.back_btn') }}
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openWhatsApp', (url) => {
                window.open(url, '_blank');
            });
        });
    </script>
</div>
