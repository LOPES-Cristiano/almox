<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <title>AlmoX</title>
    <meta name="description" content="Projeto para gerenciamento de estoque">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/login.css')?>">
</head>
<body>
  <div class="container">
  <form class="form" action="<?= base_url('login/autenticar') ?>" method="post">
    <div class="flex-column">
        <label>Email </label>
    </div>
    <div class="inputForm">
        <input type="text" name="email" class="input" placeholder="Informe seu Email">
    </div>

    <div class="flex-column">
        <label>Senha </label>
    </div>
    <div class="inputForm">
        <input type="password" name="senha" class="input" placeholder="Informe sua senha">
    </div>

    <button class="button-submit">Entrar</button>
</form>

  </div>
  <?= view('partials/modal_spinner') ?>
</body>

</html>
