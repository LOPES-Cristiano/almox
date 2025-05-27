<?= $this->extend('layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
<div class="dashboard-flex-container">

    <div class="card-pequeno card-pessoas">
        <div class="card-icon">👤</div>
        <h3>Pessoas</h3>
        <div class="card-content">
            <p>Ativas: <?= $pessoasAtivas ?></p>
            <p>Inativas: <?= $pessoasInativas ?></p>
        </div>
    </div>

    <div class="card-pequeno card-produtos">
        <div class="card-icon">📦</div>
        <h3>Produtos</h3>
        <div class="card-content">
            <p>Ativos: <?= $produtosAtivos ?></p>
            <p>Inativos: <?= $produtosInativos ?></p>
        </div>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Gráfico de Pessoas</h4>
        <canvas id="chartPessoas"></canvas>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Gráfico de Produtos</h4>
        <canvas id="chartProdutos"></canvas>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Pessoas por Tipo</h4>
        <canvas id="chartPessoasTipo"></canvas>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Produtos por Categoria</h4>
        <canvas id="chartProdutosCategoria"></canvas>
    </div>

    <div class="chart-box">
        <h4 class="chart-title">Produtos por Unidade de Medida</h4>
        <canvas id="chartProdutosUnidade"></canvas>
    </div>

    <div class="chart-box">
    <h4 class="chart-title">Movimentações por Tipo</h4>
    <canvas id="chartMovimentosTipo"></canvas>
</div>

</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="<?= base_url('js/chart.js') ?>"></script>


<script>

    new Chart(document.getElementById('chartPessoas').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Ativas', 'Inativas'],
            datasets: [{
                data: [<?= $pessoasAtivas ?>, <?= $pessoasInativas ?>],
                backgroundColor: ['#ffb347', '#4a4a4a'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 14 } } },
                tooltip: { enabled: true }
            }
        }
    });


    new Chart(document.getElementById('chartProdutos').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Ativos', 'Inativos'],
            datasets: [{
                data: [<?= $produtosAtivos ?>, <?= $produtosInativos ?>],
                backgroundColor: ['#ffb347', '#4a4a4a'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 14 } } },
                tooltip: { enabled: true }
            },
            
        }
    });


    new Chart(document.getElementById('chartPessoasTipo').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($tiposPessoas) ?>,
            datasets: [{
                label: 'Quantidade',
                data: <?= json_encode($quantidadesTipos) ?>,
                backgroundColor: '#ffb347',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                x: { grid: { display: true } }
            },
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            }
        }
    });


    new Chart(document.getElementById('chartProdutosCategoria').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($categoriasProdutos) ?>,
            datasets: [{
                label: 'Quantidade',
                data: <?= json_encode($quantidadesCategorias) ?>,
                backgroundColor: '#4caf50',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                x: { grid: { display: true } }
            },
            plugins: {
                legend: { display:true },
                tooltip: { enabled: true }
            }
        }
    });


    new Chart(document.getElementById('chartProdutosUnidade').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($unidadesProdutos) ?>,
            datasets: [{
                label: 'Quantidade',
                data: <?= json_encode($quantidadesUnidades) ?>,
                backgroundColor: '#2196f3',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                x: { grid: { display: true } }
            },
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            }
        }
    });

    new Chart(document.getElementById('chartMovimentosTipo').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($tiposMovimentos) ?>,
        datasets: [{
            data: <?= json_encode($quantidadesMovimentos) ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
            ],
            borderWidth: 1,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 14 } } },
            tooltip: { enabled: true }
        }
    }
});
</script>

<?= $this->endSection() ?>
