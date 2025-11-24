<?php
require "conexao.php";
$id = $_GET['id'];
$con->query("DELETE FROM clientes WHERE id=$id");
header("Location: index.php");
?>