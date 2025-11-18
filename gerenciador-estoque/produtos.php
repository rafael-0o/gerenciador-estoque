<?php include 'includes/header.php'; ?>
<?php include 'includes/database.php'; ?>

<h2>Catálogo de Produtos</h2>

<?php
$stmt = $pdo->query("
    SELECT p.*, c.nome as categoria_nome 
    FROM produtos p 
    LEFT JOIN categorias c ON p.categoria_id = c.id 
    ORDER BY p.nome
");
?>

<div class="table-responsive mt-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produto = $stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                <td>R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                <td class="<?php echo $produto['quantidade'] <= $produto['quantidade_minima'] ? 'text-danger fw-bold' : ''; ?>">
                    <?php echo $produto['quantidade']; ?>
                </td>
                <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>