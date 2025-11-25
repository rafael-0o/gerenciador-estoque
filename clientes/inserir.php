<?php
require "conexao.php";
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$con->query("INSERT INTO clientes (nome, email, telefone, cpf, endereco) VALUES ('$nome', '$email', '$telefone', '$cpf', '$endereco')");
header("Location: index.php");
?>