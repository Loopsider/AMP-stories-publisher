<?php

return [
    // Toggles error reporting, see https://github.com/aidantwoods/SecureHeaders/wiki/errorReporting
    'errorReporting' => true,

    // Safe mode
    'safeMode' => false,

    // HSTS Strict-Transport-Security
    'hsts' => [
        'enabled' => true,
    ],

    // Content Security Policy
    'csp' => [
        'default' => 'self',
        'script-src' => [
            'self',
            'cdn.ampproject.org',
            'cdnjs.cloudflare.com',
            'www.google-analytics.com',
            'unpkg.com',
            'media-library.cloudinary.com',
            'unsafe-inline'
        ],
        'connect-src' => [
            'self',
            'cdn.ampproject.org',
            'www.googletagmanager.com',
            'amp-error-reporting.appspot.com'
        ],
        'style-src' => [
            'self',
            'unsafe-inline',
            'unpkg.com',
            'media-library.cloudinary.com',
            'fonts.googleapis.com'
        ],
        'font-src' => [
            'self',
            'fonts.googleapis.com',
            'fonts.gstatic.com'
        ],
        'img-src' => [
            '*', // Allow images from anywhere
        ],
        'media-src'=> [
            '*', // Allow images from anywhere
        ],
        'worker-src' => [
          'self',
          'blob:',
          'cdn.ampproject.org'
        ]
    ],
];
