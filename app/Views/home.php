<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
</div>

<div class="dashboard-flex-container">
    
        <div class="card-pequeno card-pessoas">
            <div class="card-icon">👤</div>
            <div class="card-content">
                <h3>Pessoas</h3>
                <p>Ativas: <span style="color:var(--amarelo-ouro)"><?= $pessoasAtivas ?></span></p>
                <p>Inativas: <span style="color:var(--preto-medio)"><?= $pessoasInativas ?></span></p>
            </div>
        </div>

        <div class="card-pequeno card-produtos">
            <div class="card-icon">📦</div>
            <div class="card-content">
                <h3>Produtos</h3>
                <p>Ativos: <span style="color:var(--amarelo-ouro)"><?= $produtosAtivos ?></span></p>
                <p>Inativos: <span style="color:var(--preto-medio)"><?= $produtosInativos ?></span></p>
            </div>
        </div>
   

    <div class="chart-box">
        <h4 class="chart-title">Últimas Movimentações</h4>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimasMovimentacoes as $mov): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($mov['mov_data'])) ?></td>
                        <td><?= esc($mov['tipo']) ?></td>
                        <td><?= esc($mov['produto']) ?></td>
                        <td><?= esc($mov['quantidade']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Maiores Estoques</h4>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Estoque</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maioresEstoques as $item): ?>
                    <tr>
                        <td><?= esc($item['produto']) ?></td>
                        <td><?= esc($item['estoque']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Gráfico de Pessoas</h4>
        <div id="chartPessoas" class="chart-echarts"></div>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Pessoas por Tipo</h4>
        <div id="chartPessoasTipo" class="chart-echarts"></div>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Produtos por Categoria</h4>
        <div id="chartProdutosCategoria" class="chart-echarts"></div>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Produtos por Unidade de Medida</h4>
        <div id="chartProdutosUnidade" class="chart-echarts"></div>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Produtos Ativos vs Inativos</h4>
        <div id="chartProdutosStatus" class="chart-echarts"></div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  // Gráfico Pessoas (Donut)
 var optionsPessoas = {
    chart: { type: 'donut', height: 260 },
    series: [<?= $pessoasAtivas ?>, <?= $pessoasInativas ?>],
    labels: ['Ativas', 'Inativas'],
    colors: ['#007bff', '#222'],
    legend: { show: true, position: 'bottom', fontWeight: 600 },
    dataLabels: {
        enabled: true,
        style: { fontWeight: 700, fontSize: '14px', colors: ['#222'] },
        dropShadow: { enabled: false },
        offsetY: 0,
        offsetX: 0,
        formatter: function (val, opts) {
            return val.toFixed(1) + '%';
        },
        background: { enabled: false },
        minAngleToShowLabel: 10,
        offset: 20
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total',
                        fontSize: '16px',
                        fontWeight: 700,
                        color: '#222',
                        formatter: function (w) {
                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            },
            dataLabels: {
                offset: 36
            }
        }
    }
};
new ApexCharts(document.querySelector('#chartPessoas'), optionsPessoas).render();

  // Gráfico Produtos por Status (Bar)
  var optionsProdutosStatus = {
    chart: { type: 'bar', height: 260 },
    series: [{
      name: 'Quantidade',
      data: [<?= $produtosAtivos ?>, <?= $produtosInativos ?>]
    }],
    xaxis: {
      categories: ['Ativos', 'Inativos'],
      labels: { style: { colors: '#222', fontWeight: 700 } }
    },
    colors: ['#17a2b8', '#6c757d'],
    plotOptions: {
      bar: {
        borderRadius: 6,
        columnWidth: '50%'
      }
    },
    dataLabels: {
      enabled: true,
      offsetY: -18,
      style: { fontWeight: 700, fontSize: '14px', colors: ['#222'] }
    },
    grid: { borderColor: '#bbb' },
    yaxis: { labels: { style: { colors: '#222', fontWeight: 700 } } }
  };
  new ApexCharts(document.querySelector('#chartProdutosStatus'), optionsProdutosStatus).render();

  // Gráfico Pessoas por Tipo (Bar)
  var optionsPessoasTipo = {
    chart: { type: 'bar', height: 260 },
    series: [{
      name: 'Quantidade',
      data: <?= json_encode($quantidadesTipos) ?>
    }],
    xaxis: {
      categories: <?= json_encode($tiposPessoas) ?>,
      labels: { style: { colors: '#222', fontWeight: 700 } }
    },
    colors: ['#007bff', '#222'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
    dataLabels: { enabled: true, offsetY: -18, style: { fontWeight: 700, fontSize: '14px', colors: ['#222'] } },
    grid: { borderColor: '#bbb' },
    yaxis: { labels: { style: { colors: '#222', fontWeight: 700 } } }
  };
  new ApexCharts(document.querySelector('#chartPessoasTipo'), optionsPessoasTipo).render();

  // Gráfico Produtos por Categoria (Bar)
  var optionsProdutosCategoria = {
    chart: { type: 'bar', height: 260 },
    series: [{
      name: 'Quantidade',
      data: <?= json_encode($quantidadesCategorias) ?>
    }],
    xaxis: {
      categories: <?= json_encode($categoriasProdutos) ?>,
      labels: { style: { colors: '#222', fontWeight: 700 } }
    },
    colors: ['#28a745'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
    dataLabels: {
      enabled: true,
      offsetY: -18,
      style: { fontWeight: 700, fontSize: '14px', colors: ['#222'] }
    },
    grid: { borderColor: '#bbb' },
    yaxis: { labels: { style: { colors: '#222', fontWeight: 700 } } }
  };
  new ApexCharts(document.querySelector('#chartProdutosCategoria'), optionsProdutosCategoria).render();

  // Gráfico Produtos por Unidade de Medida (Pie)
  var optionsProdutosUnidade = {
    chart: { type: 'pie', height: 260 },
    series: <?= json_encode($quantidadesUnidades) ?>,
    labels: <?= json_encode($unidadesProdutos) ?>,
    colors: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
    legend: { show: true, position: 'bottom', fontWeight: 600 },
    dataLabels: {
      enabled: true,
      style: { fontWeight: 700, fontSize: '14px', colors: ['#222'] },
      dropShadow: { enabled: false },
      formatter: function (val) {
        return val.toFixed(1) + '%';
      }
    }
  };
  new ApexCharts(document.querySelector('#chartProdutosUnidade'), optionsProdutosUnidade).render();

</script>
<?= $this->endSection() ?>