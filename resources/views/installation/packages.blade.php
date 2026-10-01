<div class="grid">
    @forelse(($configuration['packages'] ?? []) as $package)
        <article class="package">
            <h3>{{ $package['name'] }}</h3>
            <p>{{ $package['description'] ?? '' }}</p>
            <details open>
                <summary>Included features</summary>
                <ul>
                    @foreach(($package['features'] ?? []) as $feature)
                        <li><strong>{{ $feature['name'] }}</strong>
                            @if($feature['required'] ?? false)<span class="badge">Required</span>@endif
                            <p class="feature-description">{{ $feature['description'] ?? '' }}</p>
                        </li>
                    @endforeach
                </ul>
            </details>
        </article>
    @empty
        <p>No package configuration has been received. Refresh your subscription after purchasing packages.</p>
    @endforelse
</div>
