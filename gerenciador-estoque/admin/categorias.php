<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();

$error = '';
try {
    // Verifica se a tabela existe
    $pdo->query("SELECT 1 FROM categorias LIMIT 1");
} catch (PDOException $e) {
    $error = 'A tabela de categorias não existe no banco de dados.';
}

if (!$error && $_POST && isset($_POST['adicionar'])) {
    try {
        $pdo->prepare("INSERT INTO categorias (nome, descricao) VALUES (?,?)")->execute([$_POST['nome'], $_POST['descricao']]);
        header('Location: categorias.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Erro ao adicionar categoria: ' . $e->getMessage();
    }
}

if (!$error && isset($_GET['excluir'])) {
    try {
        $pdo->prepare("DELETE FROM categorias WHERE id = ?")->execute([$_GET['excluir']]);
        header('Location: categorias.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Erro ao excluir categoria: ' . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<h2>Gerenciar Categorias</h2>

<?php if ($error): ?>
<div class="alert alert-warning mt-4"><?php echo htmlspecialchars($error); ?></div>
<?php else: ?>

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
                        <?php 
                        try {
                            foreach($pdo->query("SELECT * FROM categorias ORDER BY nome") as $cat): 
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['nome']); ?></td>
                            <td><?php echo htmlspecialchars($cat['descricao'] ?? ''); ?></td>
                            <td>
                                <a href="?excluir=<?php echo $cat['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir?')">Excluir</a>
                            </td>
                        </tr>
                        <?php 
                            endforeach;
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="3" class="text-danger">Erro ao carregar categorias.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php include '../includes/footer.php'; ?>