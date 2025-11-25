<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "sistema_clientes";

$con = new mysqli($servidor, $usuario, $senha);
if ($con->connect_error) {
    die("Erro na conexão");
}

$con->query("CREATE DATABASE IF NOT EXISTS $banco");
$con->select_db($banco);

$sql_tabela = "CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(30),
    cpf VARCHAR(20),
    endereco VARCHAR(200),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$con->query($sql_tabela);
?>