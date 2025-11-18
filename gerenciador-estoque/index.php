<?php include 'includes/header.php'; ?>
<?php include 'includes/database.php'; ?>

<h2>Bem-vindo ao Sistema de Estoque</h2>

<?php
$total_produtos = $pdo->query("SELECT COUNT(*) as total FROM produtos")->fetch()['total'];
$estoque_baixo = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE quantidade <= quantidade_minima")->fetch()['total'];
?>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h4><?php echo $total_produtos; ?></h4>
                <p>Total de Produtos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h4><?php echo $estoque_baixo; ?></h4>
                <p>Estoque Baixo</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <a href="produtos.php" class="btn btn-primary btn-lg w-100">Ver Produtos</a>
    </div>
    <div class="col-md-6">
        <a href="admin/index.php" class="btn btn-success btn-lg w-100">Painel Admin</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>