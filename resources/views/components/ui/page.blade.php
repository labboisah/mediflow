@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<section {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if($title || $subtitle || $actions)
        <div class="flex flex-col gap-4 border-b border-med-line pb-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0">
                @if($title)
                    <h1 class="text-2xl font-semibold text-med-ink">{{ $title }}</h1>
                @endif

                @if($subtitle)
                    <p class="mt-1 max-w-3xl text-sm text-med-muted">{{ $subtitle }}</p>
                @endif
            </div>

            @if($actions)
                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</section>
