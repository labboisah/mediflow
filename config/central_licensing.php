<?php

return [
    // Client installations always require activation, even if the optional flag is false.
    'enabled' => strtolower(trim((string) env('APP_MODE', 'standalone'))) === 'client'
        || (bool) env('MEDIFLOW_CENTRAL_LICENSING', false),
];
