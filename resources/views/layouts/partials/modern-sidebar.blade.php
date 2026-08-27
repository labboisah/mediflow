@php
    $sidebarUser = Auth::user();
    $sidebarService = app(\App\Services\SidebarService::class);
    $sidebarGroups = $sidebarService->groupsFor($sidebarUser);
    $dashboardRoute = $sidebarUser?->hasRole('medical_director')
        ? route('medical-director.index')
        : ($sidebarUser?->hasRole('administrator') ? route('admin.index') : route('dashboard'));

    $isRouteActive = fn (array $patterns) => collect($patterns)->contains(fn ($pattern) => request()->routeIs($pattern));
    $activitiesOpen = request()->routeIs('reports.activities.*');
    $canShowActivities = $sidebarUser && $sidebarService->canShowActivities($sidebarUser);
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-med-line bg-med-canvas px-3 py-4 transition lg:translate-x-0"
       :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': ! sidebarOpen }">
    <div class="mb-5 flex items-center justify-between px-2">
        <a href="{{ $dashboardRoute }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-10 rounded-md object-contain">
            <span>
                <span class="block text-base font-bold text-med-ink">Mediflow</span>
                <span class="block text-xs font-medium text-med-muted">Healthcare operations</span>
            </span>
        </a>

        <button type="button"
                class="mf-focus inline-flex h-9 w-9 items-center justify-center rounded-md border border-med-line bg-white text-med-muted lg:hidden"
                @click="sidebarOpen = false"
                aria-label="Close sidebar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <nav class="space-y-1 overflow-y-auto pb-8">
        <a class="mf-sidebar-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.index') || request()->routeIs('medical-director.index') ? 'mf-sidebar-link-active' : '' }}"
           href="{{ $dashboardRoute }}">
            <span class="mf-icon-box"><i class="bi bi-speedometer2"></i></span>
            <span>Dashboard</span>
        </a>

        @foreach($sidebarGroups as $group)
            @continue($group['key'] === 'dashboard')

            @php
                $groupOpen = $group['items']->contains(fn ($item) => $isRouteActive($sidebarService->patternsFor($item)))
                    || ($group['key'] === 'reports' && $activitiesOpen);
            @endphp

            <div x-data="{ open: {{ $groupOpen ? 'true' : 'false' }} }" class="pt-1">
                <button type="button" class="mf-sidebar-toggle mf-focus" @click="open = ! open">
                    <span class="flex items-center gap-3">
                        <span class="mf-icon-box"><i class="bi {{ $group['icon'] }}"></i></span>
                        <span>{{ $group['label'] }}</span>
                    </span>
                    <i class="bi bi-chevron-down text-xs text-med-muted transition" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" class="ml-6 mt-1 space-y-1 border-l border-med-line pl-4">
                    @foreach($group['items'] as $item)
                        @php($patterns = $sidebarService->patternsFor($item))
                        <a class="mf-sidebar-link {{ $isRouteActive($patterns) ? 'mf-sidebar-link-active' : '' }}"
                           href="{{ route($item->route) }}"
                           @click="sidebarOpen = false">
                            <i class="bi {{ $item->icon }} w-5 text-center text-med-primary"></i>
                            <span>{{ $item->label }}</span>
                        </a>
                    @endforeach

                    @if($group['key'] === 'reports' && $canShowActivities)
                        <div x-data="{ activitiesOpen: {{ $activitiesOpen ? 'true' : 'false' }} }">
                            <button type="button" class="mf-sidebar-toggle mf-focus" @click="activitiesOpen = ! activitiesOpen">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-activity w-5 text-center text-med-primary"></i>
                                    <span>Activities</span>
                                </span>
                                <i class="bi bi-chevron-down text-xs text-med-muted transition" :class="{ 'rotate-180': activitiesOpen }"></i>
                            </button>

                            <div x-show="activitiesOpen" class="ml-4 max-h-72 space-y-1 overflow-y-auto border-l border-med-line pl-4">
                                @foreach(\App\Models\Department::orderBy('name')->get(['id', 'name']) as $activityDepartment)
                                    <a class="mf-sidebar-link {{ request()->routeIs('reports.activities.*') && request()->route('department')?->id === $activityDepartment->id ? 'mf-sidebar-link-active' : '' }}"
                                       href="{{ route('reports.activities.show', $activityDepartment) }}"
                                       @click="sidebarOpen = false">
                                        <i class="bi bi-building w-5 text-center text-med-primary"></i>
                                        <span>{{ $activityDepartment->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </nav>
</aside>
