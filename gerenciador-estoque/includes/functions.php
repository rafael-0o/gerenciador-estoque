<?php
// Funções auxiliares do sistema

function formatarData($data) {
    return date('d/m/Y H:i', strtotime($data));
}

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function getStatusEstoque($quantidade, $quantidade_minima) {
    if ($quantidade == 0) {
        return ['text' => 'text-danger', 'status' => 'Esgotado'];
    } elseif ($quantidade <= $quantidade_minima) {
        return ['text' => 'text-warning', 'status' => 'Estoque Baixo'];
    } else {
        return ['text' => 'text-success', 'status' => 'Em Estoque'];
    }
}
?>