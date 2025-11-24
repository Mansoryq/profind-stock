<div class="min-h-screen bg-gray-50 py-8">
    <style>
        .slider::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .slider::-webkit-slider-track {
            background: #e5e7eb;
            height: 8px;
            border-radius: 4px;
        }

        .slider::-moz-range-track {
            background: #e5e7eb;
            height: 8px;
            border-radius: 4px;
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('all.grant_analyze.title') }}
            </h1>
            <p class="text-xl text-gray-600">
                {{ __('all.grant_analyze.desc') }}
            </p>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ __('all.grant_analyze.filters.title') }}</h2>

            <!-- Subject Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ __('all.grant_analyze.filters.choose') }}
                </label>

                <!-- Individual Subjects -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($availableSubjects as $subject)
                        @if(!in_array($subject->name, ['Ауыл квотасы', 'Көпбалалы отбасы', 'Толық емес отбасы', 'Серпін', 'Мүгедектігі бар адамдар', 'Мүгедектігі бар отбасынан', 'Жетім балалар', 'Дерексіз дұрыс енгіздім']))
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedSubjects"
                                    value="{{ $subject->name }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Social Benefits Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ __('all.grant_analyze.filters.discount') }}
                </label>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($availableSubjects as $subject)
                        @if(in_array($subject->name, ['Ауыл квотасы', 'Көпбалалы отбасы', 'Толық емес отбасы', 'Серпін', 'Мүгедектігі бар адамдар', 'Мүгедектігі бар отбасынан', 'Жетім балалар', 'Дерексіз дұрыс енгіздім']))
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedSubjects"
                                    value="{{ $subject->name }}"
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Score Range Slider -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ __('all.grant_analyze.filters.score') }}<span class="text-blue-600 font-semibold">{{ $minScore }}</span>
                </label>
                <div>
                    <input
                        type="range"
                        wire:model.live="minScore"
                        min="70"
                        max="140"
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                    >
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>70</span>
                        <span>140</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button
                    wire:click="analyzeGrants"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    {{ __('all.grant_analyze.filters.btn_analyze') }}
                </button>
                <button
                    wire:click="resetFilters"
                    class="bg-gray-100 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium border border-gray-200"
                >
                    {{ __('all.grant_analyze.filters.btn_reset') }}
                </button>
            </div>
        </div>

        <!-- Results Section -->
        @if($analysisPerformed)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    {{ __('all.grant_analyze.analyze.title') }}
                    <span class="text-lg font-normal text-gray-600">
                        ({{ $matchingSpecialities->flatten()->count() }} {{ __('all.grant_analyze.analyze.find') }})
                    </span>
                </h2>

                @if($matchingSpecialities->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📚</div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('all.grant_analyze.analyze.not_found') }}</h3>
                        <p class="text-gray-600">{{ __('all.grant_analyze.analyze.not_found_desc') }}</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($matchingSpecialities as $universityName => $specialities)
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                    {{ $universityName }}
                                </h3>

                                <div class="space-y-4">
                                    @foreach($specialities as $speciality)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $speciality['name'] }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $speciality['faculty'] }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-lg font-semibold text-green-600">
                                                        {{ $speciality['grant_count'] }} {{ __('all.grant_analyze.analyze.grants') }}
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        {{ __('all.grant_analyze.analyze.score') }} {{ $speciality['passing_score'] }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @php
                                                    $subjects = array_filter([
                                                        $speciality['subject_1'] ?? null,
                                                        $speciality['subject_2'] ?? null,
                                                        $speciality['subject_3'] ?? null,
                                                        $speciality['subject_4'] ?? null,
                                                        $speciality['subject_5'] ?? null,
                                                    ]);
                                                @endphp
                                                @foreach($subjects as $subject)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                                        {{ $subject }}
                                                    </span>
                                                @endforeach
                                            </div>

                                            <div class="flex justify-between items-center text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">{{ __('all.grant_analyze.analyze.learn_year') }}</span> {{ $speciality['duration_years'] }} {{ __('all.grant_analyze.analyze.year') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">{{ __('all.grant_analyze.analyze.code') }}</span> {{ $speciality['code'] }}
                                                </div>
                                            </div>

                                            @if($speciality['description'])
                                                <div class="mt-2 text-sm text-gray-700">
                                                    {{ $speciality['description'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Error Messages -->
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
