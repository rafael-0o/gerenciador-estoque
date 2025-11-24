<?php
require "conexao.php";
$id = $_GET['id'];
$dados = $con->query("SELECT * FROM clientes WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Cliente</title>
</head>
<body>
<h2>Editar Cliente</h2>
<form action="atualizar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
    Nome: <input type="text" name="nome" value="<?php echo $dados['nome']; ?>" required><br><br>
    Email: <input type="email" name="email" value="<?php echo $dados['email']; ?>" required><br><br>
    Telefone: <input type="text" name="telefone" value="<?php echo $dados['telefone']; ?>"><br><br>
    CPF: <input type="text" name="cpf" value="<?php echo $dados['cpf']; ?>"><br><br>
    Endereço: <input type="text" name="endereco" value="<?php echo $dados['endereco']; ?>"><br><br>
    <input type="submit" value="Atualizar">
</form>
<br>
<a href="index.php">Voltar</a>
</body>
</html>