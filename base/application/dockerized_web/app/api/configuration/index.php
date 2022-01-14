<?php

require_once "../../src/class/Reader.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if (!isset($_POST['action']) || !preg_match("/generate_(header|services|extra_services|footer)/", $_POST['action'], $m)) {
    echo json_encode(["error"=>"Access Denied!", "action"=>$_POST['action']]);
    exit;
}

if ($_POST['action'] == "generate_header") {
    sleep(1);
    echo json_encode(["message"=>"API Generate Header Working..."]);
}

if ($_POST['action'] == "generate_services") {
    sleep(1);
    echo json_encode(["message"=>"API Generate Services Working..."]);
}

if ($_POST['action'] == "generate_extra_services") {
    sleep(1);
    echo json_encode(["message"=>"API Generate Extra Services Working..."]);
}

if ($_POST['action'] == "generate_footer") {
    sleep(1);
    echo json_encode(["message"=>"API Generate Footer Working..."]);
}


//
//$setup_file = '../../config/setup.txt';
//
//echo json_encode([
//    "CONFIGURATION_SETUP" => Dockerized\Reader::apiReaderSetup('CONFIGURATION_SETUP', $setup_file),
//    "SERVICES_QUANTITY" => Dockerized\Reader::apiReaderSetup('SERVICES_QUANTITY', $setup_file),
//    "NGINX_SETUP" => Dockerized\Reader::apiReaderSetup('NGINX_SETUP', $setup_file),
//    "APACHE_SETUP" => Dockerized\Reader::apiReaderSetup('APACHE_SETUP', $setup_file),
//    "SUPERVISOR_SETUP" => Dockerized\Reader::apiReaderSetup('SUPERVISOR_SETUP', $setup_file),
//    "DOCKER_COMPOSE_VERSION" => Dockerized\Reader::apiReaderSetup('DOCKER_COMPOSE_VERSION', $setup_file),
//    "DOCKER_COMPOSE_VERSION_OTHER" => Dockerized\Reader::apiReaderSetup('DOCKER_COMPOSE_VERSION_OTHER', $setup_file),
//    "NETWORK_DEFAULT" => Dockerized\Reader::apiReaderSetup('NETWORK_DEFAULT', $setup_file),
//    "NETWORK_GATEWAY" => Dockerized\Reader::apiReaderSetup('NETWORK_GATEWAY', $setup_file),
//    "RESOURCES_DOCKERIZED_ALL" => Dockerized\Reader::apiReaderSetup('RESOURCES_DOCKERIZED_ALL', $setup_file),
//    "RESOURCES_DOCKERIZED" => explode(",", Dockerized\Reader::apiReaderSetup('RESOURCES_DOCKERIZED', $setup_file)),
//    "DOCKER_EXTRA_IMAGES" => explode(",", Dockerized\Reader::apiReaderSetup('DOCKER_EXTRA_IMAGES', $setup_file)),
//    "PHP_VERSION_ALL" => Dockerized\Reader::apiReaderSetup('PHP_VERSION_ALL', $setup_file),
//    "PHP_VERSION" => explode(",", Dockerized\Reader::apiReaderSetup('PHP_VERSION', $setup_file)),
//    "GIT_PROJECT" => Dockerized\Reader::apiReaderSetupAll('GIT_PROJECT', $setup_file),
//]);
//