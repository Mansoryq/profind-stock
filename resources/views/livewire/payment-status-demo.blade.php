<div class="min-h-screen bg-gray-50 py-8 md:py-16 px-4" x-data="{ showPromoForm: @entangle('showPromoCodeForm') }">
    <div class="max-w-4xl mx-auto">

        <!-- Status Header -->
        <div class="text-center mb-8 md:mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6 {{ $this->getStatusColor() }}">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ $this->getStatusText() }}
            </div>

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 mb-4">Оплата тарифа</h1>
            <h2 class="text-xl md:text-2xl font-medium text-gray-800 mb-2">{{ $plans[$plan]['name'] }}</h2>
            <p class="text-2xl md:text-3xl font-light text-gray-900">{{ number_format($plans[$plan]['price']) }} {{ $plans[$plan]['currency'] }}</p>
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

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-blue-800 font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Payment Options -->
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-medium text-gray-900 mb-6">Способы оплаты</h3>

                <!-- QR Code -->
                <div class="text-center mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">QR-код для оплаты</h4>
                    <div class="flex justify-center mb-4">
                        <div class="w-48 h-48 rounded-xl bg-gray-100 flex items-center justify-center">
                            <div class="text-center">
                                <img src="{{ asset('assets/images/kaspi-qr.png') }}" alt="Kaspi QR Code" class="w-48 h-48 rounded-xl border">
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Сканируйте QR-код в приложении Kaspi</p>
                </div>

                <!-- Divider -->
                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">или</span>
                    </div>
                </div>

                <!-- Payment Link -->
                <button onclick="alert('Это демо-версия. Для реальной оплаты пройдите тест талантов.')"
                        class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 text-lg font-medium">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Перейти к оплате в Kaspi
                </button>
            </div>

            <!-- Payment Confirmation -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <h3 class="text-xl font-medium text-gray-900 mb-6">Получите доступ используя код активации</h3>

                <form wire:submit.prevent="confirmPayment">
                    <!-- PIN Input Fields -->
                    <div class="mb-6" x-data="{
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
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @input="handleInput($event, 1)"
                                   @keydown="handleKeydown($event, 1)"
                                   @paste="handlePaste($event)">
                            <input type="text"
                                   wire:model="pin2"
                                   x-ref="pin2"
                                   maxlength="1"
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @input="handleInput($event, 2)"
                                   @keydown="handleKeydown($event, 2)"
                                   @paste="handlePaste($event)">
                            <input type="text"
                                   wire:model="pin3"
                                   x-ref="pin3"
                                   maxlength="1"
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @input="handleInput($event, 3)"
                                   @keydown="handleKeydown($event, 3)"
                                   @paste="handlePaste($event)">
                            <input type="text"
                                   wire:model="pin4"
                                   x-ref="pin4"
                                   maxlength="1"
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @input="handleInput($event, 4)"
                                   @keydown="handleKeydown($event, 4)"
                                   @paste="handlePaste($event)">
                            <input type="text"
                                   wire:model="pin5"
                                   x-ref="pin5"
                                   maxlength="1"
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @input="handleInput($event, 5)"
                                   @keydown="handleKeydown($event, 5)"
                                   @paste="handlePaste($event)">
                            <input type="text"
                                   wire:model="pin6"
                                   x-ref="pin6"
                                   maxlength="1"
                                   class="w-12 h-12 text-center text-lg font-medium border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   @keydown="handleKeydown($event, 6)"
                                   @paste="handlePaste($event)">
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                    class="px-8 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors font-medium">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Подтвердить
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
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">Нет кода активации?</p>
                        <button type="button"
                                wire:click="getPromoCode"
                                class="w-full py-3 px-6 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Получить код у менеджера
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-light text-sm md:text-base transition-colors">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Назад на главную
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