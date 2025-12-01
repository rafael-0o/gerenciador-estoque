<?php
session_start();

echo "<h1>Teste de Login</h1>";

echo "<h2>Dados POST recebidos:</h2>";
if ($_POST) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "<p>Nenhum dado POST recebido</p>";
}

echo "<h2>Teste de Formulário:</h2>";
echo "<form method='POST'>";
echo "Email: <input type='email' name='email' value='admin@estoque.com' required><br>";
echo "Senha: <input type='password' name='senha' value='admin123' required><br>";
echo "<button type='submit'>Testar POST</button>";
echo "</form>";

echo "<hr>";
echo "<p><a href='index.php'>Voltar para Login</a></p>";
?>
