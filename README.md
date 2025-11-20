 Objetivo Geral
Criar uma aplicação web em PHP para gerenciar o fluxo de Produtos, Fornecedores, Clientes e Movimentações de Estoque.
O sistema deve apresentar duas interfaces distintas: uma Admin (completa e sensível) e uma Vendedor (simplificada e focada em vendas).

Estrutura do Projeto 
O sistema de estoque precisará gerenciar informações sobre produtos, fornecedores, clientes e movimentações de estoque.
1. ProdutosCriar, Ler, Atualizar, Deletar (CRUD)Gerencia itens em estoque (nome, preço, quantidade, etc.).
2. FornecedoresCriar, Ler, Atualizar, Deletar (CRUD)Gerencia as empresas que fornecem os produtos.
3. ClientesCriar, Ler, Atualizar, Deletar (CRUD)Gerencia os clientes que compram os produtos.
4. MovimentaçõesCriar, Ler, Atualizar, Deletar (CRUD)Registra entradas (compras) e saídas (vendas) de produtos no estoque.

Rotas de Acesso (URL)Como você não terá autenticação, a forma mais simples de "proteger" as rotas é criando arquivos PHP separados na estrutura de pastas.
Tela do Administrador (Informações Sensíveis)Estas rotas são onde o administrador fará o gerenciamento completo do sistema (os 4 CRUDS).
Funcionalidade     Rota de Exemplo                   Acesso      ConteúdoCompleto 

Dashboard Admin    admin/dashboard.php            Administrador   Relatórios de Estoque Crítico, Valor total em estoque, Links para todos os CRUDS.
CRUD Produtos      admin/produtos/index.php       Administrador   Gerenciamento completo de produtos (preços de custo, margem de lucro, histórico de fornecimento).
CRUD Fornecedores  admin/fornecedores/index.php   Administrador   Gerenciamento de fornecedores (CNPJ, Dados bancários, Contratos).
CRUD Clientes      admin/clientes/index.php       Administrador   Gerenciamento de clientes (CPF/CNPJ, Histórico de compras).
CRUD Movimentações admin/movimentacoes/index.php  Administrador   Visualização detalhada de todas as entradas e saídas.


Tela do Vendedor (Uso Essencial)
Estas rotas contêm apenas o necessário para o vendedor realizar as vendas e verificar disponibilidade
.Funcionalidade           Rota de Exemplo             Acesso     Conteúdo Essencial 
Dashboard Vendas          vendas/dashboard.php       Vendedor    Links para registrar vendas e consultar estoque
.Consulta de Estoque      vendas/consulta.php        Vendedor    Lista de Produtos (apenas nome e quantidade disponível), sem mostrar preços de custo.
Registrar Venda (Saída)   vendas/registrar_saida.php Vendedor    Formulário para registrar uma Movimentação de Saída (venda).


Estrutura do Banco de Dados

Você precisará de cinco tabelas principais. Uma para cada CRUD e uma tabela auxiliar para mapear a relação entre Produtos e Fornecedores.

1. Tabela produtos (CRUD Produtos)
Esta tabela armazena os detalhes dos itens em estoque.
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_custo DECIMAL(10, 2) NOT NULL, -- Informação sensível (Admin)
    preco_venda DECIMAL(10, 2) NOT NULL,
    quantidade_estoque INT NOT NULL DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Com certeza! A estrutura do banco de dados (BD) é o alicerce do seu projeto. Vou te apresentar as tabelas necessárias para os 4 CRUDS (Produtos, Fornecedores, Clientes e Movimentações) usando SQL para MySQL/MariaDB.

🗄️ Estrutura do Banco de Dados
Você precisará de cinco tabelas principais. Uma para cada CRUD e uma tabela auxiliar para mapear a relação entre Produtos e Fornecedores.

1. Tabela produtos (CRUD Produtos)
Esta tabela armazena os detalhes dos itens em estoque.

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_custo DECIMAL(10, 2) NOT NULL, -- Informação sensível (Admin)
    preco_venda DECIMAL(10, 2) NOT NULL,
    quantidade_estoque INT NOT NULL DEFAULT 0,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

2. Tabela fornecedores (CRUD Fornecedores)
Armazena informações sobre as empresas que fornecem os produtos.

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) UNIQUE NOT NULL, -- Informação sensível (Admin)
    telefone VARCHAR(20),
    email VARCHAR(100),
    endereco VARCHAR(255)
);

3. Tabela clientes (CRUD Clientes)
Armazena informações básicas dos clientes para histórico de vendas.

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    documento VARCHAR(18) UNIQUE, -- CPF/CNPJ (Informação sensível - Admin)
    telefone VARCHAR(20),
    email VARCHAR(100),
    endereco VARCHAR(255)
);

4. Tabela movimentacoes (CRUD Movimentações)
Esta é a tabela mais importante para o estoque. Ela registra toda entrada e saída de produtos.

CREATE TABLE movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    tipo ENUM('ENTRADA', 'SAIDA') NOT NULL, -- Se é uma compra (ENTRADA) ou venda (SAIDA)
    quantidade INT NOT NULL,
    data_movimentacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacao VARCHAR(255),
    
    -- Chave estrangeira ligando à tabela de produtos
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

5. Tabela de Relacionamento (Opcional, mas Recomendada)
Embora não seja um CRUD separado, esta tabela é útil para manter o histórico de quais fornecedores fornecem quais produtos.

CREATE TABLE produto_fornecedor (
    produto_id INT NOT NULL,
    fornecedor_id INT NOT NULL,
    PRIMARY KEY (produto_id, fornecedor_id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
);

Relacionamento entre as Tabelas
O relacionamento é estabelecido pelas Chaves Estrangeiras (FKs), que são colunas em uma tabela que referenciam a Chave Primária (PK) de outra tabela.
A. Relacionamento produtos e movimentacoes (Mais Importante)
Tipo de Relação: Um-para-Muitos (1:N).
Um único produto pode ter muitas movimentações (entradas e saídas).
Uma movimentação pertence a apenas um produto.
Como se Relacionam: A tabela movimentacoes contém o campo produto_id (a FK) que aponta para o id (a PK) da tabela produtos.
Lógica de Negócio:
Sempre que uma linha é adicionada em movimentacoes (seja 'ENTRADA' ou 'SAIDA'), o sistema deve atualizar o campo quantidade_estoque na tabela produtos.

B. Relacionamento produtos e fornecedores (Muitos-para-Muitos)
Tipo de Relação: Muitos-para-Muitos (N:M).
Um produto pode ser fornecido por vários fornecedores.
Um fornecedor pode fornecer vários produtos.
Como se Relacionam: Essa relação é resolvida pela tabela intermediária produto_fornecedor.
produto_fornecedor tem duas chaves estrangeiras: produto_id (referencia produtos) e fornecedor_id (referencia fornecedores).


