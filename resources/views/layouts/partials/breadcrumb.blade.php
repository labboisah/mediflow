@if(class_exists(\Diglactic\Breadcrumbs\Breadcrumbs::class))

<div class="bg-white border-bottom">


<div class="container py-2">

    @php

    try {

        $routeName =
            Route::currentRouteName();

        if ($routeName) {

            $routeParams =
                Route::current()
                    ->parameters();

            $parameters =
                array_values(
                    $routeParams
                );

            $breadcrumbsHtml =
                \Diglactic\Breadcrumbs\Breadcrumbs::render(
                    $routeName,
                    ...$parameters
                );

            echo str_replace(
                [
                    '<ul>',
                    '</ul>',
                    '<li>',
                    '</li>'
                ],
                [
                    '<ol class="breadcrumb mb-0">',
                    '</ol>',
                    '<li class="breadcrumb-item">',
                    '</li>'
                ],
                $breadcrumbsHtml
            );

        }

    } catch (\Exception $e) {

    }

    @endphp

</div>


</div>

@endif
