<?php 

return [

    'stripe' => [
        'url' => env('STRIPE_URL', 'http://localhost:3001')
    ],

    'paypal' => [
        'url' => env('PAYPAL_URL', 'http://localhost:3002')
    ]

];