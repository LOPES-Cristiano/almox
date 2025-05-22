# 🗃️ AlmoX - Sistema de Gestão de Almoxarifado

**AlmoX** é um sistema de controle de estoque para almoxarifados, desenvolvido com **PHP (CodeIgniter 4)**, **MySQL** e **Tailwind CSS**.  
O objetivo é facilitar o gerenciamento de pessoas, produtos, entradas e saídas de estoque, com foco em simplicidade, desempenho e usabilidade.

---

## 📸 Prévia da Interface

_(em breve...)_

---

## 🔧 Tecnologias Utilizadas

-   ✅ **PHP 8+** com CodeIgniter 4
-   ✅ **MySQL 8**
-   ✅ **JavaScript nativo** para interações com modais e asides

---

## 📁 Estrutura do Projeto

```
/app
├── Controllers/
│   └── Pessoa.php
├── Models/
│   └── PessoaModel.php
├── Views/
│   ├── layout/
│   └── pessoas/
│       ├── index.php
│       └── form.php
/public
│   ├── css/
│   └── js/
.env
composer.json
```

---

## ✅ Funcionalidades

-   👤 Cadastro e edição de pessoas
-   📦 Cadastro de produtos
-   📈 Controle de entradas e saídas
-   🔍 Filtros e pesquisa
-   📊 Visualização de saldo de estoque
-   🧾 Geração de relatórios _(em breve)_
-   🌙 Formulários em modo **Aside** com overlay moderno

---

## ▶️ Como rodar localmente

### 1. Clone o repositório:

```bash
git clone https://github.com/seu-usuario/almox.git
cd almox
```

### 2. Instale as dependências:

```bash
composer install
```

### 3. Configure o banco de dados em `.env`:

```
database.default.hostname = localhost
database.default.database = almox
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 4. Rode o servidor:

```bash
php spark serve
```

### 5. Acesse no navegador:

[http://localhost:8080](http://localhost:8080)

---

## 📌 Padrões e Organização

-   Estrutura **MVC**
-   Organização modular por componentes reutilizáveis
-   Separação clara entre controle, visualização e regra de negócio

---

## ✨ Possíveis Melhorias Futuras

-   🔐 Autenticação com permissões (ACL)
-   📱 Responsividade total para mobile
-   📄 Geração de relatórios em PDF
-   📊 Dashboard com gráficos interativos

---

## 🤝 Contribuição

Sinta-se à vontade para contribuir com melhorias ou sugestões!  
Basta abrir um **Pull Request** ou registrar uma **Issue** neste repositório.

---

## ⭐ Licença

Este projeto é open-source sob a licença MIT.
