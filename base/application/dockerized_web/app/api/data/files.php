<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

ini_set("memory_limit",-1);
ini_set('max_execution_time', 0);

//var_dump($_REQUEST);
//print_r($_FILES);
//die;

//$fakepath = @$_FILES['app_file_identity']['tmp_name'];
$fakepath = @$_FILES['jh-form-input-file']['tmp_name'];
//$filename = @$_FILES['app_file_identity']['name'];
$filename = @$_FILES['jh-form-input-file']['name'];
$final_filename = rand()."-".basename($filename);
$response = "";

if(!empty($fakepath)) {
    if(move_uploaded_file($fakepath, "./files/".$final_filename)) {
        $response = array(
            'retorno'=>'ok',
            'mensagem' => 'Arquivo enviado com sucesso',
            'linkFile' => "./files/".$final_filename,
        );
    } else {
        $response = array(
            'retorno'=>'nok',
            'mensagem' => 'Houve um erro ao tentar enviar o arquivo'
        );
    }
}

echo json_encode($response,JSON_UNESCAPED_UNICODE);
exit;

?>

