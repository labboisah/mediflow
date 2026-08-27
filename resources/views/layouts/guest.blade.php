<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fatima Yahaya Hospital') }}</title>
        
        @vite(['resources/css/guest.css', 'resources/js/app.js']) 
        
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

           
    </head>
    <body>
        

        <div class="auth-container">
            
            <div class="auth-body">
                <div class="text text-center"><img src="{{asset('images/logo.png')}}" width="100" alt=""></div>
                
                {{ $slot }}
                
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script>
            (function () {
                const pageLoader = document.getElementById('page-loader');

                const hidePageLoader = () => {
                    if (pageLoader) {
                        pageLoader.classList.add('hidden');
                    }
                };

                const disableElement = (element) => {
                    if (!element || element.dataset.loading === 'true') {
                        return;
                    }

                    element.dataset.loading = 'true';

                    if (element.tagName === 'A') {
                        element.style.pointerEvents = 'none';
                        element.setAttribute('aria-disabled', 'true');
                        element.classList.add('disabled');
                        return;
                    }

                    element.disabled = true;
                };

                const setButtonLoading = (button, label) => {
                    if (!button || button.dataset.loading === 'true') {
                        return;
                    }

                    disableElement(button);
                    button.dataset.originalHtml = button.innerHTML;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        ${label}
                    `;
                };

                const isActionableLink = (link) => {
                    if (!link || !link.href) {
                        return false;
                    }

                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href === 'mailto:' || href === 'tel:') {
                        return false;
                    }

                    if (link.target && link.target !== '_self') {
                        return false;
                    }

                    if (link.hasAttribute('download')) {
                        return false;
                    }

                    return link.hostname === window.location.hostname;
                };

                document.addEventListener('DOMContentLoaded', function () {
                    hidePageLoader();

                    document.querySelectorAll('a').forEach(link => {
                        if (!isActionableLink(link)) {
                            return;
                        }

                        link.addEventListener('click', function () {
                            disableElement(link);
                        });
                    });

                    document.querySelectorAll('button, input[type="submit"], input[type="button"], input[type="reset"]').forEach(control => {
                        control.addEventListener('click', function (event) {
                            const button = event.currentTarget;

                            if (button.matches('[data-bs-toggle], [data-toggle], .dropdown-toggle')) {
                                return;
                            }

                            if (button.closest('form') && (button.type === 'submit' || button.getAttribute('type') === 'submit')) {
                                return;
                            }

                            setButtonLoading(button, button.dataset.loadingText || 'Loading...');
                        });
                    });

                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', function () {
                            const button = form.querySelector("button[type='submit'], input[type='submit']");
                            if (button) {
                                setButtonLoading(button, button.dataset.loadingText || 'Processing...');
                            }
                        });
                    });
                });
            })();
        </script>
    </body>
</html>
