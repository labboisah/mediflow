<div wire:poll.15s>
    <x-ui.page :title="$pageTitle" :subtitle="$pageSubtitle">
        <x-slot:actions>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.clients.index') }}" class="mf-btn mf-btn-secondary">
                    <i class="bi bi-buildings"></i>
                    <span>Clients</span>
                </a>
                <a href="{{ route('admin.agents.index') }}" class="mf-btn mf-btn-secondary">
                    <i class="bi bi-person-badge"></i>
                    <span>Agents</span>
                </a>
                <a href="{{ route('admin.license-management') }}" class="mf-btn mf-btn-primary">
                    <i class="bi bi-patch-check"></i>
                    <span>License Management</span>
                </a>
            </div>
        </x-slot:actions>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Clients</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['clients']) }}</p>
                <p class="mt-2 text-base text-med-muted">{{ number_format($summary['active_clients']) }} active</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Licenses</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['licenses']) }}</p>
                <p class="mt-2 text-base text-med-muted">{{ number_format($summary['active_licenses']) }} active</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Agents</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['agents']) }}</p>
                <p class="mt-2 text-base text-med-muted">{{ number_format($summary['active_agents']) }} active</p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-base font-medium text-med-muted">Hospital Features</p>
                <p class="mt-3 text-4xl font-semibold leading-tight text-med-ink">{{ number_format($summary['hospital_features']) }}</p>
                <p class="mt-2 text-base text-med-muted">{{ number_format($summary['platform_modules']) }} platform route</p>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-12">
            <x-ui.card class="xl:col-span-7" title="Recent Licenses">
                <div class="overflow-hidden rounded-md border border-med-line">
                    <table class="min-w-full divide-y divide-med-line text-left text-sm">
                        <thead class="bg-med-canvas text-xs uppercase tracking-wide text-med-muted">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Client</th>
                                <th class="px-4 py-3 font-semibold">Plan</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Expiry</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-med-line bg-white">
                            @forelse($recentLicenses as $license)
                                <tr>
                                    <td class="px-4 py-4 font-medium text-med-ink">{{ $license->client_name }}</td>
                                    <td class="px-4 py-4 text-med-muted">{{ str($license->plan)->replace('_', ' ')->headline() }}</td>
                                    <td class="px-4 py-4">
                                        <x-ui.badge :variant="$license->is_active ? 'success' : 'neutral'">
                                            {{ $license->is_active ? 'Active' : 'Inactive' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-4 text-med-muted">{{ $license->expires_at?->format('M j, Y') ?? 'No expiry' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10">
                                        <x-ui.empty-state title="No license records yet" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            <x-ui.card class="xl:col-span-5" title="Platform Actions">
                <div class="grid gap-3">
                    <a href="{{ route('admin.clients.index') }}" class="flex items-center justify-between rounded-md border border-med-line bg-white px-4 py-4 text-med-ink transition hover:border-med-primary hover:bg-med-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="mf-icon-box text-med-primary"><i class="bi bi-buildings"></i></span>
                            <span>
                                <span class="block font-semibold">Manage Clients</span>
                                <span class="block text-sm text-med-muted">Organizations, sectors, contacts, and lifecycle status</span>
                            </span>
                        </span>
                        <i class="bi bi-arrow-right text-med-muted"></i>
                    </a>

                    <a href="{{ route('admin.license-management') }}" class="flex items-center justify-between rounded-md border border-med-line bg-white px-4 py-4 text-med-ink transition hover:border-med-primary hover:bg-med-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="mf-icon-box text-med-primary"><i class="bi bi-patch-check"></i></span>
                            <span>
                                <span class="block font-semibold">Manage Licenses</span>
                                <span class="block text-sm text-med-muted">Plans, feature modules, keys, and branch limits</span>
                            </span>
                        </span>
                        <i class="bi bi-arrow-right text-med-muted"></i>
                    </a>

                    <a href="{{ route('admin.agents.index') }}" class="flex items-center justify-between rounded-md border border-med-line bg-white px-4 py-4 text-med-ink transition hover:border-med-primary hover:bg-med-primary/5">
                        <span class="flex items-center gap-3">
                            <span class="mf-icon-box text-med-primary"><i class="bi bi-person-badge"></i></span>
                            <span>
                                <span class="block font-semibold">Manage Agents</span>
                                <span class="block text-sm text-med-muted">Sales agents, resellers, and implementation partners</span>
                            </span>
                        </span>
                        <i class="bi bi-arrow-right text-med-muted"></i>
                    </a>
                </div>
            </x-ui.card>
        </div>

        <x-ui.card title="Recent Platform Activity">
            <div class="divide-y divide-med-line">
                @forelse($recentActivities as $activity)
                    <div class="flex flex-col gap-2 py-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="font-semibold text-med-ink">{{ ucwords(str_replace(['.', '_'], [' ', ' '], $activity->action)) }}</p>
                            <p class="mt-1 text-sm text-med-muted">{{ $activity->actor?->name ?? 'System' }}</p>
                        </div>
                        <div class="text-sm text-med-muted sm:text-right">
                            <p>{{ $activity->created_at?->format('M j, h:i A') }}</p>
                            <p>{{ $activity->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <x-ui.empty-state title="No platform activity yet" />
                @endforelse
            </div>
        </x-ui.card>
    </x-ui.page>
</div>
