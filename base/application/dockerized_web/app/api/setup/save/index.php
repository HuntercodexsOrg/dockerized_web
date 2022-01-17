<?php

require_once "../../../src/class/SaveContent.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$setup_file = '../../../config/setup.txt';

/*Request Save Content*/
if (isset($_POST) && count($_POST) > 0) {

    $save = Dockerized\SaveContent::saveSetup($_POST, $setup_file);

    if ($save) {
        echo json_encode([
            "status" => "ok",
            "message" => "Data saved successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error on trying save data"
        ]);
    }
}
