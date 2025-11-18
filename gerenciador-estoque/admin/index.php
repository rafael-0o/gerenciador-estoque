<?php
session_start();
include '../includes/database.php';

if ($_POST) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = MD5(?)");
    $stmt->execute([$_POST['email'], $_POST['senha']]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['nivel_acesso'] = $user['nivel_acesso'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Login inválido!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-center">Login</h4>
                        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <form method="POST">
                            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                            <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
                            <button class="btn btn-primary w-100">Entrar</button>
                        </form>
                        <p class="mt-3 small text-center">admin@estoque.com / admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>