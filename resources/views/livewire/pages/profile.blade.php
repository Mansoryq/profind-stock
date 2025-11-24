<div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-3xl font-light text-gray-900 mb-2">
                {{ __('all.profile.title') }}
            </h1>
            <p class="text-gray-500">
                {{ __('all.profile.desc') }}
            </p>
        </div>

        <!-- Profile Update Section -->
        <div class="mb-12">
            <h2 class="text-xl font-light text-gray-900 mb-8">{{ __('all.profile.info.title') }}</h2>

            @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 border-l-2 border-green-400 rounded-r">
                <p class="text-green-700 text-sm">
                    {{ session('message') }}
                </p>
            </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.info.name') }}</label>
                        <input wire:model="name"
                               type="text"
                               id="name"
                               name="name"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.info.name_placeholder') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input wire:model="email"
                               type="email"
                               id="email"
                               name="email"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.info.email_placeholder') }}">
                        @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($isPupil)
                    <div>
                        <label for="school" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.info.school') }}</label>
                        <input wire:model="school"
                               type="text"
                               id="school"
                               name="school"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.info.school_placeholder') }}">
                        @error('school')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    @if($isPupil)
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.info.class') }}</label>
                        <input wire:model="class"
                               type="text"
                               id="class"
                               name="class"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.info.class_placeholder') }}">
                        @error('class')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors duration-200">
                        {{ __('all.profile.save_btn') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Update Section -->
        <div class="mb-12">
            <h2 class="text-xl font-light text-gray-900 mb-8">{{ __('all.profile.password.title') }}</h2>

            @if (session()->has('password_message'))
            <div class="mb-6 p-4 bg-green-50 border-l-2 border-green-400 rounded-r">
                <p class="text-green-700 text-sm">
                    {{ session('password_message') }}
                </p>
            </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.password.current_password') }}</label>
                    <input wire:model="current_password"
                           type="password"
                           id="current_password"
                           name="current_password"
                           class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                           placeholder="{{ __('all.profile.password.current_password_placeholder') }}">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.password.new_password') }}</label>
                        <input wire:model="password"
                               type="password"
                               id="password"
                               name="password"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.password.new_password_placeholder') }}">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('all.profile.password.password_confirm') }}</label>
                        <input wire:model="password_confirmation"
                               type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="w-full px-0 py-3 text-gray-900 border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 bg-transparent placeholder-gray-400 transition-colors duration-200"
                               placeholder="{{ __('all.profile.password.password_confirm_placeholder') }}">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors duration-200">
                        {{ __('all.profile.password.save_btn') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Activity Section -->
        <div class="mb-12">
            <h2 class="text-xl font-light text-gray-900 mb-8">{{ __('all.profile.activity.title') }}</h2>
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-1">{{ __('all.profile.activity.last_enter') }}</p>
                        <p class="text-sm text-gray-500">{{ now()->format('d.m.Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-1">{{ __('all.profile.activity.ip') }}</p>
                        <p class="text-sm text-gray-500">{{ request()->ip() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="border-t border-red-100 pt-8">
            <h2 class="text-xl font-light text-red-600 mb-8">{{ __('all.profile.delete.title') }}</h2>
            <div class="bg-red-50 border border-red-100 rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-red-800 mb-1">{{ __('all.profile.delete.desc_1') }}</h3>
                        <p class="text-sm text-red-600">
                            {{ __('all.profile.delete.desc_2') }}
                        </p>
                    </div>
                    <button type="button"
                            wire:click="confirmDeleteAccount"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-white hover:bg-red-50 border border-red-200 hover:border-red-300 rounded-lg transition-colors duration-200">
                        {{ __('all.profile.delete.btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.4);" wire:click="cancelDeleteAccount">
        <div class="relative w-full max-w-sm mx-auto" wire:click.stop>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('all.profile.delete.modal.title') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('all.profile.delete.modal.desc') }}</p>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label for="deletePassword" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('all.profile.delete.modal.pass_confirm') }}
                        </label>
                        <input wire:model="deletePassword"
                               type="password"
                               id="deletePassword"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500"
                               placeholder="{{ __('all.profile.delete.modal.pass_confirm_placeholder') }}">
                        @if($errors->has('deletePassword'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('deletePassword') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 flex gap-3">
                    <button wire:click="cancelDeleteAccount"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-500">
                        {{ __('all.profile.delete.modal.cancel_btn') }}
                    </button>
                    <button wire:click="deleteAccount"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-1 focus:ring-red-500">
                        {{ __('all.profile.delete.modal.delete_btn') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
