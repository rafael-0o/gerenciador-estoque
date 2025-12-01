<?php
ob_start(); // Inicia o buffer de saída
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/database.php';

$error = '';
$debug = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $debug .= "Email recebido: " . htmlspecialchars($email) . "<br>";
    $debug .= "Senha recebida: " . str_repeat("*", strlen($senha)) . "<br>";
    try {
        $pdo->query("SELECT 1 FROM usuarios LIMIT 1");
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $debug .= "Usuário encontrado: SIM<br>";
            if ($user['senha'] === md5($senha)) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['nivel_acesso'] = $user['nivel_acesso'];
                $debug .= "Login bem-sucedido!<br>";
                echo '<div style="background: #dff0d8; color: #3c763d; padding: 20px; text-align: center;">Login validado! Redirecionando para o dashboard...</div>';
                ob_end_flush();
                echo '<meta http-equiv="refresh" content="2;url=dashboard.php">';
                echo '<script>setTimeout(function(){window.location.replace("dashboard.php")}, 2000);</script>';
                exit();
            } else {
                $error = "Senha incorreta!";
                $debug .= "Senha informada: " . md5($senha) . "<br>Senha esperada: " . $user['senha'] . "<br>";
            }
        } else {
            $error = "Email não encontrado!";
            $debug .= "Usuários no banco: ";
            $all_users = $pdo->query("SELECT email FROM usuarios")->fetchAll();
            $debug .= implode(", ", array_column($all_users, 'email')) . "<br>";
        }
    } catch (PDOException $e) {
        $error = "Erro: " . $e->getMessage();
        $debug .= "Erro na query: " . $e->getCode();
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
                        <?php if ($debug): ?>
                            <div class="alert alert-info" style="font-size: 12px;">
                                <strong>Debug:</strong><br>
                                <?php echo $debug; ?>
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
