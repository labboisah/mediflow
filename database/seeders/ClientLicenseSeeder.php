<?php

namespace Database\Seeders;

use App\Models\ClientLicense;
use Illuminate\Database\Seeder;

class ClientLicenseSeeder extends Seeder
{
    public function run(): void
    {
        ClientLicense::updateOrCreate(
            ['license_key' => 'MEDIFLOW-LOCAL-HOSPITAL'],
            [
                'client_name' => config('app.name', 'MediFlow Client'),
                'plan' => config('mediflow_modules.default_plan', 'hospital'),
                'starts_at' => now(),
                'expires_at' => null,
                'is_active' => true,
            ]
        );
    }
}
