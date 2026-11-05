#!/bin/bash
# Testes da API REST (XAMPP)

echo -e "\n--- 1. Sem token (401) ---"
curl -s -i http://localhost/projetoweb/src/api.php

echo -e "\n\n--- 2. GET - Listar todos (200) ---"
curl -s -i http://localhost/projetoweb/src/api.php \
  -H "Authorization: Bearer meu-token-secreto-123"

echo -e "\n\n--- 3. GET - Buscar por id existente (200) ---"
curl -s -i "http://localhost/projetoweb/src/api.php?id=1" \
  -H "Authorization: Bearer meu-token-secreto-123"

echo -e "\n\n--- 4. GET - Buscar por id inexistente (404) ---"
curl -s -i "http://localhost/projetoweb/src/api.php?id=999" \
  -H "Authorization: Bearer meu-token-secreto-123"

echo -e "\n\n--- 5. POST - Criar com campo vazio (400) ---"
curl -s -i -X POST http://localhost/projetoweb/src/api.php \
  -H "Authorization: Bearer meu-token-secreto-123" \
  -H "Content-Type: application/json" \
  -d '{"nome":""}'

echo -e "\n\n--- 6. POST - Criar novo aluno (201) ---"
curl -s -i -X POST http://localhost/projetoweb/src/api.php \
  -H "Authorization: Bearer meu-token-secreto-123" \
  -H "Content-Type: application/json" \
  -d '{"nome":"Maria","email":"maria@email.com","telefone":"51999999999","dt_nascimento":"2000-05-10","id_plano":1}'

echo -e "\n\n--- 7. PUT - Atualizar aluno (200) ---"
curl -s -i -X PUT "http://localhost/projetoweb/src/api.php?id=1" \
  -H "Authorization: Bearer meu-token-secreto-123" \
  -H "Content-Type: application/json" \
  -d '{"nome":"Ana Souza","email":"ana.souza@example.com","telefone":"(11) 99999-1001","dt_nascimento":"1998-04-12","id_plano":1}'

echo -e "\n\n--- 8. DELETE - Excluir aluno (200) ---"
curl -s -i -X DELETE "http://localhost/projetoweb/src/api.php?id=6" \
  -H "Authorization: Bearer meu-token-secreto-123"

echo -e "\n\n--- 9. Metodo nao permitido (405) ---"
curl -s -i -X PATCH http://localhost/projetoweb/src/api.php \
  -H "Authorization: Bearer meu-token-secreto-123"
echo ""
