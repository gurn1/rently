@props(['name', 'options' => [], 'selected' => []])

<div class="flex flex-wrap gap-2">
    @foreach ($options as $value => $label)
        <label class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-sm cursor-pointer transition
            has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-50 has-[:checked]:text-indigo-700">
            <input type="checkbox"
                name="{{ $name }}[]"
                value="{{ $value }}"
                class="sr-only"
                @checked(in_array((string) $value, array_map('strval', (array) $selected))) />
            {{ $label }}
        </label>
    @endforeach
</div>