// Funções usadas pelas páginas do site para conversar com a API REST (src/api.php).
//
// Atenção: como o token fica no JavaScript, qualquer pessoa que abrir o código
// da página consegue vê-lo. Para o projeto da disciplina isso é suficiente, mas
// num sistema real a autenticação seria feita com login (sessão ou token por usuário).
const API_URL = 'api.php';
const API_TOKEN = 'meu-token-secreto-123';

// Faz uma requisição para a API e devolve { status, dados }
//   metodo:   'GET', 'POST', 'PUT' ou 'DELETE'
//   consulta: parâmetros da URL, ex.: '?id=3' ou '?planos'
//   corpo:    objeto enviado como JSON (usado no POST e no PUT)
async function chamarApi(metodo, consulta = '', corpo = null) {
  const opcoes = {
    method: metodo,
    headers: { 'Authorization': 'Bearer ' + API_TOKEN }
  };

  if (corpo !== null) {
    opcoes.headers['Content-Type'] = 'application/json';
    opcoes.body = JSON.stringify(corpo);
  }

  const resposta = await fetch(API_URL + consulta, opcoes);
  const dados = await resposta.json();

  return { status: resposta.status, dados: dados };
}

// Mostra uma mensagem na tela. tipo: 'sucesso' ou 'erro'
function mostrarMensagem(elemento, texto, tipo) {
  elemento.textContent = texto;
  elemento.className = 'mensagem mensagem-' + tipo;
  elemento.hidden = false;
}

function esconderMensagem(elemento) {
  elemento.hidden = true;
}

// 150 -> "R$ 150,00"
function formatarMoeda(valor) {
  return Number(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// "1998-04-12" -> "12/04/1998"
function formatarData(data) {
  const [ano, mes, dia] = data.split('-');
  return dia + '/' + mes + '/' + ano;
}
