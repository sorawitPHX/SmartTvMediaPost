@props(['value', 'required'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if (isset($required))
        <span class="text-red-500 mx-1">*</span>
    @endif
</label>
