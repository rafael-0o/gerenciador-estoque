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
            <div class="card-header"><h5>Estoque Baixo</h5></div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>Produto</th><th>Estoque</th><th>Mínimo</th></tr></thead>
                    <tbody>
                        <?php foreach($pdo->query("SELECT * FROM produtos WHERE quantidade <= quantidade_minima ORDER BY quantidade") as $prod): ?>
                        <tr>
                            <td><?php echo $prod['nome']; ?></td>
                            <td class="text-danger fw-bold"><?php echo $prod['quantidade']; ?></td>
                            <td><?php echo $prod['quantidade_minima']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>