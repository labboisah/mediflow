@php
    $sidebarUser = Auth::user();
    $sidebarService = app(\App\Services\SidebarService::class);
    $sidebarGroups = $sidebarService->groupsFor($sidebarUser);
    $dashboardRoute = $sidebarUser?->isSuperAdmin()
        ? route('dashboard')
        : ($sidebarUser?->hasRole('medical_director')
            ? route('medical-director.index')
            : ($sidebarUser?->hasRole('administrator') ? route('admin.index') : route('dashboard')));
    $dashboardLabel = $sidebarUser?->isSuperAdmin() ? 'Platform' : 'Dashboard';

    $isRouteActive = function (array $patterns) {
        return collect($patterns)->contains(fn ($pattern) => request()->routeIs($pattern));
    };

    $activitiesOpen = request()->routeIs('reports.activities.*');
    $canShowActivities = $sidebarUser && $sidebarService->canShowActivities($sidebarUser);
@endphp

<aside class="admin-sidebar">
    <div class="admin-sidebar-header">
        <div class="admin-sidebar-title">
            <div class="fw-bold text-success">Menu</div>
            <small class="text-muted">Modules and permissions</small>
        </div>
        <button type="button"
                class="btn btn-outline-success sidebar-toggle-btn"
                id="sidebarToggle"
                aria-label="Toggle sidebar"
                aria-expanded="true"
                title="Toggle sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
    </div>

    <nav class="admin-sidebar-nav">
        <a class="admin-sidebar-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.index') || request()->routeIs('medical-director.index') ? 'active' : '' }}" href="{{ $dashboardRoute }}">
            <i class="bi bi-speedometer2"></i>
            {{ $dashboardLabel }}
        </a>

        @foreach($sidebarGroups as $group)
            @continue($group['key'] === 'dashboard')

            @php
                $groupOpen = $group['items']->contains(fn ($item) => $isRouteActive($sidebarService->patternsFor($item)))
                    || ($group['key'] === 'reports' && $activitiesOpen);
            @endphp

            <button class="admin-sidebar-toggle {{ $groupOpen ? '' : 'collapsed' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $group['id'] }}SidebarMenu"
                    aria-expanded="{{ $groupOpen ? 'true' : 'false' }}"
                    aria-controls="{{ $group['id'] }}SidebarMenu">
                <span><i class="bi {{ $group['icon'] }}"></i> {{ $group['label'] }}</span>
                <i class="bi bi-chevron-down admin-sidebar-chevron"></i>
            </button>

            <div class="collapse {{ $groupOpen ? 'show' : '' }}" id="{{ $group['id'] }}SidebarMenu">
                <div class="admin-sidebar-submenu">
                    @foreach($group['items'] as $item)
                        @php($patterns = $sidebarService->patternsFor($item))
                        <a class="admin-sidebar-link {{ $isRouteActive($patterns) ? 'active' : '' }}" href="{{ route($item->route) }}">
                            <i class="bi {{ $item->icon }}"></i>
                            {{ $item->label }}
                        </a>
                    @endforeach

                    @if($group['key'] === 'reports' && $canShowActivities)
                        <button class="admin-sidebar-toggle {{ $activitiesOpen ? '' : 'collapsed' }} mt-1"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#activitiesSidebarMenu"
                                aria-expanded="{{ $activitiesOpen ? 'true' : 'false' }}"
                                aria-controls="activitiesSidebarMenu">
                            <span><i class="bi bi-activity"></i> Activities</span>
                            <i class="bi bi-chevron-down admin-sidebar-chevron"></i>
                        </button>
                        <div class="collapse {{ $activitiesOpen ? 'show' : '' }}" id="activitiesSidebarMenu">
                            <div class="admin-sidebar-submenu admin-sidebar-scroll">
                                @foreach(\App\Models\Department::orderBy('name')->get(['id', 'name']) as $activityDepartment)
                                    <a class="admin-sidebar-link {{ request()->routeIs('reports.activities.*') && request()->route('department')?->id === $activityDepartment->id ? 'active' : '' }}" href="{{ route('reports.activities.show', $activityDepartment) }}">
                                        <i class="bi bi-building"></i>
                                        {{ $activityDepartment->name }}
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

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.__fayhosSidebarToggleBound) {
                return;
            }

            window.__fayhosSidebarToggleBound = true;

            const toggle = document.getElementById('sidebarToggle');
            const icon = toggle ? toggle.querySelector('i') : null;
            const mobileQuery = window.matchMedia('(max-width: 991.98px)');

            if (!toggle || !icon) {
                return;
            }

            const applySidebarState = function (collapsed) {
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                icon.classList.toggle('bi-layout-sidebar-inset', !collapsed);
                icon.classList.toggle('bi-list', collapsed);
            };

            const preferredCollapsedState = function () {
                const storageKey = mobileQuery.matches ? 'sidebar-collapsed-mobile' : 'sidebar-collapsed';
                const stored = localStorage.getItem(storageKey);

                if (stored !== null) {
                    return stored === 'true';
                }

                return mobileQuery.matches;
            };

            applySidebarState(preferredCollapsedState());

            toggle.addEventListener('click', function () {
                const collapsed = !document.body.classList.contains('sidebar-collapsed');
                const storageKey = mobileQuery.matches ? 'sidebar-collapsed-mobile' : 'sidebar-collapsed';
                localStorage.setItem(storageKey, collapsed ? 'true' : 'false');
                applySidebarState(collapsed);
            });

            document.querySelectorAll('.admin-sidebar-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (mobileQuery.matches) {
                        localStorage.setItem('sidebar-collapsed-mobile', 'true');
                        applySidebarState(true);
                    }
                });
            });

            mobileQuery.addEventListener('change', function () {
                applySidebarState(preferredCollapsedState());
            });
        });
    </script>
@endonce
