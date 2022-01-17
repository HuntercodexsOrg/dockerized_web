<?php

require_once "../../src/class/Reader.php";
require_once "../../src/class/Mapper.php";
require_once "../../src/class/Generator.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$setup_file = "../../config/setup.txt";
$config_file = "../../config/configuration.conf";
$mapper = Dockerized\Mapper::mapperSetup($setup_file);

if (!isset($_POST['action'])) {
    echo json_encode(["error"=>"Access Denied!", "action"=>$_POST['action']]);
    exit;
}

if ($_POST['action'] == "generate_header") {
    $header_file = "/data/dockerized_web/setup/header_configuration.tpl";
    $response = Dockerized\Generator::headerGenerator($setup_file, $header_file);
    if ($response == true) {
        echo json_encode([
            "status" => "ok",
            "response" => $response
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "response" => $response
        ]);
    }
    exit;
}

if ($_POST['action'] == "generate_services") {
    $services_file = "/data/dockerized_web/setup/services_configuration.tpl";
    $response = Dockerized\Generator::servicesGenerator($setup_file, $services_file);
    if ($response == true) {
        echo json_encode([
            "status" => "ok",
            "response" => $response
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "response" => $response
        ]);
    }
    exit;
}

if ($_POST['action'] == "generate_extra_services") {
    $extras_file = "/data/dockerized_web/setup/extras_configuration.tpl";
    $response = Dockerized\Generator::extrasGenerator($setup_file, $extras_file);
    if ($response == true) {
        echo json_encode([
            "status" => "ok",
            "response" => $response
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "response" => $response
        ]);
    }
    exit;
}

if ($_POST['action'] == "generate_footer") {
    $footer_file = "/data/dockerized_web/setup/footer_configuration.tpl";
    $response = Dockerized\Generator::footerGenerator($setup_file, $footer_file);
    if ($response == true) {
        echo json_encode([
            "status" => "ok",
            "response" => $response
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "response" => $response
        ]);
    }
    exit;
}

if ($_POST['action'] == "delete_configuration") {
    if (unlink($config_file)) {
        echo json_encode([
            "status" => "ok",
            "response" => "File deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "response" => "Was not possible delete the configuration file"
        ]);
    }
    exit;
}

