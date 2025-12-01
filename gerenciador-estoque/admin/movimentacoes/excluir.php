<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth.php';
include '../../includes/database.php';
redirectIfNotLoggedIn();

$id = $_GET['id'] ?? 0;

// Busca a movimentação para reverter o estoque
$stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE id = ?");
$stmt->execute([$id]);
$mov = $stmt->fetch();

if ($mov) {
    // Reverte o estoque
    if ($mov['tipo'] == 'ENTRADA') {
        $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?");
    }
    $stmt->execute([$mov['quantidade'], $mov['produto_id']]);
    
    // Remove a movimentação
    $stmt = $pdo->prepare("DELETE FROM movimentacoes WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit();

