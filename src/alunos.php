<?php
require_once __DIR__ . '/../DAO/alunoDAO.php';

$dao = new AlunoDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) && is_string($_POST['id'])
        ? filter_var($_POST['id'], FILTER_VALIDATE_INT)
        : false;

    if ($id === false || $id < 1) {
        http_response_code(400);
        $status = 'erro';
    } else {
        $dao->excluir($id);
        header('Location: alunos.php?status=excluido');
        exit;
    }
} else {
    $status = isset($_GET['status']) && is_string($_GET['status']) ? $_GET['status'] : '';
}

$mensagens = [
    'cadastrado' => 'Aluno cadastrado com sucesso.',
    'atualizado' => 'Dados do aluno atualizados com sucesso.',
    'excluido' => 'Aluno excluído com sucesso.',
    'erro' => 'Não foi possível excluir: informe um aluno válido.',
];
$alunos = $dao->listar();
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitPlus Academia - Alunos</title>
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
    <div class="cabecalho-pagina">
      <div>
        <h1>Alunos</h1>
        <p class="subtitulo">Lista de todos os alunos cadastrados.</p>
      </div>
      <a class="btn" href="cadastro.php">+ Novo aluno</a>
    </div>

    <?php if (isset($mensagens[$status])): ?>
      <p role="status"><?= $escape($mensagens[$status]) ?></p>
    <?php endif; ?>

    <div class="tabela-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Plano</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($alunos === []): ?>
            <tr>
              <td colspan="6">Ainda não há alunos cadastrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($alunos as $aluno): ?>
              <tr>
                <td><?= $escape($aluno['id']) ?></td>
                <td><?= $escape($aluno['nome']) ?></td>
                <td><?= $escape($aluno['email']) ?></td>
                <td><?= $escape($aluno['telefone']) ?></td>
                <td><?= $escape($aluno['plano']) ?> - R$ <?= number_format((float) $aluno['valor_plano'], 2, ',', '.') ?></td>
                <td class="acoes">
                  <a class="btn btn-pequeno" href="editar.php?id=<?= $escape($aluno['id']) ?>">Editar</a>
                  <form method="post" action="alunos.php" onsubmit="return confirm('Deseja realmente excluir este aluno?');">
                    <input type="hidden" name="id" value="<?= $escape($aluno['id']) ?>">
                    <button type="submit" class="btn btn-pequeno btn-perigo">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

</body>
</html>
