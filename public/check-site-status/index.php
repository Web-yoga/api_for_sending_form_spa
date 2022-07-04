<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 600');

$filename = 'block.txt';
$result = file_exists($filename) ?  'true' : 'false';

$arr['status'] = $result;

echo json_encode($arr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
