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

    public function usuarios()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        $pessoaModel = new PessoaModel();

        $pessoas = $pessoaModel
            ->select('pessoatipo.pest_id, pessoatipo.pest_descricao, pessoa.pes_id, pessoa.pes_nome, 
                      pessoa.pes_datacadastro, pessoa.pes_observacao, pessoa.pes_ativo, usuario.usu_email')
            ->join('usuario', 'usuario.pes_id = pessoa.pes_id')
            ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id', 'left')
            ->findAll();

        $pessoaTipoModel = new \App\Models\PessoaTipoModel();
        $tipos = $pessoaTipoModel->findAll();

        return view('usuarios', [
            'pessoas' => $pessoas,
            'tipos' => $tipos
        ]);
    }

    public function salvarUsuario()
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    try {
        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $observacao = $this->request->getPost('observacao');
        $pest_id = $this->request->getPost('tipo');

        if (strlen($senha) < 8) {
            return redirect()->back()->with('erro', 'A senha deve ter no mínimo 8 caracteres')->withInput();
        }

        $pessoaModel = new PessoaModel();
        $usuarioModel = new UsuarioModel();

        $pessoaData = [
            'pes_nome' => $nome,
            'pes_datacadastro' => date('Y-m-d'),
            'pes_observacao' => $observacao ?? '',
            'pes_ativo' => 1,
            'pest_id' => $pest_id
        ];

        $pessoaModel->insert($pessoaData);
        $pes_id = $pessoaModel->getInsertID();

        $usuarioData = [
            'usu_email' => $email,
            'usu_senha' => password_hash($senha, PASSWORD_DEFAULT),
            'usu_cadastro' => date('Y-m-d'),
            'usu_ativo' => 1,
            'usu_observacao' => '',
            'pes_id' => $pes_id,
        ];

        $usuarioModel->insert($usuarioData);

        return redirect()->to('home/usuarios')->with('sucesso', 'Usuário cadastrado com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao salvar usuário: ' . $e->getMessage())->withInput();
    }
}
    
public function atualizarUsuario($pes_id)
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    try {
        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $ativo = $this->request->getPost('ativo') ? 1 : 0;
        $pest_id = $this->request->getPost('tipo');
        $observacao = $this->request->getPost('observacao');

        if (!empty($senha) && strlen($senha) < 8) {
            return redirect()->back()->with('erro', 'A senha deve ter no mínimo 8 caracteres')->withInput();
        }

        $pessoaModel = new PessoaModel();
        $usuarioModel = new UsuarioModel();

        $pessoaData = [
            'pes_nome' => $nome,
            'pes_observacao' => $observacao ?? '',
            'pest_id' => $pest_id,
            'pes_ativo' => $ativo
        ];

        $pessoaModel->update($pes_id, $pessoaData);

        $usuario = $usuarioModel->where('pes_id', $pes_id)->first();

        $usuarioData = [
            'usu_email' => $email,
            'usu_ativo' => $ativo
        ];

        if (!empty($senha)) {
            $usuarioData['usu_senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        $usuarioModel->update($usuario['usu_id'], $usuarioData);

        return redirect()->to('home/usuarios')->with('sucesso', 'Usuário atualizado com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao atualizar usuário: ' . $e->getMessage())->withInput();
    }
}

    public function produtos()
    {
        $builder = $this->produtoModel->builder();
        $builder->select('produto.*, produtocategoria.procat_descricao, produtounidademedida.proum_descricao, IFNULL(armazem.arm_quantidade,0) as saldo_estoque');
        $builder->join('produtocategoria', 'produto.procat_id = produtocategoria.procat_id');
        $builder->join('produtounidademedida', 'produto.proum_id = produtounidademedida.proum_id');
        $builder->join('armazem', 'armazem.pro_id = produto.pro_id', 'left');
        $produtos = $builder->get()->getResultArray();

        $categorias = $this->categoriaModel->findAll();
        $unidades = $this->unidadeMedidaModel->findAll();

        $data = [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'unidades' => $unidades,
        ];

        return view('produtos', $data);
    }

    public function inserirProduto()
{
    try {
        $data = $this->request->getPost();

        $novoProduto = [
            'pro_descricao' => $data['descricao'],
            'pro_datacadastro'  => date('Y-m-d'),
            'procat_id' => $data['categoria'],
            'proum_id' => $data['unidademedida'],
            'pro_observacao' => $data['observacao'] ?? null,
            'pro_ativo' => isset($data['ativo']) ? 1 : 0,
        ];

        $this->produtoModel->insert($novoProduto);

        return redirect()->to(base_url('home/produtos'))->with('sucesso', 'Produto cadastrado com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao cadastrar produto: ' . $e->getMessage())->withInput();
    }
}

public function atualizarProduto($id = null)
{
    if (!$id) {
        return redirect()->to(base_url('home/produtos'))->with('erro', 'Produto inválido.');
    }

    try {
        $data = $this->request->getPost();

        $produtoAtualizado = [
            'pro_descricao' => $data['descricao'],
            'procat_id' => $data['categoria'],
            'proum_id' => $data['unidademedida'],
            'pro_observacao' => $data['observacao'] ?? null,
            'pro_ativo' => isset($data['ativo']) ? 1 : 0,
        ];

        $this->produtoModel->update($id, $produtoAtualizado);

        return redirect()->to(base_url('home/produtos'))->with('sucesso', 'Produto atualizado com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao atualizar produto: ' . $e->getMessage())->withInput();
    }
}

public function inserirCategoria()
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    try {
        $descricao = $this->request->getPost('descricao');
        $observacao = $this->request->getPost('observacao');

        $categoriaData = [
            'procat_descricao' => $descricao,
            'procat_observacao' => $observacao ?? ''
        ];

        $this->categoriaModel->insert($categoriaData);

        return redirect()->to(base_url('home/produtos'))->with('sucesso', 'Categoria inserida com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao inserir categoria: ' . $e->getMessage())->withInput();
    }
}

