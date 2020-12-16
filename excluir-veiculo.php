<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-type: application/json; charset=UTF-8');

include 'token.php';

try
{
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE')
    {
        $id = $_GET['id'] ?: null;
        $token_usuario = $_GET['token'] ?: null;

        if ($token_usuario != $token) {
            throw new Exception('TOKEN de acesso inválido!', 401);
        }

        if (!$id) {
            throw new Exception('ID inválido!');
        }

        $veiculos_json = file_get_contents(__DIR__ . '/db/veiculos.json');
        $lista_veiculos = (array)json_decode($veiculos_json);
        $lista_veiculos = array_values(
            array_filter($lista_veiculos, function($veiculo) use($id) {
                return $veiculo->id != $id;
            })
        );
        
        $veiculos_json = json_encode($lista_veiculos);
        file_put_contents(__DIR__ . '/db/veiculos.json', $veiculos_json);

        http_response_code(200);
        echo json_encode(
            (object)array(
                'status' => 1,
                'message' => 'Veículo excluído com sucesso!'
            )
        );
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
    {
        http_response_code(200);
        echo json_encode(array('status' => 'OK'));
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
            'status' => 0,
            'message' => $e->getMessage()
        )
    );
}