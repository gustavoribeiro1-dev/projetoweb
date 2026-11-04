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
        if ($id === null) {
            echo json_encode($dao->listar());
        } else {
            $c = $dao->buscarPorId($id);
            if (!$c) {
                http_response_code(404);
                echo json_encode(['erro' => 'Aluno não encontrado']);
            } else {
                echo json_encode($c);
            }
        }
        break;

    case 'POST':
        if (empty($d['nome']) || empty($d['email']) || empty($d['telefone']) || empty($d['dt_nascimento']) || empty($d['id_plano'])) {
            http_response_code(400);
            echo json_encode(['erro' => 'Preencha todos os campos obrigatórios']);
            exit;
        }

        $novoId = $dao->inserir($d['nome'], $d['email'], $d['telefone'], $d['dt_nascimento'], $d['id_plano']);
        http_response_code(201);
        echo json_encode(['id' => $novoId]);
        break;

    case 'PUT':
        break;

    case 'DELETE':
        break;

    default:
        http_response_code(405);
        echo json_encode(['erro' => 'Método não permitido']);
}
