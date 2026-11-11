<?php

return [
    'bank_name' => env('RENTAL_BANK_NAME'),
    'account_number' => env('RENTAL_BANK_ACCOUNT_NUMBER'),
    'account_holder' => env('RENTAL_BANK_ACCOUNT_HOLDER'),
    'max_days' => (int) env('RENTAL_MAX_DAYS', 30),
];
