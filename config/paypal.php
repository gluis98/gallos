<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'trial_days' => (int) env('BILLING_TRIAL_DAYS', 14),
];
