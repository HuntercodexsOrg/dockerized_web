<?php

require_once "../../../src/class/Reader.php";
require_once "../../../src/class/Mapper.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$mapper = Dockerized\Mapper::mapperSetup('../../../config/setup.txt');

echo $mapper;

