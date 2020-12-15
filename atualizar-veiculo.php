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
        $token_usuario = $_POST['token'] ?: null;
        $marca = $_POST['marca'] ?: null;
        $modelo = $_POST['modelo'] ?: null;
        $ano = filter_var($_POST['ano'], FILTER_VALIDATE_INT) ?: null;
        $preco = filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT) ?: null;
        $foto = $_POST['foto'] ?: null;
        $cor = $_POST['cor'] ?: null; 
        $descricao = $_POST['descricao'] ?: null;
        $id = $_POST['id'] ?: null;

        if ($token_usuario != $token) {
            throw new Exception('TOKEN de acesso inválido!', 401);
        }

        if (!$id) {
            throw new Exception('ID do veículo é inválido!');
        }

        if (!$marca || !$modelo || !$preco || !$ano || !$cor || !$descricao) {
            throw new Exception('Um ou mais dados enviados para atualização são inválidos!');
        }

        $veiculos_json = file_get_contents(__DIR__ . '/db/veiculos.json');
        $lista_veiculos = json_decode($veiculos_json);

        $dados_veiculo = (object)array(
            'id' => $id,
            'marca' => $marca,
            'modelo' => $modelo,
            'ano' => $ano,
            'preco' => $preco,
            'foto' => $foto,
            'cor' => $cor,
            'descricao' => $descricao
        );

        foreach ($lista_veiculos as $index => $veiculo)
        {
            if ($veiculo->id === $dados_veiculo->id)
            {
                $lista_veiculos[$index] = $dados_veiculo;
                break;
            }
        }

        $veiculos_json = json_encode($lista_veiculos);
        file_put_contents(__DIR__ . '/db/veiculos.json', $veiculos_json);

        http_response_code(200);
        echo json_encode(
            (object)array(
                'status' => 1,
                'message' => 'Veículo atualizado com sucesso!'
            )
        );
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