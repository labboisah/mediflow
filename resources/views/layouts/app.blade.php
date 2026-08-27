<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<meta name="csrf-token"
      content="{{ csrf_token() }}">

<title>
    {{ config('app.name') }}
    | @yield('title')
</title>

<link rel="icon"
      href="{{ asset('images/logo.png') }}"
      type="image/png">

@vite(['resources/css/app.css', 'resources/js/app.js'])



@livewireStyles
@stack('styles')


</head>

<body>

<div class="min-vh-100 d-flex flex-column">

    @include('layouts.partials.navbar')

    @if(Auth::check())
        <div class="admin-layout flex-grow-1">
            @include('layouts.partials.admin-sidebar')

            <div class="admin-content">
                @include('layouts.partials.breadcrumb')

                @hasSection('header')

                    <header class="bg-white shadow-sm">

                        <div class="container-fluid py-4">

                            @yield('header')

                        </div>

                    </header>

                @endif

                <main class="flex-grow-1 py-4">

                    <div class="container-fluid">

                        @include('layouts.partials.alerts')

                        @yield('content')

                    </div>

                </main>
            </div>
        </div>
    @else

    @include('layouts.partials.breadcrumb')

    @hasSection('header')

        <header class="bg-white shadow-sm">

            <div class="container py-4">

                @yield('header')

            </div>

        </header>

    @endif

    <main class="flex-grow-1 py-4">

        <div class="container">

            @include('layouts.partials.alerts')

            @yield('content')

        </div>

    </main>
    @endif
    @livewireScripts
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="livewireToast"
         class="toast align-items-center text-bg-success border-0"
         role="alert">
        <div class="d-flex">
            <div class="toast-body">Success</div>
            <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast">
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('toast', (event) => {
        const toastEl = document.getElementById('livewireToast');
        const toastBody = toastEl.querySelector('.toast-body');

        toastBody.innerText = event.message;
        toastEl.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning');

        switch (event.type) {
            case 'danger':
                toastEl.classList.add('text-bg-danger');
            break;
            case 'warning':
                toastEl.classList.add('text-bg-warning');
            break;
            default:
                toastEl.classList.add('text-bg-success');
        }

        new bootstrap.Toast(toastEl).show();
    });
});
</script>

@stack('vite')


@stack('scripts')


</body>

</html>
