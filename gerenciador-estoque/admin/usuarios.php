<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

if ($_POST && $_POST['adicionar']) {
    $pdo->prepare("INSERT INTO usuarios (nome, email, senha, nivel_acesso) VALUES (?,?,MD5(?),?)")
        ->execute([$_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['nivel_acesso']]);
    header('Location: usuarios.php');
    exit();
}

include '../includes/header.php';
?>

<h2>Gerenciar Usuários</h2>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Adicionar Usuário</h5>
                <form method="POST">
                    <input type="text" name="nome" class="form-control mb-2" placeholder="Nome" required>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                    <input type="password" name="senha" class="form-control mb-2" placeholder="Senha" required>
                    <select name="nivel_acesso" class="form-select mb-2">
                        <option value="operador">Operador</option>
                        <option value="admin">Administrador</option>
                    </select>
                    <button name="adicionar" class="btn btn-primary w-100">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5>Lista de Usuários</h5>
                <table class="table table-striped">
                    <thead><tr><th>Nome</th><th>Email</th><th>Acesso</th></tr></thead>
                    <tbody>
                        <?php foreach($pdo->query("SELECT * FROM usuarios") as $user): ?>
                        <tr>
                            <td><?php echo $user['nome']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><span class="badge bg-<?php echo $user['nivel_acesso'] == 'admin' ? 'danger' : 'secondary'; ?>"><?php echo $user['nivel_acesso']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>