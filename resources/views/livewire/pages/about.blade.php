<div>
    {{-- Hero Section --}}
    <section class="py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                {{ __('all.about.title') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 leading-relaxed">
                {{ __('all.about.desc') }}
            </p>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="prose prose-lg mx-auto text-gray-700">
                <p class="text-xl leading-relaxed mb-8">
                    {{ __('all.about.content.text_1') }}
                </p>

                <div class="space-y-8">
                    <div>

                        <p class="text-lg leading-relaxed">
                            {{ __('all.about.content.text_2') }}
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('all.about.content.text_3') }}</h2>
                        <ul class="list-disc list-inside">
                            <li class="text-lg leading-relaxed">{{ __('all.about.content.text_4') }}</li>
                            <li class="text-lg leading-relaxed">{{ __('all.about.content.text_5') }}</li>
                            <li class="text-lg leading-relaxed">
                                {{ __('all.about.content.text_6') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('all.about.content.text_7') }}</h2>
                        <ul class="list-disc list-inside">
                            <li class="text-lg leading-relaxed">{{ __('all.about.content.text_8') }}</li>
                            <li class="text-lg leading-relaxed">{{ __('all.about.content.text_9') }}</li>
                            <li class="text-lg leading-relaxed">{{ __('all.about.content.text_10') }}</li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-lg leading-relaxed">
                            {{ __('all.about.content.text_11') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">
                {{ __('all.about.footer.title') }}
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                {{ __('all.about.footer.desc') }}
            </p>

            <div class="space-y-4 text-lg">
                <div>
                    <a href="mailto:zhangeldi.turarbek@gmail.com" class="text-blue-600 hover:text-blue-700">
                        zhangeldi.turarbek@gmail.com
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-blue-50">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">
                {{ __('all.about.cta.title') }}
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                {{ __('all.about.cta.desc') }}
            </p>
            <a href="{{ route('test-preparation') }}"
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition-colors">
                {{ __('all.about.cta.btn') }}
            </a>
        </div>
    </section>
</div>
