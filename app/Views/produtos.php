<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/table.css') ?>">

<style>
    #formProduto, #formCategoria, #formUnidade {
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="buttons">
    <button id="btnOpenInsertProduto" class="tableButton" style="margin-bottom: 15px;">
        <img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Inserir Produto
    </button>
    <button id="btnOpenInsertCategoria" class="tableButton" style="margin-bottom: 15px; margin-left: 10px;">
        <img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Inserir Categoria
    </button>
    <button id="btnOpenInsertUnidade" class="tableButton" style="margin-bottom: 15px; margin-left: 10px;">
        <img src="<?= base_url('svg/square-pen.svg') ?>" alt="ícone de caneta">Inserir Unidade de Medida
    </button>
</div>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 10px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Descrição</th>
            <th>Data Cadastro</th>
            <th>Categoria</th>
            <th>Unidade de Medida</th>
            <th>Saldo</th>
            <th>Valor Unitário</th>
            <th>Valor Total Estoque</th>
            <th>Ativo</th>
            <th>Observação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($produtos) && is_array($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <tr 
                    data-id="<?= esc($produto['pro_id']) ?>" 
                    data-descricao="<?= esc($produto['pro_descricao']) ?>" 
                    data-datacadastro="<?= esc($produto['pro_datacadastro']) ?>" 
                    data-procat_id="<?= esc($produto['procat_id']) ?>"
                    data-proum_id="<?= esc($produto['proum_id']) ?>"
                    data-valor="<?= esc($produto['pro_valor']) ?>"
                    data-ativo="<?= esc($produto['pro_ativo']) ?>"
                    data-observacao="<?= esc($produto['pro_observacao']) ?>"
                >
                    <td><?= esc($produto['pro_id']) ?></td>
                    <td><?= esc($produto['pro_descricao']) ?></td>
                    <td><?= esc($produto['pro_datacadastro']) ?></td>
                    <td><?= esc($produto['procat_descricao']) ?></td>
                    <td><?= esc($produto['proum_descricao']) ?></td>
                    <td><?= esc($produto['saldo_estoque']) ?></td>
                    <td><?= number_format($produto['pro_valor'], 2, ',', '.') ?></td>
                    <td><?= number_format($produto['valor_estoque'], 2, ',', '.') ?></td>
                    <td>
                    <?php if ($produto['pro_ativo']): ?>
                        <img src="<?= base_url('svg/check.svg') ?>" alt="Ativo" width="20" height="20">
                    <?php else: ?>
                        <img src="<?= base_url('svg/x.svg') ?>" alt="Inativo" width="20" height="20">
                    <?php endif; ?>
                    </td>
                    <td><?= esc($produto['pro_observacao']) ?></td>
                    <td>
                        <button class="btnEdit" data-id="<?= esc($produto['pro_id']) ?>">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="overlay" class="overlay"></div>

<aside id="modalAside" class="modal-aside" aria-hidden="true" role="dialog" aria-labelledby="modalTitle">
    <button class="close-btn" id="btnCloseModal" aria-label="Fechar modal">&times;</button>
    <h2 id="modalTitle">Inserir Produto</h2>

    <div id="modalContent">
       
        <form class="form" id="formProduto" method="post" action="<?= base_url('home/produtos/inserirProduto') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="pro_id" id="pro_id" value="" >

            <label class="required" for="descricaoProduto">Descrição:</label>
            <input type="text" name="descricao" id="descricaoProduto" class="inputForm" required>

            <label class="required" for="categoriaProduto">Categoria:</label>
            <select name="categoria" id="categoriaProduto" class="inputForm" required>
                <option value="">Selecione a categoria</option>
                <?php foreach ($categorias ?? [] as $categoria): ?>
                    <option value="<?= esc($categoria['procat_id']) ?>"><?= esc($categoria['procat_descricao']) ?></option>
                <?php endforeach; ?>
            </select>

            <label class="required" for="unidademedidaProduto">Unidade de Medida:</label>
            <select name="unidademedida" id="unidademedidaProduto" class="inputForm" required>
                <option value="">Selecione a unidade</option>
                <?php foreach ($unidades ?? [] as $unidade): ?>
                    <option value="<?= esc($unidade['proum_id']) ?>"><?= esc($unidade['proum_descricao']) ?></option>
                <?php endforeach; ?>
            </select>

            <label class="required" for="valorProduto">Valor Unitário:</label>
            <input type="number" name="valor" id="valorProduto" class="inputForm" step="0.01" min="0" required>

            <label for="observacaoProduto">Observação:</label>
            <input type="text" name="observacao" id="observacaoProduto" class="inputForm">

         
            <label class="custom-checkbox required" for="ativoProduto">
            <input type="checkbox" id="ativoProduto" name="ativo" value="1">
            <span class="checkmark"></span>
            Ativo
            </label>

            <button type="submit" class="button-submit" id="btnSubmitProduto">Salvar</button>
        </form>

      
        <form class="form" id="formCategoria" method="post" action="<?= base_url('home/produtos/inserirCategoria') ?>" >
            <?= csrf_field() ?>

            <label class="required" for="descricaoCategoria">Descrição:</label>
            <input type="text" name="descricao" id="descricaoCategoria" class="inputForm" required>

            <label for="observacaoCategoria">Observação:</label>
            <input type="text" name="observacao" id="observacaoCategoria" class="inputForm">

            <button type="submit" class="button-submit" id="btnSubmitCategoria">Salvar</button>
        </form>

      
        <form class="form" id="formUnidade" method="post" action="<?= base_url('home/produtos/inserirUnidadeMedida') ?>">
            <?= csrf_field() ?>

            <label class="required" for="descricaoUnidade">Descrição:</label>
            <input type="text" name="descricao" id="descricaoUnidade" class="inputForm" required>

            <label for="observacaoUnidade">Observação:</label>
            <input type="text" name="observacao" id="observacaoUnidade" class="inputForm">

            <button type="submit" class="button-submit" id="btnSubmitUnidade">Salvar</button>
        </form>
    </div>
</aside>

<script>
    const btnOpenInsertProduto = document.getElementById('btnOpenInsertProduto');
    const btnOpenInsertCategoria = document.getElementById('btnOpenInsertCategoria');
    const btnOpenInsertUnidade = document.getElementById('btnOpenInsertUnidade');

    const modalAside = document.getElementById('modalAside');
    const overlay = document.getElementById('overlay');
    const btnCloseModal = document.getElementById('btnCloseModal');

    const formProduto = document.getElementById('formProduto');
    const formCategoria = document.getElementById('formCategoria');
    const formUnidade = document.getElementById('formUnidade');

    function esconderTodosOsFormularios() {
        formProduto.style.display = 'none';
        formCategoria.style.display = 'none';
        formUnidade.style.display = 'none';
    }

    function fecharModal() {
        modalAside.classList.remove('open');
        overlay.style.display = 'none';
    }

    btnCloseModal.addEventListener('click', fecharModal);
    overlay.addEventListener('click', fecharModal);

    btnOpenInsertProduto.addEventListener('click', () => {
        esconderTodosOsFormularios();
        formProduto.reset();
        formProduto.style.display = 'flex';
        modalAside.classList.add('open');
        overlay.style.display = 'flex';
        modalAside.querySelector('#modalTitle').textContent = 'Inserir Produto';
        formProduto.action = "<?= base_url('home/produtos/inserirProduto') ?>";
        document.getElementById('pro_id').value = '';
    });

    btnOpenInsertCategoria.addEventListener('click', () => {
        esconderTodosOsFormularios();
        formCategoria.reset();
        formCategoria.style.display = 'flex';
        modalAside.classList.add('open');
        overlay.style.display = 'flex';
        modalAside.querySelector('#modalTitle').textContent = 'Inserir Categoria de Produto';
        formCategoria.action = "<?= base_url('home/produtos/inserirCategoria') ?>";
    });

    btnOpenInsertUnidade.addEventListener('click', () => {
        esconderTodosOsFormularios();
        formUnidade.reset();
        formUnidade.style.display = 'flex';
        modalAside.classList.add('open');
        overlay.style.display = 'flex';
        modalAside.querySelector('#modalTitle').textContent = 'Inserir Unidade de Medida';
        formUnidade.action = "<?= base_url('home/produtos/inserirUnidadeMedida') ?>";
    });

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', () => {
            const tr = btn.closest('tr');

            const pro_id = btn.getAttribute('data-id');
            const descricao = tr.getAttribute('data-descricao');
            const datacadastro = tr.getAttribute('data-datacadastro');
            const categoria = tr.getAttribute('data-procat_id');
            const unidademedida = tr.getAttribute('data-proum_id');
            const valor = tr.getAttribute('data-valor');
            const ativo = tr.getAttribute('data-ativo');
            const observacao = tr.getAttribute('data-observacao') || '';

            esconderTodosOsFormularios();
            formProduto.reset();
            formProduto.style.display = 'flex';
            modalAside.classList.add('open');
            overlay.style.display = 'flex';
            modalAside.querySelector('#modalTitle').textContent = 'Editar Produto';
            formProduto.action = "<?= base_url('home/produtos/atualizarProduto/') ?>" + pro_id;

            document.getElementById('pro_id').value = pro_id;
            document.getElementById('descricaoProduto').value = descricao;
            document.getElementById('categoriaProduto').value = categoria;
            document.getElementById('unidademedidaProduto').value = unidademedida;
            document.getElementById('valorProduto').value = valor;
            document.getElementById('ativoProduto').checked = ativo === '1';
            document.getElementById('observacaoProduto').value = observacao;
        });
    });
</script>

<?= $this->endSection() ?>
