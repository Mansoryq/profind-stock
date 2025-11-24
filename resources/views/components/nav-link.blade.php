<a href="{{ $href }}"
   class="{{ $getClasses() }} hover:text-blue-600 transition-colors {{ $attributes->get('class') }}"
   {{ $attributes->except('class') }}>
    {{ $slot }}
</a>
