<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set("memory_limit",-1);
ini_set('max_execution_time', 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'] ?? "";
$pass = $data['password'] ?? "";

if (!$user || !$pass) {

    echo json_encode([
        "status" => 401,
        "message" => "Missing username or password !",
        "request" => $data
    ]);

} elseif ($user != 'devel' || $pass != '123456') {

    echo json_encode([
        "status" => 401,
        "message" => "Incorrect username or password !"
    ]);

} else {

    echo json_encode([
        "status" => 200,
        "message" => "Login successfully !",
        "token" => "F0F1F2F3F4F5F6F7F8F90000000000000000"
    ]);

}

exit;
