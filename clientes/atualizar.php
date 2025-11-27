<?php
require "conexao.php";
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$con->query("UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone', cpf='$cpf', endereco='$endereco' WHERE id=$id");
header("Location: index.php");
?>