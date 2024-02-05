<?php

return [
    'services' => [
        'default' => [
            'expires_in' => 5, // in minutes
            'otp_generator_options' => [
                'length' => 4, // no of digits
                'numbers' => true,
                'letters' => false,
                'symbols' => false,
            ],
            'validate_uniqueness_after_generation' => true,
            'delete_after_verification' => false,
        ],
    ],
    'fallback_options' => [
        'otp_generator_options' => [
            'length' => 4, // no of digits
            'numbers' => true,
            'letters' => false,
            'symbols' => false,
        ],
        'validate_uniqueness_after_generation' => true,
        'delete_after_verification' => false,
    ]

];
