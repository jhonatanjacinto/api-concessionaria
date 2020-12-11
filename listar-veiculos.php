<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-type: application/json; charset=UTF-8');

try
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $veiculos_json = file_get_contents(__DIR__ . '/db/veiculos.json');
        http_response_code(200);
        echo $veiculos_json;
    }
    else 
    {
        throw new Exception('Requisição não autorizada!');
    }
}
catch(Exception $e)
{
    $status_code = $e->getCode() ?: 400;
    http_response_code($status_code);
    echo json_encode(
        (object)array(
            'status' => '0',
            'message' => $e->getMessage()
        )
    );
}