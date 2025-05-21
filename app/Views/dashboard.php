<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - AlmoX</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="sidebar">
    <h2>AlmoX</h2>
    <ul>
        <li> <h3> <img src="<?= base_url('svg/house.svg') ?>" alt="ícone de casa"> <a href="#">Início</a></h3></li>
        <li>
            <h3><img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Cadastros</h3>
            <ul class="submenu">
                <li><a href="#">Usuários</a></li>
                <li><a href="#">Produtos</a></li>
                <li><a href="#">Fornecedores</a></li>   
            </ul>
        </li>
        <li>
            <h3><img src="<?= base_url('svg/arrow-left-right.svg') ?>" alt="ícone de caneta">Movimentações</h3>
            <ul class="submenu">
                <li><a href="#">Entrada</a></li>
                <li><a href="#">Saída</a></li>
                <li><a href="#">Relatórios</a></li>
            </ul>
        </li>
    </ul>

    <div class="user-card">
        <h3>Usuário</h3>
        <p><strong>Email:</strong> <?= session('usuario_email') ?></p>
        <p><strong>Nome:</strong> <?= session('usuario_nome') ?></p>
        <a href="<?= base_url('login/logout') ?>">Sair</a>
    </div>
</div>

<div class="content">
    <h1>Bem-vindo ao AlmoX</h1>
</div>

</body>
</html>
