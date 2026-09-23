<?php
require_once __DIR__ . "/../config/conexao.php";
header("Content-Type: application/json");

$dados = json_decode(file_get_contents("php://input"), true);

$nome = $dados['nome'] ?? null;
$email = $dados['email'] ?? null;
$senha = $dados['senha'] ?? null;
$confirmarSenha = $dados['confirmarSenha'] ?? null;

if (!$nome || !$email || !$senha || !$confirmarSenha) {
    http_response_code(400);
    echo json_encode(["erro" => "Preencha todos os campos."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["erro" => "E-mail inválido."]);
    exit;
}

if ($senha !== $confirmarSenha) {
    http_response_code(400);
    echo json_encode(["erro" => "As senhas não coincidem."]);
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senhaHash);
    $stmt->execute();

    echo json_encode(["sucesso" => true, "mensagem" => "Cadastro realizado com sucesso."]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(["erro" => "Este e-mail já está cadastrado."]);
    } else {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao cadastrar usuário."]);
    }
    exit;
}
?>