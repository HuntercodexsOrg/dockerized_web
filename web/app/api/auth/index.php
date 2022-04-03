<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set("memory_limit",-1);
ini_set('max_execution_time', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$headers = apache_request_headers();
$token = $headers['Authorization'] ?? "";

if ($token == 'F0F1F2F3F4F5F6F7F8F90000000000000000') {

    echo json_encode([
        "status" => 200,
        "message" => "Authorized !"
    ]);

} else {

    echo json_encode([
        "status" => 401,
        "message" => "Not Authorized !",
        "request" => $token
    ]);

}

exit;
