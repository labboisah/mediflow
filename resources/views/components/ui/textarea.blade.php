@props([
    'label' => null,
    'name' => null,
])

<label class="block">
    @if($label)
        <span class="mb-1 block text-sm font-medium text-med-ink">{{ $label }}</span>
    @endif

    <textarea name="{{ $name }}" {{ $attributes->merge(['class' => 'mf-focus block w-full rounded-md border-med-line text-sm text-med-ink shadow-sm placeholder:text-med-muted/70']) }}>{{ $slot }}</textarea>

    @error($name)
        <span class="mt-1 block text-sm text-med-danger">{{ $message }}</span>
    @enderror
</label>
