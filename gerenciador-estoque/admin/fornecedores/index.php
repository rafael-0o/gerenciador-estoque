<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$busca = $_GET['q'] ?? '';
$sql = "SELECT * FROM fornecedores";
if ($busca) {
  $sql .= " WHERE nome LIKE :busca OR cnpj LIKE :busca";
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

<h2>Gerenciar Fornecedores</h2>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <form method="get" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" placeholder="Buscar por nome ou CNPJ" value="<?= htmlspecialchars($busca) ?>">
                        <button class="btn btn-secondary">Buscar</button>
                    </form>
                    <a href="cadastrar.php" class="btn btn-primary">Novo Fornecedor</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $f): ?>
                        <tr>
                            <td><?= $f['id'] ?></td>
                            <td><?= htmlspecialchars($f['nome']) ?></td>
                            <td><?= htmlspecialchars($f['cnpj']) ?></td>
                            <td><?= htmlspecialchars($f['telefone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($f['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($f['endereco'] ?? '') ?></td>
                            <td>
                                <a href="editar.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="excluir.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir fornecedor?')">Excluir</a>
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