public function inserirUnidadeMedida()
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    try {
        $descricao = $this->request->getPost('descricao');
        $observacao = $this->request->getPost('observacao');

        $unidadeData = [
            'proum_descricao' => $descricao,
            'proum_observacao' => $observacao ?? ''
        ];

        $this->unidadeMedidaModel->insert($unidadeData);

        return redirect()->to(base_url('home/produtos'))->with('sucesso', 'Unidade de medida inserida com sucesso!');
    } catch (\Throwable $e) {
        return redirect()->back()->with('erro', 'Erro ao inserir unidade de medida: ' . $e->getMessage())->withInput();
    }
}

public function movimentos($tipo = null)
{
    if ($tipo === null) {
        $tipo = 1;
    }

    $movimentoModel = new \App\Models\MovimentoModel();
    $movimentoTipoModel = new \App\Models\MovimentoTipoModel();
    $produtoModel = new \App\Models\ProdutoModel();
    $usuarioModel = new \App\Models\UsuarioModel();
    $pessoaModel = new \App\Models\PessoaModel();

    $fornecedores = $pessoaModel
        ->select('pessoa.*')
        ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id')
        ->where('pest_descricao', 'fornecedor')
        ->findAll();

    $clientes = $pessoaModel
        ->select('pessoa.*')
        ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id')
        ->where('pest_descricao', 'cliente')
        ->findAll();

    $tipos = $movimentoTipoModel->findAll();

    // Agora fazendo joins para pegar nomes do fornecedor e cliente
    $movimentos = $movimentoModel
        ->select('
            movimento.*, 
            movimentotipo.mov_descricao, 
            produto.pro_descricao, 
            pessoa_usuario.pes_nome AS usuario_nome,
            pessoa_fornecedor.pes_nome AS fornecedor_nome,
            pessoa_cliente.pes_nome AS cliente_nome
        ')
        ->join('movimentotipo', 'movimentotipo.movt_id = movimento.movt_id')
        ->join('produto', 'produto.pro_id = movimento.pro_id')
        ->join('usuario', 'usuario.usu_id = movimento.usu_id')
        ->join('pessoa AS pessoa_usuario', 'pessoa_usuario.pes_id = usuario.pes_id')
        ->join('pessoa AS pessoa_fornecedor', 'pessoa_fornecedor.pes_id = movimento.mov_fornecedor', 'left')
        ->join('pessoa AS pessoa_cliente', 'pessoa_cliente.pes_id = movimento.mov_cliente', 'left')
        ->where('movimento.movt_id', $tipo)
        ->orderBy('mov_data', 'DESC')
        ->findAll();

    return view('movimentos/index', [
        'movimentos' => $movimentos,
        'tipos' => $tipos,
        'tipoSelecionado' => $tipo,
        'fornecedores' => $fornecedores,
        'clientes' => $clientes
    ]);
}

public function salvarMovimento()
{
    $movimentoModel = new \App\Models\MovimentoModel();
    $armazemModel = new \App\Models\ArmazemModel();
    $usuarioModel = new \App\Models\UsuarioModel();

    $movt_id = $this->request->getPost('movt_id');
    $usuario_id = session('usuario_id');

    $usuario = $usuarioModel->find($usuario_id);
    $pessoa_id = $usuario['pes_id'] ?? null;

    $mov_fornecedor = $this->request->getPost('mov_fornecedor');
    $mov_cliente = $this->request->getPost('mov_cliente');

    // Validação mínima:
    if ($movt_id == 1 && empty($mov_fornecedor)) {
        return redirect()->back()->with('error', 'Fornecedor é obrigatório para entrada.');
    }

    if ($movt_id == 2 && empty($mov_cliente)) {
        return redirect()->back()->with('error', 'Cliente é obrigatório para saída.');
    }

    // Define o timezone antes de montar o array de dados
    date_default_timezone_set('America/Sao_Paulo');
    $data = [
        'mov_data' => date('Y-m-d'),
        'movt_id' => $movt_id,
        'pro_id' => $this->request->getPost('pro_id'),
        'usu_id' => $usuario_id,
        'mov_observacao' => $this->request->getPost('mov_observacao'),
        'mov_fornecedor' => null,
        'mov_cliente' => null,
        'mov_quantidade' => (float) $this->request->getPost('quantidade')
    ];

    if ($movt_id == 1) { // Entrada
        $data['mov_fornecedor'] = $mov_fornecedor;
        $data['mov_cliente'] = $pessoa_id;
    } elseif ($movt_id == 2) { // Saída
        $data['mov_cliente'] = $mov_cliente;
        $data['mov_fornecedor'] = $pessoa_id;
    }

    log_message('debug', 'Dados para inserir movimento: ' . json_encode($data));

    $movimentoModel->insert($data);

    // Atualiza estoque
    $quantidade = (int) $this->request->getPost('quantidade');
    $produtoId = $this->request->getPost('pro_id');
    $armazem = $armazemModel->where('pro_id', $produtoId)->first();

    if ($armazem) {
        $novoSaldo = $armazem['arm_quantidade'];

        if ($movt_id == 1) {
            $novoSaldo += $quantidade;
        } elseif ($movt_id == 2) {
            $novoSaldo -= $quantidade;
            if ($novoSaldo < 0) $novoSaldo = 0;
        }

        $armazemModel->update($armazem['arm_id'], ['arm_quantidade' => $novoSaldo]);
    } else {
        $novoSaldo = ($movt_id == 1) ? $quantidade : 0;
        $armazemModel->insert([
            'pro_id' => $produtoId,
            'arm_quantidade' => $novoSaldo
        ]);
    }

    return redirect()->to('/home/movimentos/' . $movt_id);
}

public function salvarTipoMovimento()
{
    $movimentoTipoModel = new \App\Models\MovimentoTipoModel();

    $data = [
        'mov_descricao' => $this->request->getPost('mov_descricao'),
    ];

    if ($movimentoTipoModel->insert($data)) {
        return redirect()->to('/home/movimentos')->with('success', 'Tipo de movimento cadastrado com sucesso!');
    } else {
        return redirect()->back()->with('error', 'Erro ao cadastrar tipo de movimento.');
    }
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


    public function inserirTipoPessoa()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }
        $descricao = $this->request->getPost('descricao');
        if (!$descricao || trim($descricao) === '') {
            return redirect()->back()->with('erro', 'Descrição obrigatória.');
        }
        $pessoaTipoModel = new \App\Models\PessoaTipoModel();
        try {
            $pessoaTipoModel->insert([
                'pest_descricao' => $descricao
            ]);
            return redirect()->to('home/usuarios')->with('sucesso', 'Tipo de pessoa inserido com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao inserir tipo: ' . $e->getMessage());
        }
    }
}
