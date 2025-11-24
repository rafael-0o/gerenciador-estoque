<?php include 'includes/header.php'; ?>
<?php include 'includes/database.php'; ?>

<h2>Catálogo de Produtos</h2>

<?php
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome");
?>

<div class="table-responsive mt-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produto = $stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                <td>R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                <td class="<?php echo (int)$produto['quantidade_estoque'] == 0 ? 'text-danger fw-bold' : ''; ?>">
                    <?php echo (int)$produto['quantidade_estoque']; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
