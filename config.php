<?php
// config.php — Aura Creator (full app)
// Fill real values before uploading to cPanel.

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'nnvrdjjh_auracreator',
        'user' => 'nnvrdjjh_aurauser',
        'pass' => 'REPLACE_WITH_REAL_DB_PASSWORD',
        'charset' => 'utf8mb4',
    ],

    'gemini' => [
        'api_key' => 'REPLACE_WITH_GOOGLE_AI_STUDIO_KEY',
        'model' => 'gemini-2.5-flash-lite',
        'api_base' => 'https://generativelanguage.googleapis.com/v1beta/models',
    ],

    'razorpay' => [
        'key_id' => 'REPLACE_WITH_RAZORPAY_KEY_ID',
        'key_secret' => 'REPLACE_WITH_RAZORPAY_KEY_SECRET',
        'currency' => 'INR',
    ],

    'plans' => [
        'starter'  => ['label' => 'Starter',  'price' => 1499, 'credits' => 10],
        'business' => ['label' => 'Business', 'price' => 2999, 'credits' => 30],
        'pro'      => ['label' => 'Pro',      'price' => 4999, 'credits' => 9999],
        'agency'   => ['label' => 'Agency',   'price' => 9999, 'credits' => 9999],
    ],

    'downloads' => [
        'html' => ['label' => 'HTML Source Code',  'price' => 1499],
        'zip'  => ['label' => 'Full ZIP Package',  'price' => 2999],
        'apk'  => ['label' => 'APK + Full Package','price' => 4999],
    ],

    'app' => [
        'name' => 'Aura Creator',
        'base_url' => 'https://auracreator.app',
        'free_credits' => 3,
        'timezone' => 'Asia/Kolkata',
    ],
];
