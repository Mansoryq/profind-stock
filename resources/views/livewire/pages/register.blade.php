<div class="flex items-center justify-center bg-gray-50 py-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{ __('all.registration.title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('all.registration.desc_or') }}
                <a href="{{ route('login') }}" class="font-medium text-[#ff8b0d] hover:text-[#e67d0c]">
                    {{ __('all.registration.desc') }}
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="register">
            <div class="space-y-2">
                <div class="flex gap-2 justify-center" x-data="{ is_pupil: @entangle('is_pupil') }">
                    <button
                        type="button"
                        @click="is_pupil = 1"
                        class="px-4 py-2 text-sm rounded-lg transition-colors"
                        :class="is_pupil == 1 ? 'bg-blue-700 text-white' : 'bg-gray-200 text-gray-700'">
                        {{ __('all.registration.fields.pupil') }}
                    </button>

                    <button
                        type="button"
                        @click="is_pupil = 0"
                        class="px-4 py-2 text-sm rounded-lg transition-colors"
                        :class="is_pupil == 0 ? 'bg-blue-700 text-white' : 'bg-gray-200 text-gray-700'">
                        {{ __('all.registration.fields.not_pupil') }}
                    </button>
                </div>
                <div>
                    <label for="name" class="sr-only">{{ __('all.registration.fields.name') }}</label>
                    <input wire:model="name" id="name" name="name" type="text" autocomplete="name" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                        placeholder="{{ __('all.registration.fields.name') }}">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="surname" class="sr-only">{{ __('all.registration.fields.surname') }}</label>
                    <input wire:model="surname" id="surname" name="surname" type="text" autocomplete="surname" required
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                           placeholder="{{ __('all.registration.fields.surname') }}">
                    @error('surname')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="email-address" class="sr-only">{{ __('all.registration.fields.email') }}</label>
                    <input wire:model="email" id="email-address" name="email" type="email" autocomplete="email"
                           required
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                           placeholder="{{ __('all.registration.fields.email') }}">
                    @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="sr-only">{{ __('all.registration.fields.phone') }}</label>
                    <input wire:model.defer="phone"
                           id="phone"
                           name="phone"
                           type="text"
                           autocomplete="phone"
                           required
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                           placeholder="+7 (___) ___-__-__">
                    @error('phone')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div x-data="{ is_pupil: @entangle('is_pupil') }" class="space-y-2">
                    <div x-show="is_pupil == 1" x-transition>
                        <label for="school" class="sr-only">{{ __('all.registration.fields.school') }}</label>
                        <input wire:model="school" id="school" name="school" type="text" autocomplete="school"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                               placeholder="{{ __('all.registration.fields.school') }}">
                        @error('school')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-show="is_pupil == 1" x-transition>
                        <label for="class" class="sr-only">{{ __('all.registration.fields.class') }}</label>
                        <input wire:model="class" id="class" name="class" type="text" autocomplete="class"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                               placeholder="{{ __('all.registration.fields.class') }}">
                        @error('class')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="gender" class="sr-only">{{ __('all.registration.fields.gender') }}</label>
                    <select wire:model="gender" id="gender" name="gender" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm">
                        <option value="" disabled>{{ __('all.registration.fields.gender') }}</option>
                        <option value="male">{{ __('all.registration.fields.male') }}</option>
                        <option value="female">{{ __('all.registration.fields.female') }}</option>
                    </select>
                    @error('gender')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="sr-only">{{ __('all.registration.fields.password') }}</label>
                    <input wire:model="password" id="password" name="password" type="password"
                        autocomplete="new-password" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                        placeholder="{{ __('all.registration.fields.password') }}">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">{{ __('all.registration.fields.password_confirm') }}</label>
                    <input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation"
                        type="password" autocomplete="new-password" required
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#ff8b0d] focus:border-[#ff8b0d] focus:z-10 sm:text-sm"
                        placeholder="{{ __('all.registration.fields.password_confirm') }}">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('all.registration.btn') }}
                </button>
            </div>
        </form>

                <!-- Google Login Button -->
{{--        <div class="mt-4">--}}
{{--            <div class="relative">--}}
{{--                <div class="absolute inset-0 flex items-center">--}}
{{--                    <div class="w-full border-t border-gray-300"></div>--}}
{{--                </div>--}}
{{--                <div class="relative flex justify-center text-sm">--}}
{{--                    <span class="px-2 bg-gray-50 text-gray-500">{{ __('all.registration.or_sign_in') }}</span>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="mt-6">--}}
{{--                <a href="{{ route('auth.google') }}"--}}
{{--                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">--}}
{{--                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                        <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">--}}
{{--                            <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z" />--}}
{{--                            <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z" />--}}
{{--                            <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z" />--}}
{{--                            <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z" />--}}
{{--                        </g>--}}
{{--                    </svg>--}}
{{--                    {{ __('all.registration.google_auth') }}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone');

        phoneInput.value = '+7 ';

        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value;
            let numbers = value.replace(/\D/g, '');

            // Ensure it starts with 7
            if (numbers[0] !== '7') {
                numbers = '7' + numbers;
            }

            // Limit to 11 digits (7 + 10 digits)
            numbers = numbers.substring(0, 11);

            // Format the number
            let formatted = '+7';

            if (numbers.length > 1) {
                formatted += ' (' + numbers.substring(1, 4);
            }
            if (numbers.length >= 5) {
                formatted += ') ' + numbers.substring(4, 7);
            }
            if (numbers.length >= 8) {
                formatted += '-' + numbers.substring(7, 9);
            }
            if (numbers.length >= 10) {
                formatted += '-' + numbers.substring(9, 11);
            }

            e.target.value = formatted;
        });

        phoneInput.addEventListener('keydown', function(e) {
            // Prevent deleting +7 prefix
            if (e.key === 'Backspace' && e.target.selectionStart <= 3) {
                e.preventDefault();
            }
        });

        phoneInput.addEventListener('focus', function(e) {
            if (e.target.value === '') {
                e.target.value = '+7 ';
            }
        });
    });
</script>

