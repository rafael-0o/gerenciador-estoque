<?php
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();

$error = '';
try {
    // Verifica se a tabela existe
    $pdo->query("SELECT 1 FROM usuarios LIMIT 1");
} catch (PDOException $e) {
    $error = 'A tabela de usuários não existe no banco de dados.';
}

if (!$error && $_POST && isset($_POST['adicionar'])) {
    try {
        $pdo->prepare("INSERT INTO usuarios (nome, email, senha, nivel_acesso) VALUES (?,?,MD5(?),?)")
            ->execute([$_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['nivel_acesso']]);
        header('Location: usuarios.php');
        exit();
    } catch (PDOException $e) {
        $error = 'Erro ao adicionar usuário: ' . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<h2>Gerenciar Usuários</h2>

<?php if ($error): ?>
<div class="alert alert-warning mt-4"><?php echo htmlspecialchars($error); ?></div>
<?php else: ?>

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
                        <?php 
                        try {
                            foreach($pdo->query("SELECT * FROM usuarios") as $user): 
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nome']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><span class="badge bg-<?php echo $user['nivel_acesso'] == 'admin' ? 'danger' : 'secondary'; ?>"><?php echo htmlspecialchars($user['nivel_acesso']); ?></span></td>
                        </tr>
                        <?php 
                            endforeach;
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="3" class="text-danger">Erro ao carregar usuários.</td></tr>';
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