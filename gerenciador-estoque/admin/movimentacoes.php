<?php
    include '../includes/auth.php';
    include '../includes/database.php';
    redirectIfNotLoggedIn();

    // PROCESSAR EXCLUSÃO
    if (isset($_GET['excluir'])) {
        $id = $_GET['excluir'];
    
        // Buscar a movimentação antes de excluir para reverter o estoque
        $stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE id = ?");
        $stmt->execute([$id]);
        $mov = $stmt->fetch();
    
    if ($mov) {
        // Reverter o estoque
        if ($mov['tipo'] == 'ENTRADA') {
            // Era entrada, então subtrai
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?")
                ->execute([$mov['quantidade'], $mov['produto_id']]);
        } else {
            // Era saída, então adiciona de volta
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?")
                ->execute([$mov['quantidade'], $mov['produto_id']]);
        }
        
        // Excluir a movimentação
        $pdo->prepare("DELETE FROM movimentacoes WHERE id = ?")->execute([$id]);
    }
    
    header('Location: movimentacoes.php');
    exit();
}

// PROCESSAR EDIÇÃO
if ($_POST && isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $observacao = $_POST['observacao'] ?? '';
    
    // Buscar a movimentação antiga
    $stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE id = ?");
    $stmt->execute([$id]);
    $movAntiga = $stmt->fetch();
    
    $erro = null;
    
    // Reverter o estoque da movimentação antiga
    if ($movAntiga['tipo'] == 'ENTRADA') {
        $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?")
            ->execute([$movAntiga['quantidade'], $movAntiga['produto_id']]);
    } else {
        $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?")
            ->execute([$movAntiga['quantidade'], $movAntiga['produto_id']]);
    }
    
    // Validar nova movimentação se for SAÍDA
    if ($tipo == 'SAIDA') {
        $stmt = $pdo->prepare("SELECT quantidade_estoque, nome FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $produto = $stmt->fetch();
        
        if ($produto['quantidade_estoque'] < $quantidade) {
            $erro = "Estoque insuficiente! {$produto['nome']} possui apenas {$produto['quantidade_estoque']} unidades disponíveis.";
            
            // Reverter a reversão (voltar ao estado original)
            if ($movAntiga['tipo'] == 'ENTRADA') {
                $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?")
                    ->execute([$movAntiga['quantidade'], $movAntiga['produto_id']]);
            } else {
                $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?")
                    ->execute([$movAntiga['quantidade'], $movAntiga['produto_id']]);
            }
        }
    }
    
    if (!$erro) {
        // Atualizar a movimentação
        $pdo->prepare("UPDATE movimentacoes SET produto_id = ?, tipo = ?, quantidade = ?, observacao = ? WHERE id = ?")
            ->execute([$produto_id, $tipo, $quantidade, $observacao, $id]);
        
        // Aplicar novo estoque
        if ($tipo == 'ENTRADA') {
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?")
                ->execute([$quantidade, $produto_id]);
        } else {
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?")
                ->execute([$quantidade, $produto_id]);
        }
        
        header('Location: movimentacoes.php');
        exit();
    }
}

// PROCESSAR CADASTRO
if ($_POST && isset($_POST['adicionar'])) {
    $produto_id = $_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = $_POST['quantidade'];
    $observacao = $_POST['observacao'] ?? '';
    
    $erro = null;
    
    // SE FOR SAÍDA, VERIFICAR SE TEM ESTOQUE SUFICIENTE
    if ($tipo == 'SAIDA') {
        $stmt = $pdo->prepare("SELECT quantidade_estoque, nome FROM produtos WHERE id = ?");
        $stmt->execute([$produto_id]);
        $produto = $stmt->fetch();
        
        if ($produto['quantidade_estoque'] < $quantidade) {
            $erro = "Estoque insuficiente! {$produto['nome']} possui apenas {$produto['quantidade_estoque']} unidades disponíveis.";
        }
    }
    
    if (!$erro) {
        // 1. INSERIR na tabela movimentacoes
        $pdo->prepare("INSERT INTO movimentacoes (produto_id, tipo, quantidade, observacao) VALUES (?, ?, ?, ?)")
            ->execute([$produto_id, $tipo, $quantidade, $observacao]);
        
        // 2. ATUALIZAR estoque
        if ($tipo == 'ENTRADA') {
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque + ? WHERE id = ?")
                ->execute([$quantidade, $produto_id]);
        } else {
            $pdo->prepare("UPDATE produtos SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?")
                ->execute([$quantidade, $produto_id]);
        }
        
        header('Location: movimentacoes.php');
        exit();
    }
}

// Buscar movimentação para editar
$movimentacaoEditar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $movimentacaoEditar = $stmt->fetch();
}

