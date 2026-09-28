<?php

namespace App\Http\Controllers;

use App\Services\SystemBranding;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function logo(SystemBranding $branding)
    {
        $path = $branding->settings()?->logo_path;
        abort_unless($path && str_starts_with($path, 'branding/') && Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'no-cache', 'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
