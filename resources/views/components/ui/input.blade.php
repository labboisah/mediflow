@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
])

<label class="block">
    @if($label)
        <span class="mb-1 block text-sm font-medium text-med-ink">{{ $label }}</span>
    @endif

    <input type="{{ $type }}"
           name="{{ $name }}"
           {{ $attributes->merge(['class' => 'mf-focus block w-full rounded-md border-med-line text-sm text-med-ink shadow-sm placeholder:text-med-muted/70']) }}>

    @error($name)
        <span class="mt-1 block text-sm text-med-danger">{{ $message }}</span>
    @enderror
</label>
