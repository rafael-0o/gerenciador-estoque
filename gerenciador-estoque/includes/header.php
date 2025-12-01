<?php
// Inicia sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui auth.php se ainda não foi incluído
if (!function_exists('isLoggedIn')) {
    $auth_path = __DIR__ . '/auth.php';
    if (file_exists($auth_path)) {
        require_once $auth_path;
    }
}

// Define função isLoggedIn se não existir (para páginas públicas)
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

// Calcula o caminho base relativo baseado no diretório do arquivo que incluiu este header
// Obtém o diretório raiz do projeto (um nível acima de includes)
$root_dir = realpath(dirname(__DIR__));
// Obtém o diretório do arquivo atual sendo executado
$current_dir = realpath(dirname($_SERVER['SCRIPT_FILENAME']));

// Calcula o caminho relativo do arquivo atual em relação à raiz do projeto
$relative_path = str_replace($root_dir, '', $current_dir);
$relative_path = str_replace('\\', '/', $relative_path);
$relative_path = trim($relative_path, '/');

// Conta quantos níveis de diretório existem no caminho relativo
if (empty($relative_path)) {
    // Está na raiz, não precisa voltar
    $depth = 0;
} else {
    // Conta as barras e adiciona 1 (pois cada segmento representa um nível)
    $depth = substr_count($relative_path, '/') + 1;
}

// Gera o caminho base com ../
$base_path = $depth > 0 ? str_repeat('../', $depth) : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_path; ?>css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">Estoque System</a>
            <div class="navbar-nav">
                <a class="nav-link" href="<?php echo $base_path; ?>index.php">Home</a>
                <a class="nav-link" href="<?php echo $base_path; ?>produtos.php">Produtos</a>
                <a class="nav-link" href="<?php echo $base_path; ?>contato.php">Contato</a>
                <?php if (isLoggedIn()): ?>
                    <a class="nav-link" href="<?php echo $base_path; ?>admin/dashboard.php">Painel Admin</a>
                    <a class="nav-link" href="<?php echo $base_path; ?>admin/logout.php">Sair</a>
                <?php else: ?>
                    <a class="nav-link" href="<?php echo $base_path; ?>admin/index.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">