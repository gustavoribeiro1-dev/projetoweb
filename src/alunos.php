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

    <p id="mensagem" role="status" hidden></p>

    <form id="form-busca" class="busca">
      <label for="busca-id">Buscar aluno por ID</label>
      <input type="number" id="busca-id" name="id" min="1" required>
      <button type="submit" class="btn">Buscar</button>
      <button type="button" id="limpar-busca" class="btn btn-secundario" hidden>Limpar</button>
    </form>

    <article id="resultado-busca" class="card resultado-busca" hidden>
      <h2>Aluno encontrado</h2>
      <dl>
        <dt>ID</dt>                 <dd data-campo="id"></dd>
        <dt>Nome</dt>               <dd data-campo="nome"></dd>
        <dt>E-mail</dt>             <dd data-campo="email"></dd>
        <dt>Telefone</dt>           <dd data-campo="telefone"></dd>
        <dt>Data de nascimento</dt> <dd data-campo="dt_nascimento"></dd>
        <dt>Plano</dt>              <dd data-campo="plano"></dd>
      </dl>
      <a id="resultado-editar" class="btn btn-pequeno" href="#">Editar</a>
    </article>

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
        <tbody id="lista-alunos">
          <tr>
            <td colspan="6">Carregando alunos...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet &middot; Univates</p>
  </footer>

  <script src="../assets/api.js"></script>
  <script>
    const mensagem = document.getElementById('mensagem');
    const lista = document.getElementById('lista-alunos');
    const formBusca = document.getElementById('form-busca');
    const campoBusca = document.getElementById('busca-id');
    const botaoLimpar = document.getElementById('limpar-busca');
    const resultado = document.getElementById('resultado-busca');

    // Mensagens que chegam pela URL depois de cadastrar ou editar (ex.: alunos.php?status=cadastrado)
    const mensagensStatus = {
      cadastrado: 'Aluno cadastrado com sucesso.',
      atualizado: 'Dados do aluno atualizados com sucesso.'
    };
    const status = new URLSearchParams(location.search).get('status');
    if (mensagensStatus[status]) {
      mostrarMensagem(mensagem, mensagensStatus[status], 'sucesso');
    }

    // Cria uma célula <td> com o texto informado
    function criarCelula(texto) {
      const celula = document.createElement('td');
      celula.textContent = texto;
      return celula;
    }

    // Mostra uma única linha na tabela (usado para "carregando", "vazio" e erros)
    function mostrarLinhaUnica(texto) {
      const linha = document.createElement('tr');
      const celula = criarCelula(texto);
      celula.colSpan = 6;
      linha.append(celula);
      lista.replaceChildren(linha);
    }

    // GET api.php -> lista todos os alunos
    async function carregarAlunos() {
      try {
        const { status, dados } = await chamarApi('GET');

        if (status !== 200) {
          mostrarLinhaUnica('Erro ao carregar os alunos: ' + dados.erro);
          return;
        }

        if (dados.length === 0) {
          mostrarLinhaUnica('Ainda não há alunos cadastrados.');
          return;
        }

        lista.replaceChildren();

        for (const aluno of dados) {
          const linha = document.createElement('tr');

          linha.append(
            criarCelula(aluno.id),
            criarCelula(aluno.nome),
            criarCelula(aluno.email),
            criarCelula(aluno.telefone),
            criarCelula(aluno.plano + ' - ' + formatarMoeda(aluno.valor_plano))
          );

          const acoes = document.createElement('td');
          acoes.className = 'acoes';

          const editar = document.createElement('a');
          editar.className = 'btn btn-pequeno';
          editar.href = 'editar.php?id=' + aluno.id;
          editar.textContent = 'Editar';

          const excluir = document.createElement('button');
          excluir.type = 'button';
          excluir.className = 'btn btn-pequeno btn-perigo';
          excluir.textContent = 'Excluir';
          excluir.addEventListener('click', () => excluirAluno(aluno.id));

          acoes.append(editar, excluir);
          linha.append(acoes);
          lista.append(linha);
        }
      } catch (erro) {
        mostrarLinhaUnica('Não foi possível conectar à API.');
      }
    }

    // DELETE api.php?id=X -> exclui o aluno
    async function excluirAluno(id) {
      if (!confirm('Deseja realmente excluir este aluno?')) {
        return;
      }

      try {
        const { status, dados } = await chamarApi('DELETE', '?id=' + id);

        if (status === 200) {
          mostrarMensagem(mensagem, 'Aluno excluído com sucesso.', 'sucesso');
          limparBusca();
          carregarAlunos();
        } else {
          mostrarMensagem(mensagem, 'Não foi possível excluir: ' + dados.erro, 'erro');
        }
      } catch (erro) {
        mostrarMensagem(mensagem, 'Não foi possível conectar à API.', 'erro');
      }
    }

    // GET api.php?id=X -> busca um aluno pelo ID
    formBusca.addEventListener('submit', async (evento) => {
      evento.preventDefault();
      esconderMensagem(mensagem);
      resultado.hidden = true;
      botaoLimpar.hidden = false;

      try {
        const { status, dados } = await chamarApi('GET', '?id=' + encodeURIComponent(campoBusca.value));

        if (status !== 200) {
          mostrarMensagem(mensagem, dados.erro, 'erro');
          return;
        }

        const valores = {
          id: dados.id,
          nome: dados.nome,
          email: dados.email,
          telefone: dados.telefone,
          dt_nascimento: formatarData(dados.dt_nascimento),
          plano: dados.plano + ' - ' + formatarMoeda(dados.valor_plano)
        };

        for (const campo in valores) {
          resultado.querySelector('[data-campo="' + campo + '"]').textContent = valores[campo];
        }
        document.getElementById('resultado-editar').href = 'editar.php?id=' + dados.id;
        resultado.hidden = false;
      } catch (erro) {
        mostrarMensagem(mensagem, 'Não foi possível conectar à API.', 'erro');
      }
    });

    function limparBusca() {
      formBusca.reset();
      resultado.hidden = true;
      botaoLimpar.hidden = true;
    }

    botaoLimpar.addEventListener('click', () => {
      limparBusca();
      esconderMensagem(mensagem);
    });

    carregarAlunos();
  </script>

</body>
</html>
