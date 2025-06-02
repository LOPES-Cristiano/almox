<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>AlmoX</title>
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
    <?= $this->renderSection('styles') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('img/icone.png') ?>" type="image/png">
</head>

<body>
<div class="sidebar">
    <h2>AlmoX</h2>
    <ul>
        <li>
            <a href="<?= base_url('home') ?>" class="menu-item">
                <img src="<?= base_url('svg/house.svg') ?>" alt="ícone de casa">
                <span>Início</span>
            </a>
        </li>
    </ul>

    <h3 class="menu-title">
        <img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta"> Cadastros
    </h3>
    <ul class="submenu">
        <li><a href="<?= base_url('home/usuarios') ?>">Pessoa</a></li>
        <li><a href="<?= base_url('home/produtos') ?>">Produto</a></li>
    </ul>

   

    <ul>
        <li>
            <a href="<?= base_url('home/movimentos') ?>" class="menu-item">
                <img src="<?= base_url('svg/arrow-left-right.svg') ?>" alt="ícone de setas">
                <span>Movimentações</span>
            </a>
        </li>
    </ul>

     <ul>
        <li>
            <a href="#" id="btnOpenRelatorioAside" class="menu-item">
                <img src="<?= base_url('svg/file-spreadsheet.svg') ?>" alt="ícone de relatório">
                <span>Relatórios</span>
            </a>
        </li>
    </ul>
    <div class="user-card">
        <h3><?= esc($usuario_nome ?? '') ?></h3>
        <p><strong>Função:</strong> <?= esc($usuario_tipo_descricao ?? '') ?></p>
        <a href="<?= base_url('login/logout') ?>">Sair</a>
    </div>
</div>

<div class="content">
    <?= $this->renderSection('content') ?>
</div>

<div id="overlay" class="overlay"></div>

<aside id="relatorioAside" class="modal-aside" aria-hidden="true" role="dialog" aria-labelledby="relatorioAsideTitle">
    <button class="close-btn" id="btnCloseRelatorioAside" aria-label="Fechar modal">&times;</button>
    <h2 id="relatorioAsideTitle">Relatórios</h2>
    <div id="relatorioAsideContent">
        <?= view('partials/relatorio_form') ?>
    </div>
</aside>

<?= view('partials/modal_spinner') ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnOpenRelatorioAside = document.getElementById('btnOpenRelatorioAside');
    const relatorioAside = document.getElementById('relatorioAside');
    const btnCloseRelatorioAside = document.getElementById('btnCloseRelatorioAside');
    const overlay = document.getElementById('overlay');

    if (btnOpenRelatorioAside && relatorioAside && btnCloseRelatorioAside && overlay) {
        btnOpenRelatorioAside.addEventListener('click', function(e) {
            e.preventDefault();
            relatorioAside.classList.add('open');
            relatorioAside.style.display = 'block';
            overlay.style.display = 'flex';
            
            const firstInput = relatorioAside.querySelector('form input, form select');
            if (firstInput) firstInput.focus();
        });
        btnCloseRelatorioAside.addEventListener('click', function() {
            relatorioAside.classList.remove('open');
            relatorioAside.style.display = 'none';
            overlay.style.display = 'none';
        });
        overlay.addEventListener('click', function() {
            if (relatorioAside.classList.contains('open')) {
                relatorioAside.classList.remove('open');
                relatorioAside.style.display = 'none';
                overlay.style.display = 'none';
            }
        });
    }
});
</script>

</body>
</html>
