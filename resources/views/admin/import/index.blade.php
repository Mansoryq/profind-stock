<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Импорт Google Таблиц - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .btn-primary {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200;
        }
        .btn-secondary {
            @apply bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-medium px-4 py-2 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200;
        }
        .btn-success {
            @apply bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200;
        }
        .card {
            @apply bg-white rounded-xl shadow-lg border border-gray-100 backdrop-blur-sm;
        }
        .card-gradient {
            @apply bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-200 backdrop-blur-sm;
        }
        .input-modern {
            @apply w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
        }
        .status-success {
            @apply bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm;
        }
        .status-error {
            @apply bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-4 shadow-sm;
        }
        .info-card {
            @apply bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 shadow-sm;
        }
        .animate-pulse-soft {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen py-6">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Modern Header with Status -->
            <div class="card-gradient mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                📊 Импорт Google Таблиц
                            </h1>
                            <p class="text-gray-600 mt-1">Импорт данных из Google Таблиц в базу данных</p>
                        </div>
                        <button onclick="testConnection()" class="btn-primary">
                            <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                            </svg>
                            Проверить соединение
                        </button>
                    </div>
                    
                    <!-- Modern Connection Status -->
                    @if($connectionStatus)
                        <div class="status-success">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-green-700">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-base font-semibold text-green-800">Подключено к Google Таблицам</span>
                                        <div class="flex items-center mt-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse-soft"></div>
                                            <span class="text-sm text-green-600">Активное соединение</span>
                                        </div>
                                    </div>
                                </div>
                                @if($spreadsheetInfo)
                                    <div class="text-right">
                                        <div class="text-base font-semibold text-green-900">{{ $spreadsheetInfo['title'] }}</div>
                                        <div class="text-sm text-green-700 flex items-center justify-end mt-1">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Доступно листов: {{ count($spreadsheetInfo['sheets']) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="status-error">
                            <div class="flex items-center text-red-700">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-base font-semibold text-red-800">Ошибка подключения</span>
                                    <div class="flex items-center mt-1">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-sm text-red-600">Соединение не установлено</span>
                                    </div>
                                </div>
                            </div>
                            @if(isset($error))
                                <div class="mt-3 p-3 bg-red-100 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-800">{{ $error }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modern Import Form -->
            @if($connectionStatus)
                <div x-data="importForm()" class="card-gradient">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Настройка импорта</h2>
                            </div>
                            <div x-show="selectedSheet" class="flex gap-3">
                                <button type="button" @click="previewData()" class="btn-secondary">
                                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Предпросмотр
                                </button>
                                <button @click="startImport()" :disabled="importing" class="btn-success disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!importing" class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div x-show="importing" class="inline-block w-4 h-4 mr-2 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    <span x-show="!importing">Импорт</span>
                                    <span x-show="importing">Импортируется...</span>
                                </button>
                            </div>
                        </div>
                        
                        <form @submit.prevent="startImport()" class="space-y-6">
                            <!-- Modern Grid Layout -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Model Selection -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Модель данных
                                    </label>
                                    <select x-model="selectedModel" @change="modelChanged()" class="input-modern">
                                        <option value="">Выберите модель...</option>
                                        @foreach($availableModels as $key => $model)
                                            <option value="{{ $key }}">{{ $model['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sheet Selection -->
                                <div x-show="selectedModel" class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"></path>
                                        </svg>
                                        Лист таблицы
                                    </label>
                                    <select x-model="selectedSheet" @change="sheetChanged()" class="input-modern">
                                        <option value="">Выберите лист...</option>
                                        <template x-for="sheet in availableSheets" :key="sheet">
                                            <option :value="sheet" x-text="sheet"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Range Input -->
                                <div x-show="selectedSheet" class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Диапазон (необязательно)
                                    </label>
                                    <input x-model="range" type="text" placeholder="например, A1:Z100" class="input-modern">
                                    <p class="text-xs text-gray-500">Оставьте пустым для импорта всех данных</p>
                                </div>
                            </div>

                            <!-- Modern Model Info -->
                            <div x-show="selectedModel" class="info-card">
                                <template x-for="(model, key) in availableModels" :key="key">
                                    <div x-show="selectedModel === key">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold text-blue-900" x-text="model.name"></h3>
                                                    <p class="text-sm text-blue-600" x-text="model.description"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-800">Обязательные поля:</span>
                                                </div>
                                                <p class="text-sm text-gray-700" x-text="model.required_columns.join(', ')"></p>
                                            </div>
                                            <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-800">Необязательные поля:</span>
                                                </div>
                                                <p class="text-sm text-gray-700" x-text="model.optional_columns.join(', ')"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Modern Options -->
                            <div x-show="selectedSheet" class="bg-white bg-opacity-60 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Настройки импорта
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="flex items-center p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:border-gray-300 cursor-pointer transition-all duration-200">
                                        <input x-model="dryRun" type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 mr-3">
                                        <div>
                                            <span class="font-medium text-gray-800">Только тест</span>
                                            <p class="text-xs text-gray-600">Предварительный просмотр без импорта</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:border-gray-300 cursor-pointer transition-all duration-200">
                                        <input x-model="truncate" type="checkbox" class="w-4 h-4 text-red-600 rounded focus:ring-red-500 mr-3">
                                        <div>
                                            <span class="font-medium text-gray-800">Очистить таблицу</span>
                                            <p class="text-xs text-gray-600">Удалить существующие данные</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:border-gray-300 cursor-pointer transition-all duration-200">
                                        <input x-model="updateMode" type="checkbox" class="w-4 h-4 text-yellow-600 rounded focus:ring-yellow-500 mr-3">
                                        <div>
                                            <span class="font-medium text-gray-800">Режим обновления</span>
                                            <p class="text-xs text-gray-600">Обновить существующие записи</p>
                                        </div>
                                    </label>
                                </div>
                                <div x-show="updateMode" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <template x-for="(model, key) in availableModels" :key="key">
                                        <div x-show="selectedModel === key && model.unique_fields" class="text-sm">
                                            <span class="font-medium text-yellow-800">Обновление по полям:</span>
                                            <span class="text-yellow-700" x-text="model.unique_fields.join(', ')"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modern Preview Section -->
                    <div x-show="previewVisible" class="border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Предпросмотр данных</h3>
                                </div>
                                <button @click="previewVisible = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <div x-show="previewData.length > 0" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                                            <tr>
                                                <template x-for="column in previewColumns" :key="column">
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-300 last:border-r-0" x-text="column"></th>
                                                </template>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <template x-for="(row, index) in previewData.slice(0, 5)" :key="index">
                                                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                                    <template x-for="column in previewColumns" :key="column">
                                                        <td class="px-4 py-3 text-sm text-gray-900 max-w-32 truncate border-r border-gray-200 last:border-r-0" x-text="row[column] || ''"></td>
                                                    </template>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div x-show="previewData.length > 5" class="px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Показано 5 из <span class="font-semibold" x-text="previewData.length"></span> строк
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Results Section -->
                    <div x-show="results" class="border-t border-gray-200 bg-gray-50">
                        <div class="p-4">
                            <h3 class="text-base font-medium text-gray-900 mb-3">Результаты импорта</h3>
                            
                            <!-- Success/Error Message -->
                            <div x-show="results && results.success" class="bg-green-100 border border-green-300 rounded p-3 mb-3">
                                <div class="flex items-center text-green-800 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span x-text="results.message"></span>
                                </div>
                            </div>

                            <div x-show="results && !results.success" class="bg-red-100 border border-red-300 rounded p-3 mb-3">
                                <div class="flex items-center text-red-800 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span x-text="results.message"></span>
                                </div>
                            </div>

                            <!-- Compact Statistics -->
                            <div x-show="results && results.stats" class="grid grid-cols-4 gap-3 mb-3">
                                <div class="bg-white rounded p-2 text-center border">
                                    <div class="text-lg font-bold text-gray-900" x-text="results.stats.total_rows || 0"></div>
                                    <div class="text-xs text-gray-600">Всего</div>
                                </div>
                                <div class="bg-white rounded p-2 text-center border border-green-200">
                                    <div class="text-lg font-bold text-green-600" x-text="results.stats.imported_rows || 0"></div>
                                    <div class="text-xs text-gray-600">Импортировано</div>
                                </div>
                                <div class="bg-white rounded p-2 text-center border border-yellow-200">
                                    <div class="text-lg font-bold text-yellow-600" x-text="results.stats.warning_count || 0"></div>
                                    <div class="text-xs text-gray-600">Предупреждения</div>
                                </div>
                                <div class="bg-white rounded p-2 text-center border border-red-200">
                                    <div class="text-lg font-bold text-red-600" x-text="results.stats.error_count || 0"></div>
                                    <div class="text-xs text-gray-600">Ошибки</div>
                                </div>
                            </div>

                            <!-- Collapsible Errors and Warnings -->
                            <div x-show="results && results.errors && results.errors.length > 0" class="mb-3">
                                <div x-data="{ expanded: false }" class="bg-white border border-red-200 rounded">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-2 text-left">
                                        <span class="text-sm font-medium text-red-900">Ошибки (<span x-text="results.errors.length"></span>)</span>
                                        <svg class="w-4 h-4 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <div x-show="expanded" class="border-t border-red-200 p-2 bg-red-50 max-h-32 overflow-y-auto">
                                        <template x-for="error in results.errors" :key="error">
                                            <div class="text-xs text-red-800 mb-1" x-text="error"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div x-show="results && results.warnings && results.warnings.length > 0">
                                <div x-data="{ expanded: false }" class="bg-white border border-yellow-200 rounded">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-2 text-left">
                                        <span class="text-sm font-medium text-yellow-900">Предупреждения (<span x-text="results.warnings.length"></span>)</span>
                                        <svg class="w-4 h-4 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <div x-show="expanded" class="border-t border-yellow-200 p-2 bg-yellow-50 max-h-32 overflow-y-auto">
                                        <template x-for="warning in results.warnings" :key="warning">
                                            <div class="text-xs text-yellow-800 mb-1" x-text="warning"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Available models data
        const availableModels = @json($availableModels);
        
        function importForm() {
            return {
                selectedModel: '',
                selectedSheet: '',
                range: '',
                dryRun: false,
                truncate: false,
                updateMode: false,
                importing: false,
                availableSheets: @json($spreadsheetInfo['sheets'] ?? []),
                previewVisible: false,
                previewData: [],
                previewColumns: [],
                results: null,
                availableModels: availableModels,

                modelChanged() {
                    this.selectedSheet = '';
                    this.range = '';
                    this.previewVisible = false;
                    this.results = null;
                    this.updateMode = false;
                },

                sheetChanged() {
                    this.range = '';
                    this.previewVisible = false;
                    this.results = null;
                },

                async previewData() {
                    try {
                        const response = await fetch('{{ route("admin.import.preview") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                sheet: this.selectedSheet,
                                range: this.range
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.previewData = data.data;
                            this.previewColumns = data.columns;
                            this.previewVisible = true;
                        } else {
                            alert('Ошибка предпросмотра: ' + data.message);
                        }
                    } catch (error) {
                        alert('Ошибка предпросмотра: ' + error.message);
                    }
                },

                async startImport() {
                    if (!this.selectedModel || !this.selectedSheet) {
                        alert('Пожалуйста, выберите модель и лист');
                        return;
                    }

                    this.importing = true;
                    this.results = null;

                    try {
                        const response = await fetch('{{ route("admin.import.import-data") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                model: this.selectedModel,
                                sheet: this.selectedSheet,
                                range: this.range,
                                dry_run: this.dryRun,
                                truncate: this.truncate,
                                update_mode: this.updateMode,
                                unique_fields: this.getUniqueFields(),
                                update_fields: this.getUpdateFields()
                            })
                        });

                        const data = await response.json();
                        this.results = data;
                        
                    } catch (error) {
                        this.results = {
                            success: false,
                            message: 'Ошибка импорта: ' + error.message,
                            stats: { error_count: 1 }
                        };
                    } finally {
                        this.importing = false;
                    }
                },

                getUniqueFields() {
                    if (!this.selectedModel || !this.availableModels[this.selectedModel]) {
                        return [];
                    }
                    return this.availableModels[this.selectedModel].unique_fields || [];
                },

                getUpdateFields() {
                    if (!this.selectedModel || !this.availableModels[this.selectedModel]) {
                        return [];
                    }
                    return this.availableModels[this.selectedModel].update_fields || [];
                },
            }
        }

        async function testConnection() {
            try {
                const response = await fetch('{{ route("admin.import.test-connection") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Соединение установлено успешно!');
                    location.reload();
                } else {
                    alert('Ошибка соединения: ' + data.message);
                }
            } catch (error) {
                alert('Ошибка тестирования соединения: ' + error.message);
            }
        }
    </script>
</body>
</html>
