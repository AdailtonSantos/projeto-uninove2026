<?php
session_start();
header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$email = $dados['email'] ?? null;
$senha = $dados['senha'] ?? null;

if (!$email || !$senha) {
    http_response_code(400);
    echo json_encode(["erro" => "Preencha e-mail e senha."]);
    exit;
}
?>