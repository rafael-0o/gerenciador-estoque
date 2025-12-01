<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id = ?");
$stmt->execute([$id]);
header('Location: index.php');
exit();
