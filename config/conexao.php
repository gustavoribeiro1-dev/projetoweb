<?php
// Responsavel APENAS por conectar no banco de dados.
// Qualquer arquivo que precise do banco chama: $pdo = conectar();

function conectar()
{
    $host    = 'localhost';
    $banco   = 'academia_fit_plus';
    $usuario = 'root';
    $senha   = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);

        // Se der erro no SQL, o PHP lanca uma excecao (fica mais facil de ver o problema)
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Os resultados voltam como array associativo: $linha['nome']
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    } catch (PDOException $e) {
        die('Erro ao conectar no banco: ' . $e->getMessage());
    }
}
