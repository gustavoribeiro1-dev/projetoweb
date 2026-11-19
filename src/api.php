<?php
require_once __DIR__ . '/../DAO/alunoDAO.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Envia a resposta em JSON com o código HTTP informado e encerra o script
function responder($codigo, $conteudo)
{
    http_response_code($codigo);
    echo json_encode($conteudo, JSON_UNESCAPED_UNICODE);
    exit;
}

// Pega os campos do aluno que vieram no corpo da requisição (sem espaços nas pontas)
function lerAluno($corpo)
{
    $aluno = [];

    foreach (['nome', 'email', 'telefone', 'dt_nascimento', 'id_plano'] as $campo) {
        $valor = $corpo[$campo] ?? '';
        $aluno[$campo] = is_scalar($valor) ? trim((string) $valor) : '';
    }

    return $aluno;
}

// Confere os dados do aluno. Retorna a mensagem de erro ou null se estiver tudo certo
function validarAluno($aluno, $planos)
{
    if ($aluno['nome'] === '' || $aluno['email'] === '' || $aluno['telefone'] === ''
        || $aluno['dt_nascimento'] === '' || $aluno['id_plano'] === '') {
        return 'Preencha todos os campos obrigatórios';
    }

    if (mb_strlen($aluno['nome']) > 50) {
        return 'O nome deve ter no máximo 50 caracteres';
    }

    if (!filter_var($aluno['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($aluno['email']) > 100) {
        return 'Informe um e-mail válido';
    }

    if (mb_strlen($aluno['telefone']) > 20) {
        return 'O telefone deve ter no máximo 20 caracteres';
    }

    // A data precisa existir de verdade (ex.: 2000-02-30 é recusada)
    $data = DateTime::createFromFormat('!Y-m-d', $aluno['dt_nascimento']);
    if (!$data || $data->format('Y-m-d') !== $aluno['dt_nascimento']) {
        return 'Informe uma data de nascimento válida (AAAA-MM-DD)';
    }

    $idsPlanos = array_map('strval', array_column($planos, 'id'));
    if (!in_array($aluno['id_plano'], $idsPlanos, true)) {
        return 'Selecione um plano válido';
    }

    return null;
}

// Verificação do token (401)
$token = 'meu-token-secreto-123';
$cabecalhos = function_exists('getallheaders') ? array_change_key_case(getallheaders(), CASE_LOWER) : [];
$autorizacao = $_SERVER['HTTP_AUTHORIZATION'] ?? $cabecalhos['authorization'] ?? '';

if ($autorizacao !== "Bearer $token") {
    responder(401, ['erro' => 'Token inválido']);
}

$dao = new AlunoDAO();
$metodo = $_SERVER['REQUEST_METHOD'];

// O id vem pela URL (api.php?id=3) e precisa ser um número inteiro maior que zero
$id = $_GET['id'] ?? null;
if ($id !== null) {
    $id = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        responder(400, ['erro' => 'Informe um ID válido']);
    }
}

// Corpo da requisição em JSON (usado no POST e no PUT)
$corpo = json_decode(file_get_contents('php://input'), true);
if (!is_array($corpo)) {
    $corpo = [];
}

switch ($metodo) {
    case 'GET':
        // api.php?planos -> lista os planos (usado no formulário de cadastro/edição)
        if (isset($_GET['planos'])) {
            responder(200, $dao->listarPlanos());
        }

        if ($id === null) {
            responder(200, $dao->listar());
        }

        $aluno = $dao->buscarPorId($id);
        if (!$aluno) {
            responder(404, ['erro' => 'Aluno não encontrado']);
        }
        responder(200, $aluno);
        break;

    case 'POST':
        $aluno = lerAluno($corpo);
        $erro = validarAluno($aluno, $dao->listarPlanos());
        if ($erro !== null) {
            responder(400, ['erro' => $erro]);
        }

        $novoId = $dao->inserir($aluno['nome'], $aluno['email'], $aluno['telefone'], $aluno['dt_nascimento'], $aluno['id_plano']);
        responder(201, ['id' => (int) $novoId]);
        break;

    case 'PUT':
        if ($id === null) {
            responder(400, ['erro' => 'Informe o ID do aluno']);
        }
        if (!$dao->buscarPorId($id)) {
            responder(404, ['erro' => 'Aluno não encontrado']);
        }

        $aluno = lerAluno($corpo);
        $erro = validarAluno($aluno, $dao->listarPlanos());
        if ($erro !== null) {
            responder(400, ['erro' => $erro]);
        }

        $dao->atualizar($id, $aluno['nome'], $aluno['email'], $aluno['telefone'], $aluno['dt_nascimento'], $aluno['id_plano']);
        responder(200, ['ok' => true]);
        break;

    case 'DELETE':
        if ($id === null) {
            responder(400, ['erro' => 'Informe o ID do aluno']);
        }
        if (!$dao->buscarPorId($id)) {
            responder(404, ['erro' => 'Aluno não encontrado']);
        }

        $dao->excluir($id);
        responder(200, ['ok' => true]);
        break;

    default:
        responder(405, ['erro' => 'Método não permitido']);
}