<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitPlus Academia - Editar aluno</title>
  <link rel="stylesheet" href="assets/style.css">
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
    <p id="carregando">Carregando dados do aluno...</p>

    <section id="nao-encontrado" hidden>
      <h1>Aluno não encontrado</h1>
      <p class="subtitulo">O cadastro solicitado não existe ou o identificador é inválido.</p>
      <a class="btn" href="alunos.php">Voltar para alunos</a>
    </section>

    <section id="area-edicao" hidden>
      <h1>Editar aluno</h1>
      <p class="subtitulo">Altere os dados do aluno e salve.</p>

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
          <button type="submit" class="btn">Salvar alterações</button>
          <a class="btn btn-secundario" href="alunos.php">Cancelar</a>
        </div>
      </form>
    </section>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

  <script src="assets/api.js"></script>
  <script>
    const id = new URLSearchParams(location.search).get('id');
    const carregando = document.getElementById('carregando');
    const naoEncontrado = document.getElementById('nao-encontrado');
    const areaEdicao = document.getElementById('area-edicao');
    const formulario = document.getElementById('form-aluno');
    const mensagem = document.getElementById('mensagem');
    const botao = formulario.querySelector('button[type="submit"]');

    // Mostra o aviso de aluno não encontrado
    function mostrarNaoEncontrado() {
      carregando.hidden = true;
      naoEncontrado.hidden = false;
    }

    // Busca o aluno na API e preenche o formulário
    async function carregarAluno() {
      if (!id) {
        mostrarNaoEncontrado();
        return;
      }

      try {
        const { status, dados: aluno } = await chamarApi('GET', '?id=' + encodeURIComponent(id));

        if (status !== 200) {
          mostrarNaoEncontrado();
          return;
        }

        await carregarPlanos(formulario.elements.id_plano);

        formulario.elements.nome.value = aluno.nome;
        formulario.elements.email.value = aluno.email;
        formulario.elements.telefone.value = aluno.telefone;
        formulario.elements.dt_nascimento.value = aluno.dt_nascimento;
        formulario.elements.id_plano.value = aluno.id_plano;

        carregando.hidden = true;
        areaEdicao.hidden = false;
      } catch (erro) {
        carregando.textContent = 'Não foi possível conectar à API.';
      }
    }

    // Salva as alterações ao enviar o formulário
    formulario.addEventListener('submit', async (evento) => {
      evento.preventDefault();
      botao.disabled = true;

      try {
        const { status, dados } = await chamarApi('PUT', '?id=' + encodeURIComponent(id), lerFormulario(formulario));

        if (status === 200) {
          location.href = 'alunos.php?status=atualizado';
          return;
        }

        mostrarMensagem(mensagem, dados.erro, 'erro');
      } catch (erro) {
        mostrarMensagem(mensagem, 'Não foi possível conectar à API.', 'erro');
      }

      botao.disabled = false;
    });

    carregarAluno();
  </script>

</body>
</html>
