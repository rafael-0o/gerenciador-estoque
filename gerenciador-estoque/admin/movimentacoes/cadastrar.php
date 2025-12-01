<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$sucesso = false;
if ($_POST) {
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = (int)$_POST['quantidade'];
    $observacao = $_POST['observacao'] ?? '';
    
    // Verifica se é saída e se há estoque suficiente
    if ($tipo == 'SAIDA') {
        $stmt = $pdo->prepare("SELECT quantidade_estoque FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $produto = $stmt->fetch();
        
        if ($produto && (int)$produto['quantidade_estoque'] < $quantidade) {
            $error = "Estoque insuficiente! Disponível: " . $produto['quantidade_estoque'];
        } else {
            try {
                // Insere a movimentação
                $stmt = $pdo->prepare("INSERT INTO movimentacoes (produto_id, tipo, quantidade, observacao) VALUES (?, ?, ?, ?)");
                $stmt->execute([$produto_id, $tipo, $quantidade, $observacao]);
                
                // Atualiza o estoque do produto
                $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?");
                $stmt->execute([$quantidade, $produto_id]);
                
                $sucesso = true;
            } catch (PDOException $e) {
                $error = "Erro ao registrar movimentação: " . $e->getMessage();
            }
        }
    } else {
        try {
            // Insere a movimentação
            $stmt = $pdo->prepare("INSERT INTO movimentacoes (produto_id, tipo, quantidade, observacao) VALUES (?, ?, ?, ?)");
            $stmt->execute([$produto_id, $tipo, $quantidade, $observacao]);
            
            // Atualiza o estoque do produto
            $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?");
            $stmt->execute([$quantidade, $produto_id]);
            
            $sucesso = true;
        } catch (PDOException $e) {
            $error = "Erro ao registrar movimentação: " . $e->getMessage();
        }
    }
}

$produtos = $pdo->query("SELECT id, nome, quantidade_estoque FROM produtos ORDER BY nome")->fetchAll();

include '../../includes/header.php';
?>

<h2>Nova Movimentação</h2>

<?php if (isset($sucesso) && $sucesso): ?>
<div class="alert alert-success mt-4">
    <strong>Sucesso!</strong> Movimentação registrada com sucesso!
    <div class="mt-3">
        <a href="cadastrar.php" class="btn btn-primary">Registrar Outra</a>
        <a href="index.php" class="btn btn-secondary">Voltar para Lista</a>
    </div>
</div>
<?php else: ?>

<?php if (isset($error)): ?>
<div class="alert alert-danger mt-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Produto</label>
                        <select name="produto_id" class="form-control" required>
                            <option value="">Selecione um produto</option>
                            <?php foreach($produtos as $prod): ?>
                                <option value="<?= $prod['id'] ?>" data-estoque="<?= $prod['quantidade_estoque'] ?>">
                                    <?= htmlspecialchars($prod['nome']) ?> (Estoque: <?= $prod['quantidade_estoque'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Selecione o tipo</option>
                            <option value="ENTRADA">Entrada</option>
                            <option value="SAIDA">Saída</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Quantidade</label>
                        <input type="number" name="quantidade" class="form-control" placeholder="Quantidade" min="1" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Observação</label>
                        <textarea name="observacao" class="form-control" placeholder="Observação (opcional)" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Registrar</button>
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php include '../../includes/footer.php'; ?>

