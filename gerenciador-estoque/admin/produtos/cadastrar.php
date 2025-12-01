<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$sucesso = false;
if ($_POST) {
  try {
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco_custo, preco_venda, quantidade_estoque) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $_POST['nome'], 
      $_POST['descricao'] ?? null, 
      $_POST['preco_custo'], 
      $_POST['preco_venda'], 
      $_POST['quantidade_estoque']
    ]);
    $sucesso = true;
  } catch (PDOException $e) {
    $erro = "Erro ao cadastrar produto: " . $e->getMessage();
  }
}

include '../../includes/header.php';
?>

<h2>Novo Produto</h2>

<?php if (isset($sucesso) && $sucesso): ?>
<div class="alert alert-success mt-4">
    <strong>Sucesso!</strong> Produto cadastrado com sucesso!
    <div class="mt-3">
        <a href="cadastrar.php" class="btn btn-primary">Cadastrar Outro</a>
        <a href="index.php" class="btn btn-secondary">Voltar para Lista</a>
    </div>
</div>
<?php else: ?>

<?php if (isset($erro)): ?>
<div class="alert alert-danger mt-4"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nome</label>
                        <input name="nome" class="form-control" placeholder="Nome" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" placeholder="Descrição" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preço de Custo</label>
                        <input type="number" step="0.01" name="preco_custo" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preço de Venda</label>
                        <input type="number" step="0.01" name="preco_venda" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Quantidade em Estoque</label>
                        <input type="number" name="quantidade_estoque" class="form-control" placeholder="0" min="0" required>
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

<?php endif; ?>

<?php include '../../includes/footer.php'; ?>

