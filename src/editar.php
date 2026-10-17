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
      <a class="logo" href="index.html">Fit Plus</a>
      <nav>
        <ul class="menu">
          <li><a href="index.html">Início</a></li>
          <li><a class="ativo" href="alunos.html">Alunos</a></li>
          <li><a href="cadastro.html">Novo aluno</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <h1>Editar aluno</h1>
    <p class="subtitulo">Altere os dados do aluno e salve.</p>
 
    <form class="formulario">
      <div class="campo campo-largo">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" required>
      </div>
 
      <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
 
      <div class="campo">
        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone">
      </div>
 
      <div class="campo">
        <label for="data_nascimento">Data de nascimento</label>
        <input type="date" id="data_nascimento" name="data_nascimento">
      </div>
 
      <div class="campo">
        <label for="plano">Plano</label>
        <select id="plano" name="plano" required>
          <option value="">Selecione um plano</option>
        </select>
      </div>
 
      <div class="formulario-acoes">
        <button type="submit" class="btn">Salvar alterações</button>
        <a class="btn btn-secundario" href="alunos.html">Cancelar</a>
      </div>
    </form>
  </main>

  <footer class="site-footer">
    <p>&copy; 2026 FitPlus Academia &middot; Projeto do Semestre &middot; Desenvolvimento de Aplicações para Internet
      &middot; Univates</p>
  </footer>
</body>

</html>