<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - AlmoX</title>
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
    <?= $this->renderSection('styles') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.9);
}

.modal-content {
  background-color: #f44336;
  margin: 15% auto;
  padding: 20px;
  border-radius: 8px;
  width: 300px;
  color: white;
  text-align: center;
  position: relative;
  box-shadow: 0 0 15px rgba(0,0,0,0.5);
}

.modal-content.success {
  background-color: #4CAF50;
}

.close {
  color: white;
  position: absolute;
  top: 5px;
  right: 10px;
  font-size: 22px;
  cursor: pointer;
}
</style>

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
        <li><a href="<?= base_url('home/usuarios') ?>">Usuários</a></li>
        <li><a href="<?= base_url('home/produtos') ?>">Produtos</a></li>
        <li><a href="<?= base_url('home/fornecedores') ?>">Fornecedores</a></li>
    </ul>

    <h3 class="menu-title">
        <img src="<?= base_url('svg/arrow-left-right.svg') ?>" alt="ícone de setas"> Movimentações
    </h3>
    <ul class="submenu">
        <li><a href="<?= base_url('home/entrada') ?>">Entrada</a></li>
        <li><a href="<?= base_url('home/saida') ?>">Saída</a></li>
        <li><a href="<?= base_url('home/relatorios') ?>">Relatórios</a></li>
    </ul>

    <div class="user-card">
        <h3>Usuário</h3>
        <p><strong>Email:</strong> <?= session('usuario_email') ?></p>
        <p><strong>Nome:</strong> <?= session('usuario_nome') ?></p>
        <a href="<?= base_url('login/logout') ?>">Sair</a>
    </div>
</div>

<div id="globalModal" class="modal">
  <div class="modal-content">
    <span class="close" id="globalModalClose">&times;</span>
    <p id="globalModalMessage"></p>
  </div>
</div>


<div class="content">
    <?= $this->renderSection('content') ?>
</div>

<script>
window.onload = function () {
  const erro = <?= json_encode(session()->getFlashdata('erro')) ?>;
  const sucesso = <?= json_encode(session()->getFlashdata('sucesso')) ?>;

  const modal = document.getElementById('globalModal');
  const message = document.getElementById('globalModalMessage');
  const closeBtn = document.getElementById('globalModalClose');
  const modalContent = document.querySelector('.modal-content');

  if (erro || sucesso) {
    message.textContent = erro || sucesso;

    // muda a cor do modal
    if (erro) {
      modalContent.classList.remove('success');
    } else {
      modalContent.classList.add('success');
    }

    modal.style.display = 'block';

    closeBtn.onclick = function () {
      modal.style.display = 'none';
    };

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    };
  }
};
</script>

</body>
</html>
