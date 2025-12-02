<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$busca = $_GET['q'] ?? '';
$sql = "SELECT m.*, p.nome as produto_nome FROM movimentacoes m JOIN produtos p ON m.produto_id = p.id";
if ($busca) {
  $sql .= " WHERE p.nome LIKE :busca OR m.observacao LIKE :busca";
}
$sql .= " ORDER BY m.data_movimentacao DESC";

$stmt = $pdo->prepare($sql);
if ($busca) {
  $stmt->bindValue(':busca', "%$busca%");
}
$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<h2>Gerenciar Movimentações de Estoque</h2>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex">
                        <form method="get" class="d-flex">
                            <input type="text" name="q" class="form-control me-2" placeholder="Buscar por produto ou observação" value="<?= htmlspecialchars($busca) ?>">
                            <button class="btn btn-secondary" type="submit">Buscar</button>
                        </form>
                        <a href="../dashboard.php" class="btn btn-outline-secondary ms-2">Voltar ao Dashboard</a>
                    </div>
                    <a href="cadastrar.php" class="btn btn-primary">Nova Movimentação</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Observação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $mov): ?>
                        <tr>
                            <td><?= $mov['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?></td>
                            <td><?= htmlspecialchars($mov['produto_nome']) ?></td>
                            <td>
                                <span class="badge <?= $mov['tipo'] == 'ENTRADA' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $mov['tipo'] ?>
                                </span>
                            </td>
                            <td><?= $mov['quantidade'] ?></td>
                            <td><?= htmlspecialchars($mov['observacao'] ?? '') ?></td>
                            <td>
                                <a href="excluir.php?id=<?= $mov['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir movimentação?')">Excluir</a>
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