// Buscar produtos com estoque
$produtos = $pdo->query("SELECT id, nome, quantidade_estoque FROM produtos ORDER BY nome")->fetchAll();

// Buscar todas as movimentações
$movimentacoes = $pdo->query("
    SELECT m.*, p.nome as produto_nome 
    FROM movimentacoes m 
    JOIN produtos p ON m.produto_id = p.id 
    ORDER BY m.data_movimentacao DESC
")->fetchAll();

include '../includes/header.php';
?>

<h2>Gerenciar Movimentações de Estoque</h2>

<?php if (isset($erro)): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;">
        <strong>Erro:</strong> <?php echo $erro; ?>
    </div>
<?php endif; ?>

<?php if ($movimentacaoEditar): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Editar Movimentação</h5>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $movimentacaoEditar['id']; ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Produto:</label>
                            <select name="produto_id" class="form-control mb-2" required>
                                <?php foreach($produtos as $prod): ?>
                                    <option value="<?php echo $prod['id']; ?>" 
                                        <?php echo $prod['id'] == $movimentacaoEditar['produto_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($prod['nome']); ?> 
                                        (<?php echo $prod['quantidade_estoque']; ?> unidades)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tipo:</label>
                            <select name="tipo" class="form-control mb-2" required>
                                <option value="ENTRADA" <?php echo $movimentacaoEditar['tipo'] == 'ENTRADA' ? 'selected' : ''; ?>>Entrada</option>
                                <option value="SAIDA" <?php echo $movimentacaoEditar['tipo'] == 'SAIDA' ? 'selected' : ''; ?>>Saída</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Quantidade:</label>
                            <input type="number" name="quantidade" class="form-control mb-2" 
                                   value="<?php echo $movimentacaoEditar['quantidade']; ?>" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label>Observação:</label>
                            <input type="text" name="observacao" class="form-control mb-2" 
                                   value="<?php echo htmlspecialchars($movimentacaoEditar['observacao']); ?>">
                        </div>
                    </div>
                    <button name="atualizar" class="btn btn-primary">Salvar</button>
                    <a href="movimentacoes.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Registrar Movimentação</h5>
                <form method="POST">
                    <label>Produto:</label>
                    <select name="produto_id" class="form-control mb-2" required>
                        <option value="">Selecione um produto</option>
                        <?php foreach($produtos as $prod): ?>
                            <option value="<?php echo $prod['id']; ?>">
                                <?php echo htmlspecialchars($prod['nome']); ?> 
                                (<?php echo $prod['quantidade_estoque']; ?> unidades)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <label>Tipo:</label>
                    <select name="tipo" class="form-control mb-2" required>
                        <option value="">Selecione o tipo</option>
                        <option value="ENTRADA">Entrada</option>
                        <option value="SAIDA">Saída</option>
                    </select>
                
                    <label>Quantidade:</label>
                    <input type="number" name="quantidade" class="form-control mb-2" 
                           placeholder="Quantidade" min="1" required>
                    
                    <label>Observação:</label>
                    <textarea name="observacao" class="form-control mb-2" 
                              placeholder="Observação (opcional)" rows="2"></textarea>
                    
                    <button name="adicionar" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5>Lista de Movimentações</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Data</th>
                            <th>Observação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($movimentacoes as $mov): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mov['produto_nome']); ?></td>
                            <td>
                                <span class="badge <?php echo $mov['tipo'] == 'ENTRADA' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $mov['tipo']; ?>
                                </span>
                            </td>
                            <td><?php echo $mov['quantidade']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($mov['data_movimentacao'])); ?></td>
                            <td><?php echo htmlspecialchars($mov['observacao']); ?></td>
                            <td>
                                <a href="?editar=<?php echo $mov['id']; ?>" class="btn btn-secondary btn-sm">Editar</a>
                                <a href="?excluir=<?php echo $mov['id']; ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Excluir movimentação? O estoque será ajustado automaticamente.')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>