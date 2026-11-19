#!/bin/bash
# Testes da API REST (XAMPP)

URL="http://localhost/projetoweb/src/api.php"
TOKEN="Authorization: Bearer meu-token-secreto-123"
JSON="Content-Type: application/json"

echo -e "\n--- 1. Sem token (401) ---"
curl -s -i "$URL"

echo -e "\n\n--- 2. GET - Listar todos (200) ---"
curl -s -i "$URL" -H "$TOKEN"

echo -e "\n\n--- 3. GET - Buscar por id existente (200) ---"
curl -s -i "$URL?id=1" -H "$TOKEN"

echo -e "\n\n--- 4. GET - Buscar por id inexistente (404) ---"
curl -s -i "$URL?id=999" -H "$TOKEN"

echo -e "\n\n--- 5. GET - Buscar por id invalido (400) ---"
curl -s -i "$URL?id=abc" -H "$TOKEN"

echo -e "\n\n--- 6. GET - Listar planos (200) ---"
curl -s -i "$URL?planos" -H "$TOKEN"

echo -e "\n\n--- 7. POST - Criar com campo vazio (400) ---"
curl -s -i -X POST "$URL" -H "$TOKEN" -H "$JSON" \
  -d '{"nome":""}'

echo -e "\n\n--- 8. POST - Criar com e-mail invalido (400) ---"
curl -s -i -X POST "$URL" -H "$TOKEN" -H "$JSON" \
  -d '{"nome":"Maria","email":"maria","telefone":"51999999999","dt_nascimento":"2000-05-10","id_plano":1}'

echo -e "\n\n--- 9. POST - Criar novo aluno (201) ---"
RESPOSTA=$(curl -s -i -X POST "$URL" -H "$TOKEN" -H "$JSON" \
  -d '{"nome":"Maria","email":"maria@email.com","telefone":"51999999999","dt_nascimento":"2000-05-10","id_plano":1}')
echo "$RESPOSTA"
# Guarda o id do aluno criado para usar no teste de exclusao
NOVO_ID=$(echo "$RESPOSTA" | grep -o '"id":[0-9]*' | grep -o '[0-9]*')

echo -e "\n\n--- 10. PUT - Atualizar aluno (200) ---"
curl -s -i -X PUT "$URL?id=1" -H "$TOKEN" -H "$JSON" \
  -d '{"nome":"Ana Souza","email":"ana.souza@example.com","telefone":"(11) 99999-1001","dt_nascimento":"1998-04-12","id_plano":1}'

echo -e "\n\n--- 11. PUT - Atualizar aluno inexistente (404) ---"
curl -s -i -X PUT "$URL?id=999" -H "$TOKEN" -H "$JSON" \
  -d '{"nome":"Ninguem","email":"ninguem@email.com","telefone":"51999999999","dt_nascimento":"2000-05-10","id_plano":1}'

echo -e "\n\n--- 12. DELETE - Excluir o aluno criado no teste 9 (200) ---"
curl -s -i -X DELETE "$URL?id=$NOVO_ID" -H "$TOKEN"

echo -e "\n\n--- 13. Metodo nao permitido (405) ---"
curl -s -i -X PATCH "$URL" -H "$TOKEN"
echo ""
