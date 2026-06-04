@props(['options' => [], 'selected' => null, 'placeholder' => null])

<div class="relative">
    <select {{ $attributes->merge(['class' => 'select']) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" @selected($selected == $value)>{{ $label }}</option>
        @endforeach
    </select>
    <span class="absolute top-[50%] right-4 leading-none translate-y-[-50%] pointer-events-none"><span class="material-symbols-outlined">arrow_drop_down</span></span>
</div>