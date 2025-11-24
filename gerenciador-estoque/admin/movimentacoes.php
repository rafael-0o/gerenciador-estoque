<?php
    // include '../includes/auth.php';
    include '../includes/database.php';
    // redirectIfNotLoggedIn();

    $produtos = $pdo->query("SELECT id, nome FROM produtos ORDER BY nome")->fetchAll();

    // include '../includes/header.php';
?>

<h2>Gerenciar Movimentações de Estoque</h2>

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
</div>

<?php include '../includes/footer.php'; ?>