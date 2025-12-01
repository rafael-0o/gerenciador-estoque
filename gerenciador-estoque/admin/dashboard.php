<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();
include '../includes/header.php';
?>

<h2>Dashboard</h2>

<?php
$total_produtos = $pdo->query("SELECT COUNT(*) as total FROM produtos")->fetch()['total'];
$total_fornecedores = $pdo->query("SELECT COUNT(*) as total FROM fornecedores")->fetch()['total'];
$total_clientes = $pdo->query("SELECT COUNT(*) as total FROM clientes")->fetch()['total'];
$estoque_baixo = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE quantidade_estoque = 0")->fetch()['total'];
$valor_total = $pdo->query("SELECT SUM(preco_custo * quantidade_estoque) as total FROM produtos")->fetch()['total'] ?? 0;
?>

<div class="row mt-4">
    <div class="col-md-3"><div class="card text-center p-3"><h4><?php echo $total_produtos; ?></h4><p>Produtos</p></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h4><?php echo $total_fornecedores; ?></h4><p>Fornecedores</p></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h4><?php echo $total_clientes; ?></h4><p>Clientes</p></div></div>
    <div class="col-md-3"><div class="card text-center p-3"><h4><?php echo $estoque_baixo; ?></h4><p>Estoque Baixo</p></div></div>
</div>
<div class="row mt-4">
    <div class="col-md-12"><div class="card text-center p-3"><h4>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></h4><p>Valor Total em Estoque</p></div></div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Menu Admin</h5></div>
            <div class="card-body">
                <a href="produtos/index.php" class="btn btn-primary w-100 mb-2">Gerenciar Produtos</a>
                <a href="fornecedores/index.php" class="btn btn-success w-100 mb-2">Gerenciar Fornecedores</a>
                <a href="clientes/index.php" class="btn btn-info w-100 mb-2">Gerenciar Clientes</a>
                <a href="movimentacoes/index.php" class="btn btn-warning w-100 mb-2">Movimentações</a>
                <a href="relatorios.php" class="btn btn-secondary w-100">Relatórios</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
