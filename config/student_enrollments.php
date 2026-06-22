<?php

return [
    'reference_prefix' => env('STUDENT_ENROLLMENT_PREFIX', 'SEN'),

    'rate_limit' => [
        'max_attempts' => (int) env('STUDENT_ENROLLMENT_RATE_LIMIT', 5),
        'decay_minutes' => (int) env('STUDENT_ENROLLMENT_RATE_DECAY', 60),
    ],

    'gender_options' => [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'prefer_not_to_say' => 'Prefer not to say',
    ],

    'qualification_levels' => [
        'msce' => 'MSCE',
        'a_level' => 'A Level',
        'certificate' => 'Certificate',
        'diploma' => 'Diploma',
        'degree' => 'Degree',
        'other' => 'Other',
    ],

    'districts' => [
        'Balaka', 'Blantyre', 'Chikwawa', 'Chiradzulu', 'Chitipa', 'Dedza', 'Dowa',
        'Karonga', 'Kasungu', 'Likoma', 'Lilongwe', 'Machinga', 'Mangochi', 'Mchinji',
        'Mulanje', 'Mwanza', 'Mzimba', 'Neno', 'Nkhata Bay', 'Nkhotakota', 'Nsanje',
        'Ntcheu', 'Ntchisi', 'Phalombe', 'Rumphi', 'Salima', 'Thyolo', 'Zomba',
    ],
];
