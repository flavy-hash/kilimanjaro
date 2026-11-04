<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Quote defaults
    |--------------------------------------------------------------------------
    |
    | Used until an admin sets their own values on the Quote Settings page
    | (each is backed by a Setting row — see App\Models\Setting).
    |
    */

    'validity_days' => 14,

    'child_price_percent' => 70,

    'office_emails' => [
        'info@perfectkilimanjaro.com',
    ],

    'enquiry_emails_enabled' => true,

    'bank_accounts' => [],

    'payment_instructions' => 'Bank transfer details will be sent once your trip is confirmed. No payment is required to receive this quote.',

    'terms' => "This is an instant estimate, not a confirmed booking. We will confirm availability and the final price before any payment is taken.\nPrices are per person in US dollars and may change until a deposit is paid.\nFull booking terms are sent with your confirmation.",

];
