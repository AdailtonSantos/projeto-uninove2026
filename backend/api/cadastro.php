<?php
require_once __DIR__ . "/../config/conexao.php";
header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$nome = $dados['nome'] ?? null;
$email = $dados['email'] ?? null;
$senha = $dados['senha'] ?? null;

if (!$nome || !$email || !$senha) {
    http_response_code(400);
    echo json_encode(["erro" => "Preencha todos os campos."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["erro" => "E-mail inválido."]);
    exit;
}

echo json_encode(["debug" => "Dados recebidos com sucesso", "nome" => $nome, "email" => $email]);
?>