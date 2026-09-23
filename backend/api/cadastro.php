<?php
##Criar novo usuário

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

require_once __DIR__ . "/../config/conexao.php";

$sql = "SELECT id FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute(['email' => $email]);

if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["erro" => "Este e-mail já está cadastrado."]);
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nome' => $nome,
    'email' => $email,
    'senha' => $senha_hash
]);

http_response_code(201);
echo json_encode(["sucesso" => "Usuário cadastrado com sucesso!"]);
?>