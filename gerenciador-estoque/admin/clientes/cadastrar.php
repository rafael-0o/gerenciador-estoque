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
    $stmt = $pdo->prepare("INSERT INTO clientes (nome, documento, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $_POST['nome'], $_POST['documento'] ?? null, $_POST['telefone'] ?? null, $_POST['email'] ?? null, $_POST['endereco'] ?? null
    ]);
    $sucesso = true;
  } catch (PDOException $e) {
    $erro = "Erro ao cadastrar cliente: " . $e->getMessage();
  }
}

include '../../includes/header.php';
?>

<h2>Novo Cliente</h2>

<?php if (isset($sucesso) && $sucesso): ?>
<div class="alert alert-success mt-4">
    <strong>Sucesso!</strong> Cliente cadastrado com sucesso!
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
                        <label class="form-label">Documento (CPF/CNPJ)</label>
                        <input name="documento" class="form-control" placeholder="CPF/CNPJ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input name="telefone" class="form-control" placeholder="Telefone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Endereço</label>
                        <input name="endereco" class="form-control" placeholder="Endereço">
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

