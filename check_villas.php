<?php
$res = file_get_contents('http://127.0.0.1:8001/api/content');
$data = json_decode($res, true);
echo json_encode($data['villas'], JSON_PRETTY_PRINT);
