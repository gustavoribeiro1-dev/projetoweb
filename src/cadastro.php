<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitPlus Academia - Novo aluno</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container">
      <a class="logo" href="index.php">Fit <span>Plus</span></a>
      <nav>
        <ul class="menu">
          <li><a href="index.php">Início</a></li>
          <li><a href="alunos.php">Alunos</a></li>
          <li><a class="ativo" href="cadastro.php">Novo aluno</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <h1>Novo aluno</h1>
    <p class="subtitulo">Preencha os dados para cadastrar um aluno.</p>

    <p id="mensagem" role="alert" hidden></p>

    <form id="form-aluno" class="formulario">
      <div class="campo campo-largo">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" maxlength="50" required>
      </div>

      <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" maxlength="100" required>
      </div>

      <div class="campo">
        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" maxlength="20" required>
      </div>

      <div class="campo">
        <label for="dt_nascimento">Data de nascimento</label>
        <input type="date" id="dt_nascimento" name="dt_nascimento" required>
      </div>

      <div class="campo">
        <label for="id_plano">Plano</label>
        <select id="id_plano" name="id_plano" required>
          <option value="">Carregando planos...</option>
        </select>
      </div>

      <div class="formulario-acoes">
        <button type="submit" class="btn">Cadastrar</button>
        <a class="btn btn-secundario" href="alunos.php">Cancelar</a>
      </div>
    </form>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

  <script src="../assets/api.js"></script>
  <script>
    const formulario = document.getElementById('form-aluno');
    const mensagem = document.getElementById('mensagem');
    const botao = formulario.querySelector('button[type="submit"]');

    carregarPlanos(formulario.elements.id_plano).catch(() => {
      mostrarMensagem(mensagem, 'Não foi possível carregar os planos.', 'erro');
    });

    // Cadastra o aluno ao enviar o formulário
    formulario.addEventListener('submit', async (evento) => {
      evento.preventDefault();
      botao.disabled = true;

      try {
        const { status, dados } = await chamarApi('POST', '', lerFormulario(formulario));

        if (status === 201) {
          location.href = 'alunos.php?status=cadastrado';
          return;
        }

        mostrarMensagem(mensagem, dados.erro, 'erro');
      } catch (erro) {
        mostrarMensagem(mensagem, 'Não foi possível conectar à API.', 'erro');
      }

      botao.disabled = false;
    });
  </script>

</body>
</html>
