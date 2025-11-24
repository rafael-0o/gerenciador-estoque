<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();
include '../includes/header.php';
?>

<h2>Dashboard</h2>

<?php
$total_produtos = $pdo->query("SELECT COUNT(*) as total FROM produtos")->fetch()['total'];
$total_categorias = $pdo->query("SELECT COUNT(*) as total FROM categorias")->fetch()['total'];
$estoque_baixo = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE quantidade_estoque = 0")->fetch()['total'];
$valor_total = $pdo->query("SELECT SUM(preco_custo * quantidade_estoque) as total FROM produtos")->fetch()['total'];
?>

<div class="row mt-4">
    <div class="col-md-3"><div class="card bg-primary text-white text-center p-3"><h4><?php echo $total_produtos; ?></h4><p>Produtos</p></div></div>
    <div class="col-md-3"><div class="card bg-success text-white text-center p-3"><h4><?php echo $total_categorias; ?></h4><p>Categorias</p></div></div>
    <div class="col-md-3"><div class="card bg-warning text-white text-center p-3"><h4><?php echo $estoque_baixo; ?></h4><p>Estoque Baixo</p></div></div>
    <div class="col-md-3"><div class="card bg-info text-white text-center p-3"><h4>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></h4><p>Valor Total</p></div></div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Menu Admin</h5></div>
            <div class="card-body">
                <a href="produtos.php" class="btn btn-primary w-100 mb-2">Gerenciar Produtos</a>
                <a href="categorias.php" class="btn btn-success w-100 mb-2">Gerenciar Categorias</a>
                <a href="relatorios.php" class="btn btn-info w-100 mb-2">Relatórios</a>
                <a href="usuarios.php" class="btn btn-warning w-100">Gerenciar Usuários</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
