<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@mediflow.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin@2026'),
            ]
        );

        foreach (['superadmin', 'administrator'] as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role && ! $superAdmin->roles()->where('roles.id', $role->id)->exists()) {
                $superAdmin->assignRole($role);
            }
        }

        $admin = User::firstOrCreate(
            ['email' => 'muhammad.muntaka@fayhos.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('fayhos@2026'),
            ]
        );

        // Assign administrator role
        $adminRole = Role::where('name', 'administrator')->first();
        if ($adminRole && !$admin->hasRole('administrator')) {
            $admin->assignRole($adminRole);
        }

        $recordOfficer = User::firstOrCreate(
            ['email' => 'record@fayhos.com'],
            [
                'name' => 'Record',
                'password' => Hash::make('record@123'),
                'department_id'=>4
            ]);
            
        $recordOfficerRole = Role::where('name', 'record')->first();
        if ($recordOfficerRole && !$recordOfficer->hasRole('record')) {
            $recordOfficer->assignRole($recordOfficerRole);
        }

        // assign acoountant role to record officer 
        if ($recordOfficerRole && !$recordOfficer->hasRole('accountant')) { 
            $recordOfficer->assignRole('accountant'); 
        }

         $pharmacist = User::firstOrCreate(
            ['email' => 'pharmacist@fayhos.com'],
            [
                'name' => 'Pharmacist',
                'password' => Hash::make('pharm@123'),
                'department_id'=>3
            ]);
            
        $pharmacistRole = Role::where('name', 'pharmacist')->first();
        if ($pharmacistRole && !$pharmacist->hasRole('pharmacist')) {
            $pharmacist->assignRole($pharmacistRole);
        }

         $labScientist = User::firstOrCreate(
            ['email' => 'lab@fayhos.com'],
            [
                'name' => 'Lab Scientis',
                'password' => Hash::make('lab@123'),
                'department_id'=>2
            ]);
            
        $labScientistRole = Role::where('name', 'lab_technician')->first();
        if ($labScientistRole && !$labScientist->hasRole('lab_technician')) {
            $labScientist->assignRole($labScientistRole);
        }

        $headRole = Role::where('name', 'head_of_department')->first();
        if ($headRole && !$labScientist->hasRole('head_of_department')) {
            $labScientist->assignRole($headRole);
        }

            $nurse = User::firstOrCreate(
                ['email' => 'nurse@fayhos.com'],
                [
                    'name' => 'Nurse',
                    'password' => Hash::make('nurse@123'),
                ]);
                
        $nurseRole = Role::where('name', 'nurse')->first();
        if ($nurseRole && !$nurse->hasRole('nurse')) {
            $nurse->assignRole($nurseRole);
        }
        $midwife = User::firstOrCreate(
            ['email' => 'midwife@fayhos.com'],
            [
                'name' => 'Midwife',
                'password' => Hash::make('midwife@123'),
            ]);
        
        $midwifeRole = Role::where('name', 'midwife')->first();
        if ($midwifeRole && !$midwife->hasRole('midwife')) {
            $midwife->assignRole($midwifeRole);
        }

        $doctor = User::firstOrCreate(
            ['email' => 'doctor@fayhos.com'],
            [
                'name' => 'Doctor',
                'password' => Hash::make('doctor@123'),
            ]);
            
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole && !$doctor->hasRole('doctor')) {
            $doctor->assignRole($doctorRole);
        }

        $radiologist = User::firstOrCreate(
            ['email' => 'radio@fayhos.com'],
            [
                'name' => 'Radiologist',
                'password' => Hash::make('radio@123'),
                'department_id'=>1
            ]);
            
        $radiologistRole = Role::where('name', 'radiologist')->first();
        if ($radiologistRole && !$radiologist->hasRole('radiologist')) {
            $radiologist->assignRole($radiologistRole);
        }

    }
}
