<?php
require_once __DIR__ . '/../config/conexao.php';

class AlunoDAO
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conectar();
    }

    public function inserir($nome, $email, $telefone, $dtNascimento, $idPlano)
    {
        $sql = $this->pdo->prepare(
            'INSERT INTO alunos (nome, email, telefone, dt_nascimento, id_plano)
             VALUES (?, ?, ?, ?, ?)'
        );
        $sql->execute([$nome, $email, $telefone, $dtNascimento, $idPlano]);

        return $this->pdo->lastInsertId();
    }

    public function listar()
    {
        $sql = $this->pdo->query(
            'SELECT alunos.*, planos.titulo AS plano, planos.valor AS valor_plano
             FROM alunos
             INNER JOIN planos ON planos.id = alunos.id_plano
             ORDER BY alunos.nome'
        );

        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare(
            'SELECT alunos.*, planos.titulo AS plano, planos.valor AS valor_plano
             FROM alunos
             INNER JOIN planos ON planos.id = alunos.id_plano
             WHERE alunos.id = ?'
        );
        $sql->execute([$id]);

        return $sql->fetch();
    }

    public function listarPlanos()
    {
        $sql = $this->pdo->query(
            'SELECT id, titulo, valor
             FROM planos
             ORDER BY titulo, id'
        );

        return $sql->fetchAll();
    }

    public function atualizar($id, $nome, $email, $telefone, $dtNascimento, $idPlano)
    {
        $sql = $this->pdo->prepare(
            'UPDATE alunos
             SET nome = ?, email = ?, telefone = ?, dt_nascimento = ?, id_plano = ?
             WHERE id = ?'
        );

        return $sql->execute([$nome, $email, $telefone, $dtNascimento, $idPlano, $id]);
    }

    public function excluir($id)
    {
        $sql = $this->pdo->prepare('DELETE FROM alunos WHERE id = ?');

        return $sql->execute([$id]);
    }
}
