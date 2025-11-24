<div class="min-h-screen bg-white py-4 px-4 mb-8"
     x-data="professionMap()"
     @favorite-updated.window="handleFavoriteUpdate($event.detail)">

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-light text-gray-900 mb-1">{{ __('all.my_spheres.title') }}</h1>
            <p class="text-sm text-gray-600">{{ __('all.my_spheres.desc') }}</p>
        </div>

        <div>
            @foreach($spheres as $sphere)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 hover:border-gray-300 mb-3"
                     x-data="{ open: false }"
                >
                    <!-- Sphere Header -->
                    <div class="p-3 cursor-pointer"
                         @click="open = !open"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 text-gray-500">{{ svg($sphere->icon ?? 'heroicon-o-home') }}</div>
                                <h3 class="text-base font-medium text-gray-900">{{ $sphere->localized_name }}</h3>
                            </div>

                            <div class="flex items-center space-x-1">
                                <!-- Like Button -->
                                @auth
                                    <button @click.stop="toggleSphereLike({{ $sphere->id }})"
                                            :class="getSphereButtonClass({{ $sphere->id }})"
                                            class="p-1 transition-colors flex-shrink-0"
                                            :title="isFavoriteSphere({{ $sphere->id }}) ? 'Убрать из избранного' : 'Добавить в избранное'">
                                        <template x-if="isFavoriteSphere({{ $sphere->id }})">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        </template>
                                        <template x-if="!isFavoriteSphere({{ $sphere->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        </template>
                                    </button>
                                @endauth

                                <div class="text-gray-400 transition-transform duration-200"
                                     :class="openId === {{ $sphere->id }} ? 'rotate-90' : ''">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div x-show="open"
                         x-transition
                         class="text-xs border-t border-gray-100"
                         style="display: none; background-color: #f5f9ff">
                        @if($sphere->localized_description)
                            <div class="p-3 border-b border-gray-200">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $sphere->localized_description }}</p>
                            </div>
                        @endif
                        @if($sphere->professions->count() > 0)
                            <div class="p-3">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">{{ __('all.profession_map.professions.into') }}</h4>
                                <div class="grid grid-cols-1 gap-1">
                                    @foreach($sphere->professions as $profession)
                                        <div
                                            class="bg-white border flex flex-col border-gray-200 rounded text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                            x-data="{ open: false }"
                                        >
                                            <!-- Header -->
                                            <div class="flex justify-between items-center px-2 py-1">
                                                {{ $profession->localized_name }}

                                                <div class="flex">
                                                    @if($profession->localized_description)
                                                        <button
                                                            @click="open = !open"
                                                            class="ml-2 p-1 text-gray-400 hover:text-blue-500 transition-colors flex-shrink-0"
                                                            title="Показать описание профессии"
                                                        >
                                                            <svg class="w-4 h-4"
                                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    @auth
                                                        <button
                                                            @click.stop="toggleProfessionLike({{ $profession->id }})"
                                                            :class="getProfessionButtonClass({{ $profession->id }})"
                                                            class="p-1 transition-colors flex-shrink-0"
                                                            :title="isFavoriteProfession({{ $profession->id }}) ? 'Убрать из избранного' : 'Добавить в избранное'"
                                                        >
                                                            <template x-if="isFavoriteProfession({{ $profession->id }})">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                                </svg>
                                                            </template>
                                                            <template x-if="!isFavoriteProfession({{ $profession->id }})">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                                </svg>
                                                            </template>
                                                        </button>
                                                    @endauth
                                                </div>
                                            </div>

                                            <!-- Accordion Content -->
                                            <div
                                                x-show="open"
                                                x-transition
                                                class="border-t border-gray-100 bg-gray-50 px-2 py-2 text-xs text-gray-700 leading-relaxed"
                                                style="display: none;"
                                            >
                                                {{ $profession->localized_description }}
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function professionMap() {
            return {
                favoriteSpheres: @json(Auth::check() ? (Auth::user()->favouriteSpheres()->pluck('spheres.id')->toArray() ?? []) : []),
                favoriteProfessions: @json(Auth::check() ? (Auth::user()->favouriteProfessions()->pluck('professions.id')->toArray() ?? []) : []),

                init() {
                    console.log('Initialized with favorites:', {
                        spheres: this.favoriteSpheres,
                        professions: this.favoriteProfessions
                    });
                },

                loadFavorites(data) {
                    // This method can be called if you want to update favorites from Livewire events
                    this.favoriteSpheres = data.spheres || [];
                    this.favoriteProfessions = data.professions || [];
                    console.log('Updated favorites from event:', data);
                },

                isFavoriteSphere(sphereId) {
                    return this.favoriteSpheres.includes(sphereId);
                },

                isFavoriteProfession(professionId) {
                    return this.favoriteProfessions.includes(professionId);
                },

                getSphereButtonClass(sphereId) {
                    return this.isFavoriteSphere(sphereId)
                        ? 'text-red-500 hover:text-red-600'
                        : 'text-gray-400 hover:text-red-500';
                },

                getProfessionButtonClass(professionId) {
                    return this.isFavoriteProfession(professionId)
                        ? 'text-red-500 hover:text-red-600'
                        : 'text-gray-400 hover:text-red-500';
                },

                toggleSphereLike(sphereId) {
                    const isCurrentlyFavorite = this.isFavoriteSphere(sphereId);

                    // Update UI immediately
                    if (isCurrentlyFavorite) {
                        this.favoriteSpheres = this.favoriteSpheres.filter(id => id !== sphereId);
                    } else {
                        this.favoriteSpheres.push(sphereId);
                    }

                    // Update database via Livewire
                @this.call('likeSphere', sphereId, !isCurrentlyFavorite);
                },

                toggleProfessionLike(professionId) {
                    const isCurrentlyFavorite = this.isFavoriteProfession(professionId);

                    // Update UI immediately
                    if (isCurrentlyFavorite) {
                        this.favoriteProfessions = this.favoriteProfessions.filter(id => id !== professionId);
                    } else {
                        this.favoriteProfessions.push(professionId);
                    }

                    // Update database via Livewire
                @this.call('likeProfession', professionId, !isCurrentlyFavorite);
                },

                handleFavoriteUpdate(data) {
                    // Optional: Show success message or handle errors
                    console.log(`Favorite ${data.type} ${data.action}:`, data.id);

                    // You could show a toast notification here
                    // this.showToast(`${data.type} ${data.action} to favorites!`);
                }
            }
        }
    </script>
</div>
