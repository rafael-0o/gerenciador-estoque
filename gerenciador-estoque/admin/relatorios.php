<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/auth.php';
include '../includes/database.php';
redirectIfNotLoggedIn();
include '../includes/header.php';
require_once '../includes/functions.php';

$threshold = isset($_GET['limite']) ? max(1, (int)$_GET['limite']) : 5;

$total_produtos = $pdo->query("SELECT COUNT(*) as total FROM produtos")->fetch()['total'] ?? 0;
$produtos_esgotados_total = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE quantidade_estoque = 0")->fetch()['total'] ?? 0;
$produtos_baixo_total = $pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE quantidade_estoque > 0 AND quantidade_estoque <= ?");
$produtos_baixo_total->execute([$threshold]);
$produtos_baixo_total = $produtos_baixo_total->fetch()['total'] ?? 0;
$estoque_total_unidades = $pdo->query("SELECT SUM(quantidade_estoque) as total FROM produtos")->fetch()['total'] ?? 0;
$valor_total_custo = $pdo->query("SELECT SUM(preco_custo * quantidade_estoque) as total FROM produtos")->fetch()['total'] ?? 0;
$valor_total_venda = $pdo->query("SELECT SUM(preco_venda * quantidade_estoque) as total FROM produtos")->fetch()['total'] ?? 0;

$stmt_baixo = $pdo->prepare("SELECT nome, quantidade_estoque FROM produtos WHERE quantidade_estoque > 0 AND quantidade_estoque <= ? ORDER BY quantidade_estoque ASC, nome");
$stmt_baixo->execute([$threshold]);
$produtos_baixo = $stmt_baixo->fetchAll();

$produtos_esgotados = $pdo->query("SELECT nome, quantidade_estoque FROM produtos WHERE quantidade_estoque = 0 ORDER BY nome")->fetchAll();

$ultimas_mov = $pdo->query("SELECT m.id, m.tipo, m.quantidade, m.data_movimentacao, p.nome FROM movimentacoes m JOIN produtos p ON p.id = m.produto_id ORDER BY m.data_movimentacao DESC LIMIT 10")->fetchAll();

$top_mov_30 = $pdo->query("SELECT p.nome, SUM(m.quantidade) as total FROM movimentacoes m JOIN produtos p ON p.id = m.produto_id WHERE m.data_movimentacao >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY m.produto_id ORDER BY total DESC LIMIT 10")->fetchAll();

$fornecedor_counts = $pdo->query("SELECT f.nome, COUNT(*) as total FROM produto_fornecedor pf JOIN fornecedores f ON f.id = pf.fornecedor_id GROUP BY f.id ORDER BY total DESC LIMIT 10")->fetchAll();
?>

<h2>Relatórios</h2>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header"><h5>Resumo do Estoque</h5></div>
      <div class="card-body">
        <div class="row w-100">
          <div class="col-md-2"><div class="card p-3 text-center"><h4><?php echo (int)$total_produtos; ?></h4><div>Produtos</div></div></div>
          <div class="col-md-2"><div class="card p-3 text-center"><h4><?php echo (int)$estoque_total_unidades; ?></h4><div>Unidades em Estoque</div></div></div>
          <div class="col-md-2"><div class="card p-3 text-center"><h4><?php echo (int)$produtos_esgotados_total; ?></h4><div>Esgotados</div></div></div>
          <div class="col-md-2"><div class="card p-3 text-center"><h4><?php echo (int)$produtos_baixo_total; ?></h4><div>Estoque Baixo (≤ <?php echo (int)$threshold; ?>)</div></div></div>
          <div class="col-md-2"><div class="card p-3 text-center"><h4>R$ <?php echo number_format((float)$valor_total_custo, 2, ',', '.'); ?></h4><div>Valor Total (Custo)</div></div></div>
          <div class="col-md-2"><div class="card p-3 text-center"><h4>R$ <?php echo number_format((float)$valor_total_venda, 2, ',', '.'); ?></h4><div>Valor Total (Venda)</div></div></div>
        </div>
        <form method="get" class="mt-3">
          <div class="input-group">
            <span class="input-group-text">Limite estoque baixo</span>
            <input type="number" name="limite" class="form-control" min="1" value="<?php echo (int)$threshold; ?>">
            <button class="btn btn-primary" type="submit">Aplicar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Produtos Esgotados</h5></div>
      <div class="card-body">
        <table class="table table-striped">
          <thead><tr><th>Produto</th><th>Estoque</th></tr></thead>
          <tbody>
          <?php foreach ($produtos_esgotados as $prod): ?>
            <tr>
              <td><?php echo htmlspecialchars($prod['nome']); ?></td>
              <td class="text-danger fw-bold">0</td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($produtos_esgotados) === 0): ?>
            <tr><td colspan="2" class="text-muted">Nenhum produto esgotado.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Produtos com Estoque Baixo</h5></div>
      <div class="card-body">
        <table class="table table-striped">
          <thead><tr><th>Produto</th><th>Estoque</th></tr></thead>
          <tbody>
          <?php foreach ($produtos_baixo as $prod): ?>
            <tr>
              <td><?php echo htmlspecialchars($prod['nome']); ?></td>
              <td class="text-warning fw-bold">&le; <?php echo (int)$prod['quantidade_estoque']; ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($produtos_baixo) === 0): ?>
            <tr><td colspan="2" class="text-muted">Nenhum produto com estoque baixo.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Últimas Movimentações</h5></div>
      <div class="card-body">
        <table class="table table-striped">
          <thead><tr><th>Data</th><th>Produto</th><th>Tipo</th><th>Quantidade</th></tr></thead>
          <tbody>
          <?php foreach ($ultimas_mov as $m): ?>
            <tr>
              <td><?php echo formatarData($m['data_movimentacao']); ?></td>
              <td><?php echo htmlspecialchars($m['nome']); ?></td>
              <td><?php echo htmlspecialchars($m['tipo']); ?></td>
              <td><?php echo (int)$m['quantidade']; ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($ultimas_mov) === 0): ?>
            <tr><td colspan="4" class="text-muted">Sem movimentações registradas.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Mais Movimentados (30 dias)</h5></div>
      <div class="card-body">
        <table class="table table-striped">
          <thead><tr><th>Produto</th><th>Total Movimentado</th></tr></thead>
          <tbody>
          <?php foreach ($top_mov_30 as $t): ?>
            <tr>
              <td><?php echo htmlspecialchars($t['nome']); ?></td>
              <td><?php echo (int)$t['total']; ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($top_mov_30) === 0): ?>
            <tr><td colspan="2" class="text-muted">Sem movimentações no período.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h5>Fornecedores por Produtos</h5></div>
      <div class="card-body">
        <table class="table table-striped">
          <thead><tr><th>Fornecedor</th><th>Qtd. Produtos</th></tr></thead>
          <tbody>
          <?php foreach ($fornecedor_counts as $f): ?>
            <tr>
              <td><?php echo htmlspecialchars($f['nome']); ?></td>
              <td><?php echo (int)$f['total']; ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($fornecedor_counts) === 0): ?>
            <tr><td colspan="2" class="text-muted">Nenhum vínculo fornecedor-produto cadastrado.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
