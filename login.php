<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-type: application/json; charset=UTF-8');

include 'token.php';

try 
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $usuario = $_POST['usuario'] ?: null;
        $senha = $_POST['senha'] ?: null;

        if ($usuario == 'caelum' && $senha == 'js46') 
        {
            http_response_code(200);
            echo json_encode(
                (object)array(
                    'status' => 1,
                    'message' => 'Login realizado com sucesso!',
                    'token' => $token
                )
            );
        }
        else 
        {
            throw new Exception('Usuário/Senha inválido!');
        }
    }
    else 
    {
        throw new Exception('Requisição não autorizada!');
    }
}
catch(Exception $e)
{
    http_response_code(400);
    echo json_encode(
        (object)array(
            'status' => 0,
            'message' => $e->getMessage()
        )
    );
}
