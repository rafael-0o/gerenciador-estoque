<?php
require "conexao.php";
$dados = $con->query("SELECT * FROM clientes");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clientes - Pedro Henrique Fernandes</title>
</head>
<body>
<h2>Cadastro de Clientes - Programação Web - Professor Daniel</h2>
<a href="cadastrar.php">Cadastrar Cliente</a>
<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
    <th>CPF</th>
    <th>Endereço</th>
    <th>Ações</th>
</tr>
<?php while($c = $dados->fetch_assoc()) { ?>
<tr>
    <td><?php echo $c['id']; ?></td>
    <td><?php echo $c['nome']; ?></td>
    <td><?php echo $c['email']; ?></td>
    <td><?php echo $c['telefone']; ?></td>
    <td><?php echo $c['cpf']; ?></td>
    <td><?php echo $c['endereco']; ?></td>
    <td>
        <a href="editar.php?id=<?php echo $c['id']; ?>">Editar</a>
        <a href="excluir.php?id=<?php echo $c['id']; ?>">Excluir</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>