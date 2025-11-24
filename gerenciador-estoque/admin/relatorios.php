<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();
include '../includes/header.php';
?>

<h2>Relatórios</h2>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Produtos Esgotados</h5></div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>Produto</th><th>Estoque</th></tr></thead>
                    <tbody>
                        <?php foreach($pdo->query("SELECT * FROM produtos WHERE quantidade_estoque = 0 ORDER BY nome") as $prod): ?>
                        <tr>
                            <td><?php echo $prod['nome']; ?></td>
                            <td class="text-danger fw-bold"><?php echo (int)$prod['quantidade_estoque']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
