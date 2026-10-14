<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["logado" => false]);
    exit;
}

echo json_encode([
    "logado" => true,
    "id"     => $_SESSION['usuario_id'],
    "nome"   => $_SESSION['usuario_nome'],
    "email"  => $_SESSION['usuario_email'],
    "tipo"   => $_SESSION['tipo'] ?? 'cliente'
]);