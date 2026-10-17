<?php
require_once __DIR__ . '/../DAO/alunoDAO.php';

$id = isset($_GET['id']) && is_string($_GET['id'])
    ? filter_var($_GET['id'], FILTER_VALIDATE_INT)
    : false;

if ($id === false || $id < 1) {
    http_response_code(400);
    $aluno = false;
} else {
    $dao = new AlunoDAO();
    $aluno = $dao->buscarPorId($id);
}

if ($aluno === false) {
    http_response_code(404);
}

$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$valorPost = static function ($campo) {
    return isset($_POST[$campo]) && is_string($_POST[$campo]) ? trim($_POST[$campo]) : '';
};
$erro = '';

if ($aluno !== false) {
    $planos = $dao->listarPlanos();
    $dados = [
        'nome' => $aluno['nome'],
        'email' => $aluno['email'],
        'telefone' => $aluno['telefone'],
        'dt_nascimento' => $aluno['dt_nascimento'],
        'id_plano' => (string) $aluno['id_plano'],
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dados = [
            'nome' => $valorPost('nome'),
            'email' => $valorPost('email'),
            'telefone' => $valorPost('telefone'),
            'dt_nascimento' => $valorPost('dt_nascimento'),
            'id_plano' => $valorPost('id_plano'),
        ];

        $data = DateTime::createFromFormat('!Y-m-d', $dados['dt_nascimento']);
        $errosData = DateTime::getLastErrors();
        $dataValida = $data !== false
            && $data->format('Y-m-d') === $dados['dt_nascimento']
            && ($errosData === false || ($errosData['warning_count'] === 0 && $errosData['error_count'] === 0));
        $idPlano = filter_var($dados['id_plano'], FILTER_VALIDATE_INT);
        $idsPlanos = array_map('strval', array_column($planos, 'id'));

        if ($dados['nome'] === '' || strlen($dados['nome']) > 50
            || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)
            || strlen($dados['email']) > 100
            || $dados['telefone'] === '' || strlen($dados['telefone']) > 20
            || !$dataValida
            || $idPlano === false || !in_array((string) $idPlano, $idsPlanos, true)) {
            $erro = 'Confira os dados informados e selecione um plano válido.';
        } else {
            $dao->atualizar(
                $id,
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['dt_nascimento'],
                $idPlano
            );

            header('Location: alunos.php?status=atualizado');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitPlus Academia - Editar aluno</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container">
      <a class="logo" href="index.php">Fit <span>Plus</span></a>
      <nav>
        <ul class="menu">
          <li><a href="index.php">Início</a></li>
          <li><a class="ativo" href="alunos.php">Alunos</a></li>
          <li><a href="cadastro.php">Novo aluno</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <?php if ($aluno === false): ?>
      <h1>Aluno não encontrado</h1>
      <p class="subtitulo">O cadastro solicitado não existe ou o identificador é inválido.</p>
      <a class="btn" href="alunos.php">Voltar para alunos</a>
    <?php else: ?>
      <h1>Editar aluno</h1>
      <p class="subtitulo">Altere os dados do aluno e salve.</p>

      <?php if ($erro !== ''): ?>
        <p role="alert"><?= $escape($erro) ?></p>
      <?php endif; ?>

      <form class="formulario" method="post" action="editar.php?id=<?= $escape($id) ?>">
        <div class="campo campo-largo">
          <label for="nome">Nome completo</label>
          <input type="text" id="nome" name="nome" maxlength="50" value="<?= $escape($dados['nome']) ?>" required>
        </div>

        <div class="campo">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" maxlength="100" value="<?= $escape($dados['email']) ?>" required>
        </div>

        <div class="campo">
          <label for="telefone">Telefone</label>
          <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= $escape($dados['telefone']) ?>" required>
        </div>

        <div class="campo">
          <label for="dt_nascimento">Data de nascimento</label>
          <input type="date" id="dt_nascimento" name="dt_nascimento" value="<?= $escape($dados['dt_nascimento']) ?>" required>
        </div>

        <div class="campo">
          <label for="id_plano">Plano</label>
          <select id="id_plano" name="id_plano" required>
            <option value="">Selecione um plano</option>
            <?php foreach ($planos as $plano): ?>
              <option value="<?= $escape($plano['id']) ?>" <?= (string) $dados['id_plano'] === (string) $plano['id'] ? 'selected' : '' ?>>
                <?= $escape($plano['titulo']) ?> - R$ <?= number_format((float) $plano['valor'], 2, ',', '.') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="formulario-acoes">
          <button type="submit" class="btn">Salvar alterações</button>
          <a class="btn btn-secundario" href="alunos.php">Cancelar</a>
        </div>
      </form>
    <?php endif; ?>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

</body>
</html>
