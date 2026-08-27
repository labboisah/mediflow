<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trim(config('app.name') . ' ' . (trim($__env->yieldContent('title')) ? '| ' . trim($__env->yieldContent('title')) : '')) }}</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    @vite(['resources/css/modern.css', 'resources/js/modern.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="antialiased">
    <div x-data="{ sidebarOpen: false }" class="mf-shell">
        @auth
            <div x-show="sidebarOpen"
                 x-transition.opacity
                 x-cloak
                 class="fixed inset-0 z-30 bg-med-ink/35 lg:hidden"
                 @click="sidebarOpen = false"></div>

            @include('layouts.partials.modern-sidebar')

            <div class="min-h-screen lg:pl-72">
                @include('layouts.partials.modern-topbar')

                <main class="px-4 py-6 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-screen-2xl">
                        @include('layouts.partials.modern-alerts')

                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot ?? '' }}
                        @endif
                    </div>
                </main>
            </div>
        @else
            <main class="flex min-h-screen items-center justify-center px-4 py-10">
                <div class="w-full max-w-md">
                    @include('layouts.partials.modern-alerts')

                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>
        @endauth

        <div x-data="{ show: false, message: '', type: 'success' }"
             x-on:mediflow-toast.window="
                message = $event.detail.message;
                type = $event.detail.type || 'success';
                show = true;
                setTimeout(() => show = false, 3500);
             "
             x-show="show"
             x-transition
             x-cloak
             class="fixed right-4 top-4 z-50 max-w-sm rounded-md border bg-white px-4 py-3 text-sm shadow-panel"
             :class="{
                'border-med-primary text-med-primary': type === 'success',
                'border-med-danger text-med-danger': type === 'danger',
                'border-med-accent text-med-ink': type === 'warning'
             }">
            <span x-text="message"></span>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    @stack('modals')
    @stack('vite')
</body>
</html>
