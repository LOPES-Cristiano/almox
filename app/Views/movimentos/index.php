<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/table.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="buttons">
    <button id="btnOpenInsertMovimento" class="tableButton" >
        <img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Inserir Movimento
    </button>
</div>

<div class="tab-buttons">
    <a href="<?= base_url('movimento/1') ?>" class="tab <?= (isset($tipoSelecionado) && $tipoSelecionado == 1) ? 'active' : '' ?>">Entradas</a>
    <a href="<?= base_url('movimento/2') ?>" class="tab <?= (isset($tipoSelecionado) && $tipoSelecionado == 2) ? 'active' : '' ?>">Saídas</a>
</div>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
    <thead>
        <tr>
            <th>Data</th>
            <th>Tipo</th>
            <th>Produto</th>
            <th>Fornecedor / Cliente</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimentos as $m): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($m['mov_data'])) ?></td>
                <td><?= $m['mov_descricao'] ?></td>
                <td><?= $m['pro_descricao'] ?></td>
                <td>
                <?= $m['fornecedor_nome'] ?? $m['cliente_nome'] ?? '' ?>
                </td>
                <td><?= $m['mov_observacao'] ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div id="overlay" class="overlay"></div>

<aside id="modalAside" class="modal-aside" aria-hidden="true" role="dialog" aria-labelledby="modalTitle">
    <button class="close-btn" id="btnCloseModal" aria-label="Fechar modal">&times;</button>
    <h2 id="modalTitle">Inserir Movimento</h2>

    <div id="modalContent">
        <form class="form" id="formMovimento" method="post" action="<?= base_url('movimento/salvar') ?>">
            <?= csrf_field() ?>

            <label class="required" for="movimentoTipo">Tipo:</label>
            <select name="movt_id" id="movimentoTipo" class="inputForm" required>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= $tipo['movt_id'] ?>"><?= esc($tipo['mov_descricao']) ?></option>
                <?php endforeach ?>
            </select>

            <label class="required" for="movimentoProduto">Produto:</label>
            <select name="pro_id" id="movimentoProduto" class="inputForm" required>
                <?php foreach ((new \App\Models\ProdutoModel())->findAll() as $produto): ?>
                    <option value="<?= $produto['pro_id'] ?>"><?= esc($produto['pro_descricao']) ?></option>
                <?php endforeach ?>
            </select>

            <label class="required" for="movimentoQuantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="movimentoQuantidade" class="inputForm" required min="1">
                   
        <div id="divFornecedor" style="display:none;">
            <label class="required" for="movimentoFornecedor">Fornecedor:</label>
            <select name="mov_fornecedor" id="movimentoFornecedor" class="inputForm">
                <option value="">Selecione o fornecedor</option>
                <?php foreach ($fornecedores as $f): ?>
                    <option value="<?= $f['pes_id'] ?>"><?= esc($f['pes_nome']) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div id="divCliente" style="display:none;">
            <label class="required" for="movimentoCliente">Cliente:</label>
            <select name="mov_cliente" id="movimentoCliente" class="inputForm">
            <option value="">Selecione o cliente</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?= $c['pes_id'] ?>"><?= esc($c['pes_nome']) ?></option>
            <?php endforeach ?>
            </select>
        </div>

            <label for="movimentoObservacao">Observação:</label>
            <textarea name="mov_observacao" id="movimentoObservacao" class="inputForm"></textarea>

            <button type="submit" class="button-submit">Salvar</button>
        </form>
    </div>
</aside>

<script>
    const btnOpenInsertMovimento = document.getElementById('btnOpenInsertMovimento');
    const modalAside = document.getElementById('modalAside');
    const overlay = document.getElementById('overlay');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const selectTipoMovimento = document.getElementById('movimentoTipo');
    const campoFornecedor = document.getElementById('divFornecedor');
    const campoCliente = document.getElementById('divCliente');

    function closeAllModals() {
        modalAside.classList.remove('open');
        overlay.style.display = 'none';
    }

    btnOpenInsertMovimento.addEventListener('click', () => {
        closeAllModals(); 
        modalAside.classList.add('open');
        overlay.style.display = 'block';
    });

    btnCloseModal.addEventListener('click', () => {
        modalAside.classList.remove('open');
        overlay.style.display = 'none';
    });

    overlay.addEventListener('click', () => {
        closeAllModals();
    });

    function toggleCamposRelacionados() {
        const tipoSelecionado = parseInt(selectTipoMovimento.value);
        
        if (tipoSelecionado === 1) {
            campoFornecedor.style.display = 'block';
            campoCliente.style.display = 'none';
        } else if (tipoSelecionado === 2) {
            campoFornecedor.style.display = 'none';
            campoCliente.style.display = 'block';
        } else {
            campoFornecedor.style.display = 'none';
            campoCliente.style.display = 'none';
        }
    }

    selectTipoMovimento.addEventListener('change', toggleCamposRelacionados);
    window.addEventListener('DOMContentLoaded', toggleCamposRelacionados);
</script>

<?= $this->endSection() ?>