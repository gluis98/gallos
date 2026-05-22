@props(['name', 'size' => 20, 'class' => ''])
<svg class="i {{ $class }}" width="{{ $size }}" height="{{ $size }}" aria-hidden="true" focusable="false">
    <use href="{{ asset('img/icons.svg') }}#{{ $name }}"></use>
</svg>
