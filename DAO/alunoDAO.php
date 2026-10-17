<?php
// DAO = Data Access Object
// Esta classe concentra TODO o SQL da tabela "cliente".
// Quem usa o DAO nao precisa saber SQL: so chama os metodos.

require_once 'conexao.php';

class ClienteDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    // C - Create: cadastra um cliente e devolve o id gerado
    public function inserir($nome, $email, $telefone)
    {
        $sql = $this->pdo->prepare('INSERT INTO ALUNOS (nome, email, telefone) VALUES (?, ?, ?)');
        $sql->execute([$nome, $email, $telefone]);

        return $this->pdo->lastInsertId();
    }

    // R - Read: devolve todos os clientes
    public function listar()
    {
        $sql = $this->pdo->query('SELECT * FROM ALUNOS ORDER BY nome');

        return $sql->fetchAll();
    }

    // R - Read: devolve um cliente pelo id (ou false se nao existir)
    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare('SELECT * FROM ALUNOS WHERE id = ?');
        $sql->execute([$id]);

        return $sql->fetch();
    }

    // U - Update: altera os dados de um cliente
    public function atualizar($id, $nome, $email, $telefone)
    {
        $sql = $this->pdo->prepare('UPDATE ALUNOS SET nome = ?, email = ?, telefone = ? WHERE id = ?');

        return $sql->execute([$nome, $email, $telefone, $id]);
    }

    // D - Delete: remove um cliente
    public function excluir($id)
    {
        $sql = $this->pdo->prepare('DELETE FROM ALUNOS WHERE id = ?');

        return $sql->execute([$id]);
    }
}
