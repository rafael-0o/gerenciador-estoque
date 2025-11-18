<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();

if ($_POST && $_POST['adicionar']) {
    $pdo->prepare("INSERT INTO categorias (nome, descricao) VALUES (?,?)")->execute([$_POST['nome'], $_POST['descricao']]);
    header('Location: categorias.php');
    exit();
}

if ($_GET['excluir']) {
    $pdo->prepare("DELETE FROM categorias WHERE id = ?")->execute([$_GET['excluir']]);
    header('Location: categorias.php');
    exit();
}

include '../includes/header.php';
?>

<h2>Gerenciar Categorias</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Adicionar Categoria</h5>
                <form method="POST">
                    <input type="text" name="nome" class="form-control mb-2" placeholder="Nome" required>
                    <textarea name="descricao" class="form-control mb-2" placeholder="Descrição"></textarea>
                    <button name="adicionar" class="btn btn-primary w-100">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5>Lista de Categorias</h5>
                <table class="table table-striped">
                    <thead>
                        <tr><th>Nome</th><th>Descrição</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($pdo->query("SELECT * FROM categorias ORDER BY nome") as $cat): ?>
                        <tr>
                            <td><?php echo $cat['nome']; ?></td>
                            <td><?php echo $cat['descricao']; ?></td>
                            <td>
                                <a href="?excluir=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir?')">Excluir</a>
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