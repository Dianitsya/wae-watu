<?php
$data = [
    'villa_id' => 1,
    'check_in' => '2026-12-25',
    'check_out' => '2026-12-28',
    'guests' => 2,
    'guest_name' => 'Test Real Midtrans Keys',
    'guest_email' => 'realmidtrans@waewatu.com',
    'guest_phone' => '+628123456789'
];

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => json_encode($data),
        'ignore_errors' => true
    ]
];

$res = file_get_contents('http://127.0.0.1:8001/api/bookings', false, stream_context_create($opts));
echo $res;
