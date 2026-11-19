const API_URL = 'api.php';
const API_TOKEN = 'meu-token-secreto-123';

// Faz uma requisição para a API e devolve o status e os dados
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

// Mostra uma mensagem de sucesso ou de erro na tela
function mostrarMensagem(elemento, texto, tipo) {
  elemento.textContent = texto;
  elemento.className = 'mensagem mensagem-' + tipo;
  elemento.hidden = false;
}

// Esconde a mensagem da tela
function esconderMensagem(elemento) {
  elemento.hidden = true;
}

// Formata um valor em reais
function formatarMoeda(valor) {
  return Number(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

// Converte a data de AAAA-MM-DD para DD/MM/AAAA
function formatarData(data) {
  const [ano, mes, dia] = data.split('-');
  return dia + '/' + mes + '/' + ano;
}

// Transforma os campos do formulário em um objeto
function lerFormulario(formulario) {
  return Object.fromEntries(new FormData(formulario));
}

// Busca os planos na API e preenche o select
async function carregarPlanos(select) {
  const { dados: planos } = await chamarApi('GET', '?planos');

  select.innerHTML = '';
  select.add(new Option('Selecione um plano', ''));

  for (const plano of planos) {
    select.add(new Option(plano.titulo + ' - ' + formatarMoeda(plano.valor), plano.id));
  }
}
