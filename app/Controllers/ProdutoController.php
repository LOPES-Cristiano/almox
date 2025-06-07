<?php
namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\ProdutoCategoriaModel;
use App\Models\ProdutoUnidadeMedidaModel;
use App\Models\ArmazemModel;
use CodeIgniter\Controller;

class ProdutoController extends BaseController
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
        $builder = $this->produtoModel->builder();
        $builder->select('produto.*, produtocategoria.procat_descricao, produtounidademedida.proum_descricao, IFNULL(armazem.arm_quantidade,0) as saldo_estoque, IFNULL(armazem.arm_valor,0) as valor_estoque');
        $builder->join('produtocategoria', 'produto.procat_id = produtocategoria.procat_id');
        $builder->join('produtounidademedida', 'produto.proum_id = produtounidademedida.proum_id');
        $builder->join('armazem', 'armazem.pro_id = produto.pro_id', 'left');
        $builder->orderBy('produto.pro_id', 'ASC');
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
                'pro_valor' => $data['valor'],
                'pro_observacao' => $data['observacao'] ?? null,
                'pro_ativo' => isset($data['ativo']) ? 1 : 0,
            ];
            $this->produtoModel->insert($novoProduto);
            return redirect()->to(base_url('produto'))->with('sucesso', 'Produto cadastrado com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao cadastrar produto: ' . $e->getMessage())->withInput();
        }
    }

    public function atualizarProduto($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('produto'))->with('erro', 'Produto inválido.');
        }
        try {
            $data = $this->request->getPost();
            $produtoAtualizado = [
                'pro_descricao' => $data['descricao'],
                'procat_id' => $data['categoria'],
                'proum_id' => $data['unidademedida'],
                'pro_valor' => $data['valor'],
                'pro_observacao' => $data['observacao'] ?? null,
                'pro_ativo' => isset($data['ativo']) ? 1 : 0,
            ];
            $this->produtoModel->update($id, $produtoAtualizado);
            return redirect()->to(base_url('produto'))->with('sucesso', 'Produto atualizado com sucesso!');
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
            return redirect()->to(base_url('produto'))->with('sucesso', 'Categoria inserida com sucesso!');
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
            return redirect()->to(base_url('produto'))->with('sucesso', 'Unidade de medida inserida com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao inserir unidade de medida: ' . $e->getMessage())->withInput();
        }
    }
}
