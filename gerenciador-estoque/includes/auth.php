<?php
// session_start() is already handled in header.php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        // Detecta o caminho base usando o diretório físico (mesmo método do header.php)
        $root_dir = realpath(dirname(__DIR__));
        $current_dir = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
        
        // Calcula o caminho relativo
        $relative_path = str_replace($root_dir, '', $current_dir);
        $relative_path = str_replace('\\', '/', $relative_path);
        $relative_path = trim($relative_path, '/');
        
        // Conta quantos níveis precisamos voltar
        if (empty($relative_path)) {
            $depth = 0;
        } else {
            $depth = substr_count($relative_path, '/') + 1;
        }
        
        $base_path = $depth > 0 ? str_repeat('../', $depth) : '';
        
        header('Location: ' . $base_path . 'admin/index.php');
        exit();
    }
}
?>
