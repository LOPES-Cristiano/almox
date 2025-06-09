# Sistema de Almoxarifado — Guia Técnico e Estrutural

## Visão Geral

Este sistema de almoxarifado foi desenvolvido em **CodeIgniter 4** seguindo o padrão **MVC** (Model-View-Controller). Ele permite o controle de pessoas, produtos, movimentações de estoque, geração de relatórios em PDF e oferece uma interface moderna, responsiva e fácil de usar.

---

## Estrutura do Projeto

### 1. Controllers (app/Controllers)

Os controllers recebem as requisições, processam dados, chamam os models e retornam as views.

-   **HomeController**: Dashboard (resumo do sistema) e geração de relatórios PDF.
-   **PessoaController**: Gerencia pessoas (usuários, clientes, fornecedores, etc).
-   **ProdutoController**: Gerencia produtos, categorias e unidades de medida.
-   **MovimentoController**: Gerencia movimentações de estoque (entradas e saídas).
-   **LoginController**: Autenticação de usuários.

### 2. Models (app/Models)

Os models representam as tabelas do banco e encapsulam a lógica de acesso aos dados.

-   **PessoaModel**: Pessoas (nome, tipo, observação, ativo, etc).
-   **PessoaTipoModel**: Tipos de pessoa (Cliente, Fornecedor, etc).
-   **UsuarioModel**: Usuários do sistema (login, senha, pessoa associada).
-   **ProdutoModel**: Produtos (descrição, categoria, unidade, valor, ativo, etc).
-   **ProdutoCategoriaModel**: Categorias de produto.
-   **ProdutoUnidadeMedidaModel**: Unidades de medida dos produtos.
-   **ArmazemModel**: Estoque de cada produto (quantidade, valor total).
-   **MovimentoModel**: Movimentações de estoque (entrada/saída, produto, quantidade, fornecedor/cliente, data).
-   **MovimentoTipoModel**: Tipos de movimentação (Entrada, Saída).

### 3. Views (app/Views)

As views são arquivos PHP/HTML que exibem a interface para o usuário.

-   **layout.php**: Layout base, inclui sidebar, header, scripts e o conteúdo principal.
-   **home.php**: Dashboard com cards, gráficos e tabelas de resumo.
-   **usuarios.php**: Lista de pessoas, formulário de cadastro/edição em aside/modal.
-   **produtos.php**: Lista de produtos, formulário de cadastro/edição, categorias e unidades.
-   **movimentos/index.php**: Lista de movimentações, formulário de entrada/saída.
-   **partials/relatorio_form.php**: Formulário global para geração de relatórios PDF.
-   **CSS (public/css/...)**: Estilos para tabelas, formulários, dashboard, etc.

### 4. Config (app/Config)

-   **Routes.php**: Define as rotas do sistema (URLs e seus controladores).
-   **Database.php**: Configuração de conexão com o banco de dados.

### 5. Outros diretórios

-   **public/**: Arquivos públicos (CSS, JS, imagens, index.php).
-   **writable/**: Logs, uploads, cache.
-   **tests/**: Testes automatizados.
-   **composer.json / composer.lock**: Gerenciamento de dependências PHP.

---

## Fluxo de Funcionamento

1. **Login**: Usuário acessa o sistema e faz login.
2. **Dashboard**: Vê o resumo do estoque, movimentações e gráficos.
3. **Cadastros**: Pode cadastrar/editar pessoas, produtos, categorias e unidades.
4. **Movimentações**: Registra entradas e saídas de produtos, atualizando o estoque e o valor total automaticamente.
5. **Relatórios**: Gera relatórios em PDF a partir de qualquer tela, usando o formulário global.

---

## Funcionalidades e Recursos

-   **Dashboard**: Cards com indicadores, tabelas de movimentações e estoques, gráficos (ApexCharts).
-   **Cadastro de Pessoas**: Usuários, clientes, fornecedores, tipos de pessoa.
-   **Cadastro de Produtos**: Produtos, categorias, unidades de medida, valor unitário.
-   **Movimentações**: Entradas e saídas de estoque, controle de saldo e valor total do estoque.
-   **Relatórios em PDF**: Geração de relatórios a partir dos dados do dashboard.
-   **Responsividade**: Sidebar vira menu hamburger em telas pequenas, tabelas adaptam-se ao mobile.
-   **Acessibilidade**: Labels, botões e formulários seguem boas práticas.
-   **Segurança**: Filtros de autenticação, CSRF, senhas com hash.

---

## Responsividade e Usabilidade

-   **Sidebar**: Vira menu hamburger em telas menores de 500px.
-   **Tabelas**: Adaptam-se para visualização em dispositivos móveis, exibindo labels acima dos dados.
-   **Formulários**: Usam asides/modais, com visual moderno e overlay.

---

## Relatórios PDF

-   Geração de relatórios a partir do dashboard, exportando dados em PDF (usando mPDF).
-   Relatórios disponíveis: Pessoas por tipo, Produtos por categoria/unidade, Maiores estoques, Últimas movimentações.

---

## Dicas de Uso

-   Use o menu lateral para navegar entre cadastros, movimentações e relatórios.
-   Utilize o botão hamburger em dispositivos móveis para acessar o menu.
-   Os formulários de cadastro/edição abrem em asides (modais laterais), facilitando o uso sem sair da tela principal.
-   Relatórios podem ser gerados a partir de qualquer tela, pelo menu lateral.

---

## Scripts e Banco de Dados

-   O script SQL inicial está em `app/scripts/script001.sql`.
-   Inclui estrutura de tabelas, dados iniciais (tipos, usuário admin, etc).
-   Para rodar o sistema, basta importar o script no seu banco MySQL/MariaDB.

---

## Observações Finais

-   O sistema é modular e fácil de expandir.
-   Segue boas práticas de organização, segurança e usabilidade.
-   Para dúvidas ou contribuições, consulte o README principal ou entre em contato com o desenvolvedor.
