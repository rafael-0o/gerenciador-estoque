<?php
// Arquivo de teste para debug
include 'includes/database.php';

echo "<h1>Teste de Conexão e Banco de Dados</h1>";

try {
    echo "<p><strong>✓ Conexão com o banco de dados OK</strong></p>";
    
    // Testa se a tabela usuarios existe
    try {
        $result = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $count = $result->fetch()['total'];
        echo "<p><strong>✓ Tabela 'usuarios' existe</strong></p>";
        echo "<p>Total de usuários: $count</p>";
        
        // Lista todos os usuários
        echo "<h2>Usuários cadastrados:</h2>";
        $usuarios = $pdo->query("SELECT id, nome, email, nivel_acesso FROM usuarios");
        foreach ($usuarios as $user) {
            echo "<p>ID: {$user['id']}, Nome: {$user['nome']}, Email: {$user['email']}, Acesso: {$user['nivel_acesso']}</p>";
        }
    } catch (PDOException $e) {
        echo "<p><strong style='color: red;'>✗ Erro ao acessar tabela 'usuarios':</strong> " . $e->getMessage() . "</p>";
    }
    
    // Testa outras tabelas
    $tables = ['produtos', 'fornecedores', 'clientes', 'movimentacoes', 'categorias'];
    echo "<h2>Status de outras tabelas:</h2>";
    foreach ($tables as $table) {
        try {
            $pdo->query("SELECT 1 FROM $table LIMIT 1");
            echo "<p><strong>✓</strong> Tabela '$table' existe</p>";
        } catch (PDOException $e) {
            echo "<p><strong style='color: orange;'>✗</strong> Tabela '$table' não existe</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p><strong style='color: red;'>✗ Erro na conexão:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='admin/index.php'>Voltar para Login</a></p>";
?>
