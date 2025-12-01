<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$busca = $_GET['q'] ?? '';
$sql = "SELECT * FROM clientes";
if ($busca) {
  $sql .= " WHERE nome LIKE :busca OR documento LIKE :busca";
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

<h2>Gerenciar Clientes</h2>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <form method="get" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" placeholder="Buscar por nome ou documento" value="<?= htmlspecialchars($busca) ?>">
                        <button class="btn btn-secondary">Buscar</button>
                    </form>
                    <a href="cadastrar.php" class="btn btn-primary">Novo Cliente</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Documento</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['nome']) ?></td>
                            <td><?= htmlspecialchars($c['documento'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['telefone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['endereco'] ?? '') ?></td>
                            <td>
                                <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="excluir.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir cliente?')">Excluir</a>
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

