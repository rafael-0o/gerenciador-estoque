<?php
$base_url = '/projeto-gerenciamentodeestoque/estoque-system';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url; ?>/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php">Estoque System</a>
            <div class="navbar-nav">
                <a class="nav-link" href="<?php echo $base_url; ?>/index.php">Home</a>
                <a class="nav-link" href="<?php echo $base_url; ?>/produtos.php">Produtos</a>
                <a class="nav-link" href="<?php echo $base_url; ?>/contato.php">Contato</a>
                <?php if (isLoggedIn()): ?>
                    <a class="nav-link" href="<?php echo $base_url; ?>/admin/dashboard.php">Painel Admin</a>
                    <a class="nav-link" href="<?php echo $base_url; ?>/admin/logout.php">Sair</a>
                <?php else: ?>
                    <a class="nav-link" href="<?php echo $base_url; ?>/admin/index.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">