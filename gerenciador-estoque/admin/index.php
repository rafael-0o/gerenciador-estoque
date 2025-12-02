<?php
ob_start(); // Inicia o buffer de saída
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/database.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    try {
        $pdo->query("SELECT 1 FROM usuarios LIMIT 1");
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            if ($user['senha'] === md5($senha)) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['nivel_acesso'] = $user['nivel_acesso'];
                echo '<div style="background: #dff0d8; color: #3c763d; padding: 20px; text-align: center;">Login realizado. Redirecionando para o dashboard...</div>';
                ob_end_flush();
                echo '<meta http-equiv="refresh" content="2;url=dashboard.php">';
                echo '<script>setTimeout(function(){window.location.replace("dashboard.php")}, 2000);</script>';
                exit();
            } else {
                $error = "Senha incorreta. Verifique e tente novamente.";
            }
        } else {
            $error = "Email não cadastrado. Confira se digitou corretamente.";
        }
    } catch (PDOException $e) {
        $error = "Erro: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="text-center">Login</h4>
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <input type="email" name="email" class="form-control form-control-lg mb-3" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <input type="password" name="senha" class="form-control form-control-lg mb-3" placeholder="Senha" required>
                            <button type="submit" class="btn btn-login btn-lg w-100">Entrar</button>
                        </form>
                        <p class="mt-3 small text-center">admin@estoque.com / admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
