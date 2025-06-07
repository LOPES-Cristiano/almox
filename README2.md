# Explicação Técnica do Código — Sistema de Almoxarifado

## Estrutura Geral

O projeto segue o padrão MVC (Model-View-Controller) utilizando o framework CodeIgniter 4. Abaixo, explico o papel de cada diretório e os principais arquivos:

### 1. app/

#### a) Controllers/
Controladores são responsáveis por receber as requisições, processar dados (usando Models) e retornar as Views.

- **HomeController.php**: Controla a página inicial (dashboard). Busca dados agregados (quantidade de pessoas, produtos, movimentações, etc.) e envia para a view `home.php`.
- **LoginController.php**: Gerencia autenticação de usuários (login/logout).
- **MovimentoController.php**: Gerencia as movimentações de estoque (entrada/saída de produtos).
- **PessoaController.php**: CRUD de pessoas, ativação/inativação, tipos de pessoas.
- **ProdutoController.php**: CRUD de produtos, categorias, unidades de medida, ativação/inativação.

#### b) Models/
Modelos fazem a interface com o banco de dados.

- **PessoaModel.php**: Manipula dados de pessoas.
- **PessoaTipoModel.php**: Manipula tipos de pessoas.
- **ProdutoModel.php**: Manipula dados de produtos.
- **ProdutoCategoriaModel.php**: Manipula categorias de produtos.
- **ProdutoUnidadeMedidaModel.php**: Manipula unidades de medida dos produtos.
- **MovimentoModel.php**: Manipula movimentações de estoque.
- **MovimentoTipoModel.php**: Manipula tipos de movimentação (entrada/saída).
- **UsuarioModel.php**: Manipula dados de usuários do sistema.
- **ArmazemModel.php**: (Se houver) manipula dados de armazéns.

#### c) Views/
Views são os arquivos de interface (HTML + PHP).

- **layout.php**: Template base, inclui cabeçalho, rodapé e define onde o conteúdo das páginas será inserido.
- **home.php**: Dashboard principal, exibe cards com indicadores, tabelas de movimentações e estoques, e gráficos (ApexCharts).
- **login.php**: Tela de login.
- **produtos.php, usuarios.php, movimentos/**: Telas de listagem, cadastro e edição de produtos, usuários e movimentações.
- **partials/**: Componentes reutilizáveis (menus, cabeçalhos, etc).

#### d) Config/
Configurações do sistema, como rotas, banco de dados, serviços, etc.

- **Routes.php**: Define as rotas do sistema (URLs e seus controladores).
- **Database.php**: Configuração de conexão com o banco de dados.

#### e) Filters/
Filtros de autenticação e autorização.

- **UsuarioLogado.php**: Garante que apenas usuários autenticados acessem determinadas rotas.

### 2. public/
Arquivos públicos acessíveis pelo navegador.

- **index.php**: Front controller do CodeIgniter.
- **css/**: Estilos customizados (dashboard, forms, tabelas, etc).
- **js/**: Scripts JS, incluindo gráficos.
- **img/**, **svg/**: Imagens e ícones usados na interface.

### 3. writable/
Diretório para arquivos gerados pelo sistema (logs, uploads, cache, etc).

### 4. tests/
Testes automatizados do sistema.

### 5. composer.json / composer.lock
Gerenciamento de dependências PHP.

---

## Fluxo de Funcionamento

1. **Usuário acessa o sistema**: A requisição chega ao `public/index.php`, que inicializa o CodeIgniter.
2. **Roteamento**: O arquivo `app/Config/Routes.php` direciona a requisição para o Controller correto.
3. **Controller**: O Controller processa a lógica, consulta Models para buscar ou gravar dados.
4. **Model**: O Model executa queries no banco de dados e retorna os dados ao Controller.
5. **View**: O Controller carrega a View, passando os dados necessários.
6. **Renderização**: A View monta o HTML, insere dados dinâmicos e scripts (ex: gráficos ApexCharts).
7. **Resposta**: O navegador exibe a página ao usuário.

---

## Destaques do Dashboard (`home.php`)

- Exibe indicadores de pessoas e produtos (ativos/inativos).
- Tabela com as últimas movimentações de estoque.
- Tabela com os maiores estoques.
- Diversos gráficos (donut, barras, pizza) usando ApexCharts, alimentados por dados vindos do Controller.
- Utiliza variáveis PHP para inserir dados dinâmicos no HTML e nos scripts JS.

---

## Segurança

- Filtros de autenticação para proteger rotas.
- Uso de funções como `esc()` para evitar XSS.
- Validação de dados nos Controllers e Models.

---

## Resumo

O projeto é modular, organizado e segue boas práticas do CodeIgniter. Cada parte tem responsabilidade clara: Controllers processam lógica, Models acessam dados, Views exibem a interface. O dashboard é dinâmico e visual, facilitando o acompanhamento do almoxarifado.
