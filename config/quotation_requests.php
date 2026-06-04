<?php

return [
    'reference_prefix' => env('QUOTATION_REFERENCE_PREFIX', 'WEB'),

    'rate_limit' => [
        'max_attempts' => (int) env('QUOTATION_RATE_LIMIT', 5),
        'decay_minutes' => (int) env('QUOTATION_RATE_LIMIT_DECAY', 60),
    ],

    'uploads' => [
        'max_files' => (int) env('QUOTATION_MAX_FILES', 5),
        'max_size_kb' => (int) env('QUOTATION_MAX_FILE_SIZE_KB', 10240),
        'disk' => env('QUOTATION_UPLOAD_DISK', 'local'),
        'directory' => 'quotation-requests',
        'allowed_mimes' => [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/zip',
            'application/x-zip-compressed',
            'application/postscript',
            'application/illustrator',
        ],
    ],

    'captcha' => [
        'required' => env('QUOTATION_CAPTCHA_REQUIRED', false),
    ],

    'job_types' => [
        'Booklet',
        'Brochure',
        'Poster',
        'Banner',
        'Business Cards',
        'Other',
    ],

    'job_types_requiring_size' => [
        'Booklet',
        'Brochure',
        'Poster',
        'Banner',
        'Business Cards',
    ],

    'job_types_requiring_pages' => [
        'Booklet',
        'Brochure',
    ],

    'colour_options' => [
        'full_colour' => 'Full colour',
        'black_white' => 'Black & white',
        'spot_colour' => 'Spot colour',
        'mixed' => 'Mixed',
        'not_sure' => 'Not sure',
    ],

    'delivery_options' => [
        'pickup' => 'Pickup',
        'delivery' => 'Delivery',
        'not_sure' => 'Not sure',
    ],

    'preferred_contact_options' => [
        'email' => 'Email',
        'phone' => 'Phone',
        'either' => 'Either',
    ],

    'artwork_status_options' => [
        'ready' => 'Artwork ready',
        'needs_design' => 'Needs design',
        'needs_changes' => 'Needs changes',
        'not_applicable' => 'Not applicable',
    ],

    'finishing_options' => [
        'numbering' => 'Numbering',
        'perforating' => 'Perforating',
        'saddle_stitching' => 'Saddle stitching',
        'perfect_binding' => 'Perfect binding',
        'paper_cutting' => 'Paper cutting',
        'trimming' => 'Trimming',
        'case_making' => 'Case making',
        'gold_blocking' => 'Gold blocking',
        'lamination' => 'Lamination',
        'uv_coating' => 'UV coating',
        'folding' => 'Folding',
        'other' => 'Other',
    ],

    'paper_roles' => [
        'cover' => 'Cover',
        'text' => 'Text',
        'insert' => 'Insert',
        'other' => 'Other',
    ],

    'notification_email' => env('QUOTATION_NOTIFICATION_EMAIL'),

    'erp' => [
        'import_endpoint' => '/api/v1/quotations/import',
        'deep_link_template' => env('ERP_ESTIMATION_URL_TEMPLATE', ''),
    ],
];
