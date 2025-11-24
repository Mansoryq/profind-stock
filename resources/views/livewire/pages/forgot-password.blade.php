<div class="flex items-center justify-center bg-gray-50 py-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-light text-gray-900">
                Восстановление пароля
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Введите email для получения инструкций
            </p>
        </div>

        @if($sent)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-green-800">
                        Инструкции отправлены на email
                    </p>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    ← Вернуться к входу
                </a>
            </div>
        @else
            <form wire:submit.prevent="sendResetLink" class="space-y-4">
                <div>
                    <input wire:model="email" 
                           type="email" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ваш email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm font-medium">
                    Отправить
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Вернуться к входу
                </a>
            </div>
        @endif
    </div>
</div>
