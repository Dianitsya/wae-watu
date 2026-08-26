<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-oZab5O51M7Abx7xs9k4f-HPY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-D0IkiqrGGO1tEGgM'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
