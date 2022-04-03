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
$auth = $headers["Authorization"] ?? "";

if ($auth == "Bearer F0F1F2F3F4F5F6F7F8F90000000000000000") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'] ?? "";
    $age = $data['age'] ?? "";
    $address = $data['address'] ?? "";

    echo json_encode([
        "status" => 200,
        "message" => "Data save successfully !",
        "auth" => $auth,
        "request" => [
            "name" => $name,
            "age" => $age,
            "address" => $address,
        ]
    ]);
} else {

    echo json_encode([
        "status" => 401,
        "message" => "Authorization Required !",
        "auth" => $auth
    ]);
}

exit;
