<?php
namespace App\Controllers;

use App\Models\PessoaModel;
use App\Models\PessoaTipoModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class PessoaController extends BaseController
{
    public function index()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }
        $pessoaModel = new PessoaModel();
        $pessoas = $pessoaModel
            ->select('pessoatipo.pest_id, pessoatipo.pest_descricao, pessoa.pes_id, pessoa.pes_nome, pessoa.pes_datacadastro, pessoa.pes_observacao, pessoa.pes_ativo, usuario.usu_email')
            ->join('usuario', 'usuario.pes_id = pessoa.pes_id')
            ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id', 'left')
            ->findAll();
        $pessoaTipoModel = new PessoaTipoModel();
        $tipos = $pessoaTipoModel->findAll();
        return view('usuarios', [
            'pessoas' => $pessoas,
            'tipos' => $tipos
        ]);
    }

    public function salvar()
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
            return redirect()->to('pessoa')->with('sucesso', 'Usuário cadastrado com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao salvar usuário: ' . $e->getMessage())->withInput();
        }
    }

    public function atualizar($pes_id)
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
            return redirect()->to('pessoa')->with('sucesso', 'Usuário atualizado com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao atualizar usuário: ' . $e->getMessage())->withInput();
        }
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
        $pessoaTipoModel = new PessoaTipoModel();
        try {
            $pessoaTipoModel->insert([
                'pest_descricao' => $descricao
            ]);
            return redirect()->to('pessoa')->with('sucesso', 'Tipo de pessoa inserido com sucesso!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('erro', 'Erro ao inserir tipo: ' . $e->getMessage());
        }
    }
}
