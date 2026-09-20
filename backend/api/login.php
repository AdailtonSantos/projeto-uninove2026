<?php
session_start();
require_once __DIR__ . "/../config/conexao.php";
header("Content-Type: application/json");
 
$dados = json_decode(file_get_contents("php://input"), true);
 
$email = $dados['email'] ?? null;
$senha = $dados['senha'] ?? null;
 
if (!$email || !$senha) {
    http_response_code(400);
    echo json_encode(["erro" => "Preencha e-mail e senha."]);
    exit;
}
 
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["erro" => "E-mail inválido."]);
    exit;
}
 
try {
   
    $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
 
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if (!$usuario) {
        http_response_code(401);
        echo json_encode(["erro" => "E-mail ou senha incorretos."]);
        exit;
    }
 

    if (!password_verify($senha, $usuario['senha'])) {
        http_response_code(401);
        echo json_encode(["erro" => "E-mail ou senha incorretos."]);
        exit;
    }
 
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
 
    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Login realizado com sucesso.",
        "usuario" => [
            "id" => $usuario['id'],
            "nome" => $usuario['nome'],
            "email" => $usuario['email']
        ]
    ]);
 
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro no servidor. Tente novamente."]);
 
}