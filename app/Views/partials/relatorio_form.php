<form class="form" id="formRelatorio" method="get" action="<?= base_url('home/gerarRelatorio') ?>">
    <label class="required" for="tipo">Tipo de Relatório:</label>
    <select name="tipo" id="tipo" class="inputForm" required>
        <option value="pessoas">Pessoas por Tipo</option>
        <option value="produtos_categoria">Produtos por Categoria</option>
        <option value="produtos_unidade">Produtos por Unidade de Medida</option>
        <option value="maiores_estoques">Maiores Estoques</option>
        <option value="ultimas_movimentacoes">Últimas Movimentações</option>
    </select>
   
    <button type="submit" class="button-submit">Gerar PDF</button>
</form>
