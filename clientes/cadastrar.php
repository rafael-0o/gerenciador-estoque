<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Cliente</title>
</head>
<body>
<h2>Novo Cliente</h2>
<form action="inserir.php" method="post">
    Nome: <input type="text" name="nome" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Telefone: <input type="text" name="telefone"><br><br>
    CPF: <input type="text" name="cpf"><br><br>
    Endereço: <input type="text" name="endereco"><br><br>
    <input type="submit" value="Salvar">
</form>
<br>
<a href="index.php">Voltar</a>
</body>
</html>