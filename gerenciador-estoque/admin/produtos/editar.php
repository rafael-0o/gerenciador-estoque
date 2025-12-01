<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$prod = $stmt->fetch();

if (!$prod) {
    header('Location: index.php');
    exit();
}

if ($_POST) {
  $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco_custo=?, preco_venda=?, quantidade_estoque=? WHERE id=?");
  $stmt->execute([
    $_POST['nome'], 
    $_POST['descricao'] ?? null, 
    $_POST['preco_custo'], 
    $_POST['preco_venda'], 
    $_POST['quantidade_estoque'], 
    $id
  ]);
  header('Location: index.php');
  exit();
}

include '../../includes/header.php';
?>

<h2>Editar Produto</h2>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nome</label>
                        <input name="nome" class="form-control" value="<?= htmlspecialchars($prod['nome']) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($prod['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preço de Custo</label>
                        <input type="number" step="0.01" name="preco_custo" class="form-control" value="<?= number_format($prod['preco_custo'], 2, '.', '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preço de Venda</label>
                        <input type="number" step="0.01" name="preco_venda" class="form-control" value="<?= number_format($prod['preco_venda'], 2, '.', '') ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Quantidade em Estoque</label>
                        <input type="number" name="quantidade_estoque" class="form-control" value="<?= (int)$prod['quantidade_estoque'] ?>" min="0" required>
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

