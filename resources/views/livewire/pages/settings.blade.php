<div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-3xl font-light text-gray-900 mb-2">
                {{ __('messages.settings_title') }}
            </h1>
            <p class="text-gray-500">
                {{ __('messages.settings_desc') }}
            </p>
        </div>

        @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 border-l-2 border-green-400 rounded-r">
            <p class="text-green-700 text-sm">
                {{ session('message') }}
            </p>
        </div>
        @endif

        <form wire:submit.prevent="updateSettings" class="space-y-12">
            <!-- Notifications Settings -->
            <div>
                <h2 class="text-xl font-light text-gray-900 mb-6">{{ __('messages.notifications') }}</h2>
                
                <div class="space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input wire:model="notifications_email" 
                               id="notifications_email" 
                               name="notifications_email" 
                               type="checkbox" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="text-sm text-gray-700">{{ __('messages.notifications_email') }}</span>
                    </label>
                    
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input wire:model="notifications_app" 
                               id="notifications_app" 
                               name="notifications_app" 
                               type="checkbox" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="text-sm text-gray-700">{{ __('messages.notifications_app') }}</span>
                    </label>
                </div>
            </div>

            <!-- Appearance Settings -->
            <div>
                <h2 class="text-xl font-light text-gray-900 mb-6">{{ __('messages.appearance') }}</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">{{ __('messages.theme') }}</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                            <input type="radio" 
                                   name="theme" 
                                   value="light" 
                                   wire:model="theme" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-900">{{ __('messages.theme_light') }}</span>
                        </label>

                        <label class="relative bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                            <input type="radio" 
                                   name="theme" 
                                   value="dark" 
                                   wire:model="theme" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-900">{{ __('messages.theme_dark') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-6 border-t border-gray-100">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors duration-200">
                    {{ __('messages.settings_save') }}
                </button>
            </div>
        </form>
    </div>
</div>
