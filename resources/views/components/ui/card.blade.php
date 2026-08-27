@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-md border border-med-line bg-white shadow-sm']) }}>
    @if($title || $subtitle || $actions)
        <div class="flex flex-col gap-3 border-b border-med-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if($title)
                    <h2 class="text-base font-semibold text-med-ink">{{ $title }}</h2>
                @endif

                @if($subtitle)
                    <p class="mt-1 text-sm text-med-muted">{{ $subtitle }}</p>
                @endif
            </div>

            @if($actions)
                <div class="flex flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-5">
        {{ $slot }}
    </div>
</section>
