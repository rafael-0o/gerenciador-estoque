<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$busca = $_GET['q'] ?? '';
$sql = "SELECT * FROM produtos";
if ($busca) {
  $sql .= " WHERE nome LIKE :busca OR descricao LIKE :busca";
}
$sql .= " ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
if ($busca) {
  $stmt->bindValue(':busca', "%$busca%");
}
$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<h2>Gerenciar Produtos</h2>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex">
                        <form method="get" class="d-flex">
                            <input type="text" name="q" class="form-control me-2" placeholder="Buscar por nome ou descrição" value="<?= htmlspecialchars($busca) ?>">
                            <button class="btn btn-secondary" type="submit">Buscar</button>
                        </form>
                        <a href="../dashboard.php" class="btn btn-outline-secondary ms-2">Voltar ao Dashboard</a>
                    </div>
                    <a href="cadastrar.php" class="btn btn-primary">Novo Produto</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço Custo</th>
                            <th>Preço Venda</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $prod): ?>
                        <tr>
                            <td><?= $prod['id'] ?></td>
                            <td><?= htmlspecialchars($prod['nome']) ?></td>
                            <td><?= htmlspecialchars($prod['descricao'] ?? '') ?></td>
                            <td>R$ <?= number_format($prod['preco_custo'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($prod['preco_venda'], 2, ',', '.') ?></td>
                            <td class="<?= (int)$prod['quantidade_estoque'] == 0 ? 'text-danger fw-bold' : '' ?>">
                                <?= (int)$prod['quantidade_estoque'] ?>
                            </td>
                            <td>
                                <a href="editar.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="excluir.php?id=<?= $prod['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir produto?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

