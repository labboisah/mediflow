<?php

return [
    'default_plan' => env('MEDIFLOW_DEFAULT_PLAN', 'hospital'),

    'platform_modules' => [
        'platform',
    ],

    'features' => [
        'core' => 'Core System',
        'patient_records' => 'Patient Records',
        'clinical_care' => 'Clinical Care',
        'doctor' => 'Doctor Workspace',
        'nursing' => 'Nursing Workspace',
        'maternity' => 'Maternity',
        'laboratory' => 'Laboratory',
        'radiology' => 'Radiology',
        'pharmacy' => 'Pharmacy',
        'billing' => 'Billing',
        'finance' => 'Finance',
        'reports' => 'Reports',
        'wards_beds' => 'Wards & Beds',
        'department_management' => 'Departments & Inventory',
        'access_control' => 'Access Control',
        'synchronization' => 'Data Synchronization',
        'maintenance' => 'Maintenance',
        'medical_director' => 'Medical Director',
        'branch_management' => 'Branch Management',
    ],

    'plans' => [
        'diagnostic_center' => [
            'label' => 'Diagnostic Center',
            'description' => 'Laboratory, radiology, patient registration, billing, finance, and reports.',
            'modules' => [
                'core',
                'patient_records',
                'laboratory',
                'radiology',
                'billing',
                'finance',
                'reports',
                'access_control',
            ],
        ],

        'maternity_clinic' => [
            'label' => 'Maternity Clinic',
            'description' => 'Patient records, maternity workflows, basic clinical care, billing, and reports.',
            'modules' => [
                'core',
                'patient_records',
                'clinical_care',
                'nursing',
                'maternity',
                'billing',
                'reports',
                'access_control',
            ],
        ],

        'pharmacy' => [
            'label' => 'Pharmacy',
            'description' => 'Standalone pharmacy operations, medicines, stock, dispensing, billing, and reports.',
            'modules' => [
                'core',
                'pharmacy',
                'billing',
                'finance',
                'reports',
                'access_control',
            ],
        ],

        'general_clinic' => [
            'label' => 'General Clinic',
            'description' => 'Patient records, doctor and nursing care, billing, and reports.',
            'modules' => [
                'core',
                'patient_records',
                'clinical_care',
                'doctor',
                'nursing',
                'billing',
                'reports',
                'access_control',
            ],
        ],

        'hospital' => [
            'label' => 'Hospital',
            'description' => 'Full single-branch hospital operations.',
            'modules' => ['*'],
        ],

        'enterprise_hospital' => [
            'label' => 'Enterprise Hospital',
            'description' => 'Full hospital operations with many branches.',
            'modules' => ['*', 'branch_management'],
        ],
    ],

    'legacy_plan_aliases' => [
        'clinic' => 'general_clinic',
        'diagnostic_annex' => 'diagnostic_center',
        'maternity' => 'maternity_clinic',
    ],
];
