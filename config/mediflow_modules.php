<?php

return [
    'default_plan' => env('MEDIFLOW_DEFAULT_PLAN', 'hospital'),

    'plans' => [
        'pharmacy' => [
            'core',
            'pharmacy',
            'billing',
            'reports',
        ],

        'clinic' => [
            'core',
            'patient_records',
            'clinical_care',
            'doctor',
            'nursing',
            'billing',
            'reports',
        ],

        'diagnostic_annex' => [
            'core',
            'patient_records',
            'laboratory',
            'radiology',
            'billing',
            'finance',
            'reports',
        ],

        'maternity' => [
            'core',
            'patient_records',
            'clinical_care',
            'maternity',
            'billing',
            'reports',
        ],

        'hospital' => [
            '*',
        ],
    ],
];
