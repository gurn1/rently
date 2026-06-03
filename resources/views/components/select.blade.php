@props(['options' => [], 'selected' => null, 'placeholder' => null])

<select {{ $attributes->merge(['class' => 'select']) }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected($selected == $value)>{{ $label }}</option>
    @endforeach
</select>