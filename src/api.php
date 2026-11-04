<?php
require_once __DIR__ . '/../DAO/alunoDAO.php';
$dao = new AlunoDAO();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Verificação do token (401)
$token = 'meu-token-secreto-123';
$autorizacao = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? '';

if ($autorizacao !== "Bearer $token") {
    http_response_code(401);
    echo json_encode(['erro' => 'Token inválido']);
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$d = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {
    case 'GET':
        break;

    case 'POST':
        break;

    case 'PUT':
        break;

    case 'DELETE':
        break;

    default:
        http_response_code(405);
        echo json_encode(['erro' => 'Método não permitido']);
}
