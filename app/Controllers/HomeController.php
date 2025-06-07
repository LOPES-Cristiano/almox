<?php

namespace App\Controllers;

use App\Models\PessoaModel;
use App\Models\UsuarioModel;
use App\Models\ProdutoModel;
use App\Models\ProdutoCategoriaModel;
use App\Models\ProdutoUnidadeMedidaModel;
use Mpdf\Mpdf;

class HomeController extends BaseController
{
    protected $produtoModel;
    protected $categoriaModel;
    protected $unidadeMedidaModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new ProdutoCategoriaModel();
        $this->unidadeMedidaModel = new ProdutoUnidadeMedidaModel();
    }

    public function index()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        $usuarioModel = new \App\Models\UsuarioModel();
        $pessoaModel = new \App\Models\PessoaModel();
        $pessoaTipoModel = new \App\Models\PessoaTipoModel();
        $produtoModel = new \App\Models\ProdutoModel();
        $armazemModel = new \App\Models\ArmazemModel();
        $movimentoModel = new \App\Models\MovimentoModel();
        $movimentoTipoModel = new \App\Models\MovimentoTipoModel();

        $usuario = $usuarioModel->find(session('usuario_id'));
        $pessoa = $pessoaModel->find($usuario['pes_id']);
        $tipoDescricao = '';
        if ($pessoa && isset($pessoa['pest_id'])) {
            $tipo = $pessoaTipoModel->find($pessoa['pest_id']);
            $tipoDescricao = $tipo['pest_descricao'] ?? '';
        }
    
        $produtoModel = new \App\Models\ProdutoModel();
        $produtoCategoriaModel = new \App\Models\ProdutoCategoriaModel();
        $produtoUnidadeModel = new \App\Models\ProdutoUnidadeMedidaModel();
    
        
        $tipos = $pessoaTipoModel->findAll();
        $tiposDescricao = [];
        $quantidadesPessoasTipo = [];
        foreach ($tipos as $tipo) {
            $produtoModel->resetQuery();
            $quantidade = $pessoaModel->where('pest_id', $tipo['pest_id'])->countAllResults();
            $tiposDescricao[] = $tipo['pest_descricao'];
            $quantidadesPessoasTipo[] = $quantidade;
        }
    
        
        $categorias = $produtoCategoriaModel->findAll();
        $categoriasDescricao = [];
        $quantidadesProdutosCategoria = [];
        foreach ($categorias as $categoria) {
            $produtoModel->resetQuery();
            $quantidade = $produtoModel->where('procat_id', $categoria['procat_id'])->countAllResults();
            $categoriasDescricao[] = $categoria['procat_descricao'];
            $quantidadesProdutosCategoria[] = $quantidade;
        }
    
       
        $unidades = $produtoUnidadeModel->findAll();
        $unidadesDescricao = [];
        $quantidadesProdutosUnidade = [];
        foreach ($unidades as $unidade) {
            $produtoModel->resetQuery();
            $quantidade = $produtoModel->where('proum_id', $unidade['proum_id'])->countAllResults();
            $unidadesDescricao[] = $unidade['proum_descricao'];
            $quantidadesProdutosUnidade[] = $quantidade;
        }
    
        
        $ultimasMovimentacoes = $movimentoModel
            ->select('movimento.mov_data, movimentotipo.mov_descricao as tipo, produto.pro_descricao as produto, movimento.mov_quantidade as quantidade')
            ->join('movimentotipo', 'movimentotipo.movt_id = movimento.movt_id')
            ->join('produto', 'produto.pro_id = movimento.pro_id')
            ->orderBy('movimento.mov_data', 'DESC')
            ->limit(6)
            ->findAll();

        $maioresEstoques = $armazemModel
            ->select('produto.pro_descricao as produto, armazem.arm_quantidade as estoque')
            ->join('produto', 'produto.pro_id = armazem.pro_id')
            ->orderBy('armazem.arm_quantidade', 'DESC')
            ->limit(6)
            ->findAll();

        $dadosDashboard = [
            'pessoasAtivas' => $pessoaModel->where('pes_ativo', 1)->countAllResults(),
            'pessoasInativas' => $pessoaModel->where('pes_ativo', 0)->countAllResults(),
            'produtosAtivos' => $produtoModel->where('pro_ativo', 1)->countAllResults(),
            'produtosInativos' => $produtoModel->where('pro_ativo', 0)->countAllResults(),
    
            'tiposPessoas' => $tiposDescricao,
            'quantidadesTipos' => $quantidadesPessoasTipo,
    
            'categoriasProdutos' => $categoriasDescricao,
            'quantidadesCategorias' => $quantidadesProdutosCategoria,
    
            'unidadesProdutos' => $unidadesDescricao,
            'quantidadesUnidades' => $quantidadesProdutosUnidade,
            

            'ultimasMovimentacoes' => $ultimasMovimentacoes,
            'maioresEstoques' => $maioresEstoques,
        ];

        return view('home', $dadosDashboard);
    }

    public function gerarRelatorio()
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    $tipo = $this->request->getGet('tipo');
    $dataInicio = $this->request->getGet('data_inicio');
    $dataFim = $this->request->getGet('data_fim');

    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h2 { text-align: center; color: #2c3e50; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
            th { background-color: #f8f8f8; color: #333; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #aaa; }
        </style>
    </head>
    <body>';

    switch ($tipo) {
        case 'pessoas':
            $pessoaTipoModel = new \App\Models\PessoaTipoModel();
            $pessoaModel = new \App\Models\PessoaModel();
            $tipos = $pessoaTipoModel->findAll();
            $html .= '<h2>Relatório: Pessoas por Tipo</h2>';
            $html .= '<table><tr><th>Tipo</th><th>Quantidade</th></tr>';
            foreach ($tipos as $tipo) {
                $qtd = $pessoaModel->where('pest_id', $tipo['pest_id'])->countAllResults();
                $html .= "<tr><td>{$tipo['pest_descricao']}</td><td>{$qtd}</td></tr>";
            }
            $html .= '</table>';
            break;

        case 'produtos_categoria':
            $categoriaModel = new \App\Models\ProdutoCategoriaModel();
            $produtoModel = new \App\Models\ProdutoModel();
            $categorias = $categoriaModel->findAll();
            $html .= '<h2>Relatório: Produtos por Categoria</h2>';
            $html .= '<table><tr><th>Categoria</th><th>Quantidade</th></tr>';
            foreach ($categorias as $cat) {
                $qtd = $produtoModel->where('procat_id', $cat['procat_id'])->countAllResults();
                $html .= "<tr><td>{$cat['procat_descricao']}</td><td>{$qtd}</td></tr>";
            }
            $html .= '</table>';
            break;

        case 'produtos_unidade':
            $unidadeModel = new \App\Models\ProdutoUnidadeMedidaModel();
            $produtoModel = new \App\Models\ProdutoModel();
            $unidades = $unidadeModel->findAll();
            $html .= '<h2>Relatório: Produtos por Unidade de Medida</h2>';
            $html .= '<table><tr><th>Unidade</th><th>Quantidade</th></tr>';
            foreach ($unidades as $un) {
                $qtd = $produtoModel->where('proum_id', $un['proum_id'])->countAllResults();
                $html .= "<tr><td>{$un['proum_descricao']}</td><td>{$qtd}</td></tr>";
            }
            $html .= '</table>';
            break;

        case 'maiores_estoques':
            $armazemModel = new \App\Models\ArmazemModel();
            $produtoModel = new \App\Models\ProdutoModel();
            $estoques = $armazemModel
                ->select('produto.pro_descricao as produto, armazem.arm_quantidade as estoque')
                ->join('produto', 'produto.pro_id = armazem.pro_id')
                ->orderBy('armazem.arm_quantidade', 'DESC')
                ->limit(6)
                ->findAll();
            $html .= '<h2>Relatório: Maiores Estoques</h2>';
            $html .= '<table><tr><th>Produto</th><th>Estoque</th></tr>';
            foreach ($estoques as $e) {
                $html .= "<tr><td>{$e['produto']}</td><td>{$e['estoque']}</td></tr>";
            }
            $html .= '</table>';
            break;

        case 'ultimas_movimentacoes':
            $movimentoModel = new \App\Models\MovimentoModel();
            $movimentoTipoModel = new \App\Models\MovimentoTipoModel();
            $produtoModel = new \App\Models\ProdutoModel();
            $movimentos = $movimentoModel
                ->select('movimento.mov_data, movimentotipo.mov_descricao as tipo, produto.pro_descricao as produto, movimento.mov_quantidade as quantidade')
                ->join('movimentotipo', 'movimentotipo.movt_id = movimento.movt_id')
                ->join('produto', 'produto.pro_id = movimento.pro_id')
                ->orderBy('movimento.mov_data', 'DESC')
                ->limit(6)
                ->findAll();
            $html .= '<h2>Relatório: Últimas Movimentações</h2>';
            $html .= '<table><tr><th>Data</th><th>Tipo</th><th>Produto</th><th>Quantidade</th></tr>';
            foreach ($movimentos as $m) {
                $dataFormatada = date('d/m/Y', strtotime($m['mov_data']));
                $html .= "<tr><td>{$dataFormatada}</td><td>{$m['tipo']}</td><td>{$m['produto']}</td><td>{$m['quantidade']}</td></tr>";
            }
            $html .= '</table>';
            break;

        default:
            $html .= '<p style="color: red; text-align: center;">Tipo de relatório inválido.</p>';
    }

    $html .= '<div class="footer">Relatório gerado em ' . date('d/m/Y H:i') . '</div>';
    $html .= '</body></html>';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    return $this->response->setHeader('Content-Type', 'application/pdf')
        ->setHeader('Content-Disposition', 'attachment; filename="relatorio.pdf"')
        ->setBody($mpdf->Output('', 'S'));
}
}