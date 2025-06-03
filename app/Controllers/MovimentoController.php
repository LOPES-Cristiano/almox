<?php
namespace App\Controllers;

use App\Models\MovimentoModel;
use App\Models\MovimentoTipoModel;
use App\Models\ProdutoModel;
use App\Models\UsuarioModel;
use App\Models\PessoaModel;
use App\Models\ArmazemModel;
use CodeIgniter\Controller;

class MovimentoController extends BaseController
{
    public function index($tipo = null)
    {
        if ($tipo === null) {
            $tipo = 1;
        }
        $movimentoModel = new MovimentoModel();
        $movimentoTipoModel = new MovimentoTipoModel();
        $produtoModel = new ProdutoModel();
        $usuarioModel = new UsuarioModel();
        $pessoaModel = new PessoaModel();
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

    public function salvar()
    {
        $movimentoModel = new MovimentoModel();
        $armazemModel = new ArmazemModel();
        $usuarioModel = new UsuarioModel();
        $movt_id = $this->request->getPost('movt_id');
        $usuario_id = session('usuario_id');
        $usuario = $usuarioModel->find($usuario_id);
        $pessoa_id = $usuario['pes_id'] ?? null;
        $mov_fornecedor = $this->request->getPost('mov_fornecedor');
        $mov_cliente = $this->request->getPost('mov_cliente');
        if ($movt_id == 1 && empty($mov_fornecedor)) {
            return redirect()->back()->with('error', 'Fornecedor é obrigatório para entrada.');
        }
        if ($movt_id == 2 && empty($mov_cliente)) {
            return redirect()->back()->with('error', 'Cliente é obrigatório para saída.');
        }
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
        if ($movt_id == 1) { 
            $data['mov_fornecedor'] = $mov_fornecedor;
            $data['mov_cliente'] = $pessoa_id;
        } elseif ($movt_id == 2) { 
            $data['mov_cliente'] = $mov_cliente;
            $data['mov_fornecedor'] = $pessoa_id;
        }
        $movimentoModel->insert($data);
        $quantidade = (float) $this->request->getPost('quantidade');
        $produtoId = $this->request->getPost('pro_id');
        $armazem = $armazemModel->where('pro_id', $produtoId)->first();
        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->find($produtoId);
        $valorUnitario = isset($produto['pro_valor']) ? (float)$produto['pro_valor'] : 0;
        if ($armazem) {
            $novoSaldo = $armazem['arm_quantidade'];
            if ($movt_id == 1) {
                $novoSaldo += $quantidade;
            } elseif ($movt_id == 2) {
                $novoSaldo -= $quantidade;
                if ($novoSaldo < 0) $novoSaldo = 0;
            }
            $novoValor = $novoSaldo * $valorUnitario;
            $armazemModel->update($armazem['arm_id'], ['arm_quantidade' => $novoSaldo, 'arm_valor' => $novoValor]);
        } else {
            $novoSaldo = ($movt_id == 1) ? $quantidade : 0;
            $novoValor = $novoSaldo * $valorUnitario;
            $armazemModel->insert([
                'pro_id' => $produtoId,
                'arm_quantidade' => $novoSaldo,
                'arm_valor' => $novoValor
            ]);
        }
        return redirect()->to('/movimento/' . $movt_id);
    }

    public function salvarTipoMovimento()
    {
        $movimentoTipoModel = new MovimentoTipoModel();
        $data = [
            'mov_descricao' => $this->request->getPost('mov_descricao'),
        ];
        if ($movimentoTipoModel->insert($data)) {
            return redirect()->to('/movimento')->with('success', 'Tipo de movimento cadastrado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao cadastrar tipo de movimento.');
        }
    }
}
