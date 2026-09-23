<?php
$host = "localhost";
$dbname = "uninove2026"; 
$usuario = "root";
$senha = ""; 
 
try {

    $pdoTemp = new PDO("mysql:host=$host;charset=utf8mb4", $usuario, $senha);
    $pdoTemp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdoTemp->exec("CREATE DATABASE IF NOT EXISTS $dbname");
 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
 
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao conectar com o banco de dados."]);
    exit;
}
?>