<?= $this->extend('layout') ?>


<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/forms.css')?>">
<link rel="stylesheet" href="<?= base_url('css/table.css')?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="buttons">
    <button id="btnOpenInsert" class="tableButton"  style="margin-bottom: 15px;"><img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Inserir Pessoa</button>
</div>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 10px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Data Cadastro</th>
            <th>Tipo</th>
            <th>Ativo</th>
            <th>Observação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pessoas) && is_array($pessoas)): ?>
            <?php foreach ($pessoas as $pessoa): ?>
                <tr data-id="<?= esc($pessoa['pes_id']) ?>" 
                data-nome="<?= esc($pessoa['pes_nome']) ?>" 
                data-email="<?= esc($pessoa['usu_email']) ?>" 
                data-tipo="<?= esc($pessoa['pest_id']) ?>"
                data-ativo="<?= esc($pessoa['pes_ativo']) ?>" 
                data-observacao="<?= esc($pessoa['pes_observacao']) ?>">

                    <td><?= esc($pessoa['pes_id']) ?></td>
                    <td><?= esc($pessoa['pes_nome']) ?></td>
                    <td><?= esc($pessoa['usu_email']) ?></td>
                    <td><?= esc($pessoa['pes_datacadastro']) ?></td>
                    <td><?= esc($pessoa['pest_descricao']) ?></td>
                    <td><?= esc($pessoa['pes_observacao']) ?></td>
                    <td>
                    <?php if ($pessoa['pes_ativo']): ?>
                        <img src="<?= base_url('svg/check.svg') ?>" alt="Ativo" width="20" height="20">
                    <?php else: ?>
                        <img src="<?= base_url('svg/x.svg') ?>" alt="Inativo" width="20" height="20">
                    <?php endif; ?>
                    </td>

                    <td>
                        <button class="btnEdit" data-id="<?= esc($pessoa['pes_id']) ?>" >Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhuma pessoa cadastrada.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="overlay" class="overlay"></div>

<aside id="modalAside" class="modal-aside" aria-hidden="true" role="dialog" aria-labelledby="modalTitle">
    <button class="close-btn" id="btnCloseModal" aria-label="Fechar modal">&times;</button>

    <h2 id="modalTitle">Inserir Pessoa</h2>

    <form id="formPessoa" method="post" action="<?= base_url('home/usuarios/salvar') ?>">
        <?= csrf_field() ?>

        <input type="hidden" name="pes_id" id="pes_id" value="" >

        <label class="required" for="nome">Nome:</label>
        <input type="text" name="nome" id="nome"  class="inputForm" required>

        <label class="required" for="email">Email:</label>
        <input type="email" name="email" id="email" class="inputForm"required>

        <label class="required" for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" class="inputForm"><small><em>Deixe em branco para manter a senha atual no editar.</em></small>

        <label class="required" for="tipo">Tipo de Pessoa:</label>
        <select name="tipo" id="tipo" class="inputForm" required>
            <option value="">Selecione o tipo</option>
            <?php if (!empty($tipos)): ?>
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= $tipo['pest_id'] ?>"><?= $tipo['pest_descricao'] ?></option>
            <?php endforeach; ?>
            <?php else: ?>
                <option disabled>Não há nenhum registro</option>
            <?php endif; ?>
        </select>

        <label for="observacao">Observação:</label>
        <input type="text" name="observacao" id="observacao" class="inputForm">

        <label class="custom-checkbox required" for="ativo">
            <input type="checkbox" id="ativo" name="ativo" value="1">
            <span class="checkmark"></span>
            Ativo
            </label>

        <button type="submit" class="button-submit" id="btnSubmit">Salvar</button>
    </form>
</aside>

<script>
    const btnOpenInsert = document.getElementById('btnOpenInsert');
    const modalAside = document.getElementById('modalAside');
    const overlay = document.getElementById('overlay');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const formPessoa = document.getElementById('formPessoa');

    btnOpenInsert.addEventListener('click', () => {
        formPessoa.reset();
        modalAside.classList.add('open');
        overlay.style.display = 'flex';
        modalAside.querySelector('h2').textContent = 'Inserir Pessoa';
        formPessoa.action = "<?= base_url('home/usuarios/salvar') ?>";
        document.getElementById('pes_id').value = '';
        document.getElementById('senha').required = true;
    });

    function fecharModal() {
        modalAside.classList.remove('open');
        overlay.style.display = 'none';
    }

    btnCloseModal.addEventListener('click', fecharModal);

    overlay.addEventListener('click', fecharModal);

    document.querySelectorAll('.btnEdit').forEach(btn => {
    btn.addEventListener('click', () => {
        const tr = btn.closest('tr');
        const pes_id = btn.getAttribute('data-id');
        const nome = tr.getAttribute('data-nome');
        const email = tr.getAttribute('data-email');
        const observacao = tr.getAttribute('data-observacao') || '';
        const tipo = tr.getAttribute('data-tipo') || '';
        const ativo = tr.getAttribute('data-ativo');
        
        formPessoa.reset();
        modalAside.classList.add('open');
        overlay.style.display = 'flex';
        modalAside.querySelector('h2').textContent = 'Editar Pessoa';
        formPessoa.action = "<?= base_url('home/usuarios/atualizar/') ?>" + pes_id;
        
        document.getElementById('pes_id').value = pes_id;
        document.getElementById('nome').value = nome;
        document.getElementById('email').value = email;
        document.getElementById('senha').value = '';
        document.getElementById('senha').required = false;
        document.getElementById('tipo').value = tipo;
        document.getElementById('ativo').checked = ativo === '1';
        document.getElementById('observacao').value = observacao;
        
    });
});
</script>

<?= $this->endSection() ?>