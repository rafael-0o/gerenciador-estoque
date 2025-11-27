<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();

if ($_POST && isset($_POST['adicionar'])) {
    $pdo->prepare("INSERT INTO produtos (nome, descricao, preco_custo, preco_venda, quantidade_estoque) VALUES (?,?,?,?,?)")
        ->execute([$_POST['nome'], $_POST['descricao'], $_POST['preco_custo'], $_POST['preco_venda'], $_POST['quantidade_estoque']]);
    header('Location: produtos.php');
    exit();
}

if ($_POST && isset($_POST['atualizar'])) {
    $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco_custo = ?, preco_venda = ?, quantidade_estoque = ? WHERE id = ?")
        ->execute([$_POST['nome'], $_POST['descricao'], $_POST['preco_custo'], $_POST['preco_venda'], $_POST['quantidade_estoque'], $_POST['id']]);
    header('Location: produtos.php');
    exit();
}

if (isset($_GET['excluir'])) {
    $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([$_GET['excluir']]);
    header('Location: produtos.php');
    exit();
}

$produtoEditar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $produtoEditar = $stmt->fetch();
}

include '../includes/header.php';
?>

<h2>Gerenciar Produtos</h2>

<?php if ($produtoEditar): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Editar Produto</h5>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $produtoEditar['id']; ?>">
                    <div class="row">
                        <div class="col-md-4"><input type="text" name="nome" class="form-control mb-2" value="<?php echo htmlspecialchars($produtoEditar['nome']); ?>" required></div>
                        <div class="col-md-4"><input type="number" step="0.01" name="preco_custo" class="form-control mb-2" value="<?php echo number_format($produtoEditar['preco_custo'], 2, '.', ''); ?>" required></div>
                        <div class="col-md-4"><input type="number" step="0.01" name="preco_venda" class="form-control mb-2" value="<?php echo number_format($produtoEditar['preco_venda'], 2, '.', ''); ?>" required></div>
                    </div>
                    <textarea name="descricao" class="form-control mb-2" placeholder="Descrição"><?php echo htmlspecialchars($produtoEditar['descricao']); ?></textarea>
                    <input type="number" name="quantidade_estoque" class="form-control mb-2" value="<?php echo (int)$produtoEditar['quantidade_estoque']; ?>" min="0" required>
                    <button name="atualizar" class="btn btn-primary">Salvar</button>
                    <a href="produtos.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
    
    
</div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Adicionar Produto</h5>
                <form method="POST">
                    <input type="text" name="nome" class="form-control mb-2" placeholder="Nome" required>
                    <textarea name="descricao" class="form-control mb-2" placeholder="Descrição"></textarea>
                    <input type="number" step="0.01" name="preco_custo" class="form-control mb-2" placeholder="Preço Custo" required>
                    <input type="number" step="0.01" name="preco_venda" class="form-control mb-2" placeholder="Preço Venda" required>
                    <input type="number" name="quantidade_estoque" class="form-control mb-2" placeholder="Quantidade em Estoque" min="0" required>
                    <button name="adicionar" class="btn btn-primary w-100">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5>Lista de Produtos</h5>
                <table class="table table-striped">
                    <thead>
                        <tr><th>Nome</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($pdo->query("SELECT * FROM produtos ORDER BY nome") as $prod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['nome']); ?></td>
                            <td>R$ <?php echo number_format($prod['preco_venda'], 2, ',', '.'); ?></td>
                            <td class="<?php echo $prod['quantidade_estoque'] == 0 ? 'text-danger fw-bold' : ''; ?>">
                                <?php echo (int)$prod['quantidade_estoque']; ?>
                            </td>
                            <td>
                                <a href="?editar=<?php echo $prod['id']; ?>" class="btn btn-secondary btn-sm">Editar</a>
                                <a href="?excluir=<?php echo $prod['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
