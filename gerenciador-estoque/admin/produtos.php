<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();

if ($_POST && $_POST['adicionar']) {
    $pdo->prepare("INSERT INTO produtos (nome, descricao, preco_custo, preco_venda, quantidade, quantidade_minima, categoria_id) VALUES (?,?,?,?,?,?,?)")
        ->execute([$_POST['nome'], $_POST['descricao'], $_POST['preco_custo'], $_POST['preco_venda'], $_POST['quantidade'], $_POST['quantidade_minima'], $_POST['categoria_id']]);
    header('Location: produtos.php');
    exit();
}

if ($_GET['excluir']) {
    $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([$_GET['excluir']]);
    header('Location: produtos.php');
    exit();
}

include '../includes/header.php';
?>

<h2>Gerenciar Produtos</h2>

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
                    <input type="number" name="quantidade" class="form-control mb-2" placeholder="Quantidade" required>
                    <input type="number" name="quantidade_minima" class="form-control mb-2" placeholder="Quantidade Mínima" value="5" required>
                    <select name="categoria_id" class="form-select mb-2" required>
                        <option value="">Selecione a categoria</option>
                        <?php foreach($pdo->query("SELECT * FROM categorias") as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
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
                            <td><?php echo $prod['nome']; ?></td>
                            <td>R$ <?php echo number_format($prod['preco_venda'], 2, ',', '.'); ?></td>
                            <td class="<?php echo $prod['quantidade'] <= $prod['quantidade_minima'] ? 'text-danger fw-bold' : ''; ?>">
                                <?php echo $prod['quantidade']; ?>
                            </td>
                            <td>
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