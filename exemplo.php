<?php
require_once __DIR__ . '/DAO/alunoDAO.php';

$dao = new AlunoDAO();
$alunos = $dao->listar();

$aluno = null;
$mensagem = '';


if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id < 1) {
        $mensagem = 'Informe um ID válido.';
    } else {
        $aluno = $dao->buscarPorId($id);

        if (!$aluno) {
            $mensagem = 'Aluno não encontrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Teste do AlunoDAO</title>
</head>
<body>
  <h1>Teste do AlunoDAO</h1>

  <form method="get">
    <label>Buscar aluno por ID:</label>
    <input type="number" name="id" min="1">
    <button type="submit">Buscar</button>
  </form>

  <?php if ($mensagem != '') { ?>
    <p><?php echo $mensagem; ?></p>
  <?php } ?>

  <?php if ($aluno) { ?>
    <h2>Aluno encontrado</h2>
    <p>ID: <?php echo $aluno['id']; ?></p>
    <p>Nome: <?php echo ($aluno['nome']); ?></p>
    <p>E-mail: <?php echo ($aluno['email']); ?></p>
    <p>Plano: <?php echo ($aluno['plano']); ?></p>
  <?php } ?>

  <h2>Alunos cadastrados</h2>
  <?php foreach ($alunos as $item) { ?>
    <p><?php echo $item['id']; ?> - <?php echo ($item['nome']); ?> (<?php echo ($item['plano']); ?>)</p>
  <?php } ?>
</body>
</html>