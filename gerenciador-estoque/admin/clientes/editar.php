<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    header('Location: index.php');
    exit();
}

if ($_POST) {
  $stmt = $pdo->prepare("UPDATE clientes SET nome=?, documento=?, telefone=?, email=?, endereco=? WHERE id=?");
  $stmt->execute([
    $_POST['nome'], $_POST['documento'] ?? null, $_POST['telefone'] ?? null, $_POST['email'] ?? null, $_POST['endereco'] ?? null, $id
  ]);
  header('Location: index.php');
  exit();
}

include '../../includes/header.php';
?>

<h2>Editar Cliente</h2>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nome</label>
                        <input name="nome" class="form-control" value="<?= htmlspecialchars($c['nome']) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Documento (CPF/CNPJ)</label>
                        <input name="documento" class="form-control" value="<?= htmlspecialchars($c['documento'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input name="telefone" class="form-control" value="<?= htmlspecialchars($c['telefone'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($c['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Endereço</label>
                        <input name="endereco" class="form-control" value="<?= htmlspecialchars($c['endereco'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Salvar</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

