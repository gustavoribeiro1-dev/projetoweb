<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitPlus Academia - Início</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container">
      <a class="logo" href="index.php">Fit <span>Plus</span></a>
      <nav>
        <ul class="menu">
          <li><a class="ativo" href="index.php">Início</a></li>
          <li><a href="alunos.php">Alunos</a></li>
          <li><a href="cadastro.php">Novo aluno</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="hero">
      <h1>Gestão de alunos e planos</h1>
      <p>Cadastre, consulte, edite e exclua os alunos da academia e acompanhe o plano de cada um.</p>
      <a class="btn" href="cadastro.php">Cadastrar aluno</a>
    </section>

    <section class="cards">
      <article class="card">
        <h2>Listagem</h2>
        <p>Veja todos os alunos cadastrados com seus respectivos planos.</p>
        <a class="btn btn-secundario" href="alunos.php">Ver alunos</a>
      </article>

      <article class="card">
        <h2>Cadastro</h2>
        <p>Adicione um novo aluno e escolha o plano contratado.</p>
        <a class="btn btn-secundario" href="cadastro.php">Novo aluno</a>
      </article>

      <article class="card">
        <h2>Edição</h2>
        <p>Atualize os dados ou troque o plano de um aluno já cadastrado.</p>
        <a class="btn btn-secundario" href="alunos.php">Escolher aluno</a>
      </article>
    </section>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

</body>
</html>
