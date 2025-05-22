🗃️ AlmoX - Sistema de Gestão de Almoxarifado
AlmoX é um sistema de controle de estoque para almoxarifados, desenvolvido com PHP (CodeIgniter 4), MySQL e Tailwind CSS. O objetivo é facilitar o gerenciamento de pessoas, produtos, entradas e saídas de estoque, com foco em simplicidade, desempenho e usabilidade.

📸 Prévia da Interface

🔧 Tecnologias Utilizadas
✅ PHP 8+ com CodeIgniter 4

✅ MySQL 8

✅ JavaScript nativo para interações com modais e asides

📁 Estrutura do Projeto

/app
├── Controllers/
│ └── Pessoa.php
├── Models/
│ └── PessoaModel.php
├── Views/
│ ├── layout/
│ ├── pessoas/
│ │ ├── index.php
│ │ └── form.php
/public
│ ├── css/
│ ├── js/
.env
composer.json

Funcionalidades
👤 Cadastro e edição de pessoas

📦 Cadastro de produtos

📈 Controle de entradas e saídas

🔍 Filtros e pesquisa

📊 Visualização de saldo de estoque

🧾 Geração de relatórios (em breve)

🌙 Modo Aside para formulários com overlay moderno

▶️ Como rodar localmente
Clone o repositório:

git clone https://github.com/seu-usuario/almox.git
cd almox

Instale as dependências:

composer install

Configure o banco de dados em .env:

database.default.hostname = localhost
database.default.database = almox
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

Rode o servidor:

php spark serve

Acesse:

http://localhost:8080

📌 Padrões e Organização
Estrutura MVC com organização por módulos

Componentes reutilizáveis para formulários

✨ Possíveis Melhorias Futuras
Autenticação com permissões (ACL)

Responsividade mobile

Relatórios em PDF

Dashboard com gráficos

🤝 Contribuição
Sinta-se à vontade para contribuir com melhorias ou sugestões! Basta abrir um Pull Request ou uma Issue.
