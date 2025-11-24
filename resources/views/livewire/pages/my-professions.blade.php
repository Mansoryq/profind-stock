<div class="min-h-screen bg-white py-4 px-4 mb-8"
     x-data="myProfessionsManager()"
     @favorite-updated.window="handleFavoriteUpdate($event.detail)">

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-light text-gray-900 mb-1">{{ __('all.my_professions.title') }}</h1>
            <p class="text-sm text-gray-600">{{ __('all.my_professions.desc') }}</p>
        </div>

        @if($favoriteProfessions->count() > 0)
            <!-- Professions List -->
            <div class="space-y-2">
                @foreach($favoriteProfessions as $profession)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden transition-all duration-200 hover:border-gray-300"
                         x-show="shouldShowProfession({{ $profession->id }})"
                         x-data="{ open: false }"
                         x-transition>
                        <!-- Profession Header -->
                        <div class="p-3 cursor-pointer"
                             @click="open = !open">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 flex-1 min-w-0">
                                    <div class="w-6 h-6 text-gray-500">{{ svg($profession->sphere->icon ?? 'heroicon-o-home') }}</div>
                                    <h3 class="text-base font-medium text-gray-900 truncate">{{ $profession->localized_name }}</h3>
                                </div>

                                <div class="flex items-center space-x-1">
                                    <!-- Like/Unlike Button -->
                                    <button @click.stop="toggleProfessionLike({{ $profession->id }})"
                                            :class="getProfessionButtonClass({{ $profession->id }})"
                                            class="p-1 transition-colors flex-shrink-0"
                                            :title="isFavoriteProfession({{ $profession->id }}) ? 'Убрать из избранного' : 'Добавить в избранное'">
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

                                    @if($profession->localized_description)
                                        <div class="text-gray-400 transition-transform duration-200"
                                             :class="openId === {{ $profession->id }} ? 'rotate-90' : ''">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($profession->localized_description)
                            <div x-show="open"
                                 x-transition
                                 class="text-xs md:text-sm border-t border-gray-100"
                                 style="display: none; background-color: #f5f9ff">
                                <div class="p-3">
                                    <p class="text-xs text-gray-700 leading-relaxed mb-2">
                                        <span class="font-semibold">{{ __('all.profession_map.professions.sphere') }}:</span> {{ $profession->sphere->localized_name }}
                                    </p>
                                    <h4 class="text-xs font-medium text-gray-900 mb-2">{{ __('all.profession_map.professions.desc') }}</h4>
                                    <p class="text-xs text-gray-700 leading-relaxed">{{ $profession->localized_description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h2 class="text-xl font-light text-gray-900 mb-2">{{ __('all.my_professions.no_found.title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('all.my_professions.no_found.desc') }}</p>
                <a href="{{ route('profession-map') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    {{ __('all.my_professions.no_found.btn') }}
                </a>
            </div>
        @endif
    </div>

    <script>
        function myProfessionsManager() {
            return {
                favoriteProfessions: @json(Auth::check() ? Auth::user()->favouriteProfessions()->pluck('professions.id')->toArray() : []),
                temporarilyRemovedProfessions: [], // Track professions that are temporarily removed but not yet persisted

                init() {
                    console.log('MyProfessions initialized with favorites:', this.favoriteProfessions);
                },

                isFavoriteProfession(professionId) {
                    return this.favoriteProfessions.includes(professionId) && !this.temporarilyRemovedProfessions.includes(professionId);
                },

                getProfessionButtonClass(professionId) {
                    return this.isFavoriteProfession(professionId)
                        ? 'text-red-500 hover:text-red-600'
                        : 'text-gray-400 hover:text-red-500';
                },

                shouldShowProfession(professionId) {
                    // On my-professions page, we want to keep showing professions even if temporarily unliked
                    // They will only disappear after page reload when the database is actually updated
                    return true;
                },

                toggleProfessionLike(professionId) {
                    const isCurrentlyFavorite = this.isFavoriteProfession(professionId);

                    if (isCurrentlyFavorite) {
                        // Remove from favorites and add to temporarily removed
                        this.temporarilyRemovedProfessions.push(professionId);
                        console.log('Temporarily removed profession:', professionId);
                    } else {
                        // Add back to favorites and remove from temporarily removed
                        this.temporarilyRemovedProfessions = this.temporarilyRemovedProfessions.filter(id => id !== professionId);
                        console.log('Added profession back to favorites:', professionId);
                    }

                    // Update database via Livewire
                @this.call('toggleProfessionLike', professionId, !isCurrentlyFavorite);
                },

                handleFavoriteUpdate(data) {
                    console.log(`Favorite ${data.type} ${data.action}:`, data.id);

                    // Optional: Show success message
                    if (data.action === 'removed') {
                        this.showToast('Профессия удалена из избранного. Обновите страницу для полного удаления.');
                    } else {
                        this.showToast('Профессия добавлена в избранное!');
                    }
                },

                showToast(message) {
                    // Simple toast implementation
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => document.body.removeChild(toast), 300);
                    }, 3000);
                }
            }
        }
    </script>
</div>
