<?php

namespace App\Controllers;

use App\Models\PessoaModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class HomeController extends BaseController
{
    public function index()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }
        return view('home');
    }

    public function usuarios()
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    $pessoaModel = new \App\Models\PessoaModel();

    $pessoas = $pessoaModel
        ->select('pessoatipo.pest_descricao, pessoa.pes_id, pessoa.pes_nome, pessoa.pes_datacadastro, pessoa.pes_observacao, pessoa.pes_ativo, usuario.usu_email')
        ->join('usuario', 'usuario.pes_id = pessoa.pes_id')
        ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id', 'left') 
        ->findAll();

    return view('usuarios', ['pessoas' => $pessoas]);
}

    public function salvarUsuario()
    {
        try {
        
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $observacao = $this->request->getPost('observacao');

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
        echo "<pre>";
        echo "Erro capturado: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString();
        echo "</pre>";
        exit;
    }

    }

    public function editarUsuario($pes_id)
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    $pessoaModel = new \App\Models\PessoaModel();
    $usuarioModel = new \App\Models\UsuarioModel();

    $pessoa = $pessoaModel->find($pes_id);
    if (!$pessoa) {
        return redirect()->to('home/usuarios')->with('erro', 'Pessoa não encontrada');
    }

    $usuario = $usuarioModel->where('pes_id', $pes_id)->first();

    return view('editar_usuario', [
        'pessoa' => $pessoa,
        'usuario' => $usuario,
    ]);
}

public function atualizarUsuario($pes_id)
{
    if (!session()->has('usuario_id')) {
        return redirect()->to('/');
    }

    $nome = $this->request->getPost('nome');
    $email = $this->request->getPost('email');
    $senha = $this->request->getPost('senha');
    $observacao = $this->request->getPost('observacao');

    $pessoaModel = new \App\Models\PessoaModel();
    $usuarioModel = new \App\Models\UsuarioModel();

    
    $pessoaData = [
        'pes_nome' => $nome,
        'pes_observacao' => $observacao ?? '',
       
    ];

    $pessoaModel->update($pes_id, $pessoaData);

    $usuario = $usuarioModel->where('pes_id', $pes_id)->first();

    $usuarioData = [
        'usu_email' => $email,
    ];

    if (!empty($senha)) {
        if (strlen($senha) < 8) {
            return redirect()->back()->with('erro', 'A senha deve ter no mínimo 8 caracteres')->withInput();
        }
        $usuarioData['usu_senha'] = password_hash($senha, PASSWORD_DEFAULT);
    }

    $usuarioModel->update($usuario['usu_id'], $usuarioData);

    return redirect()->to('home/usuarios')->with('sucesso', 'Usuário atualizado com sucesso!');
}

    public function produtos()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        return view('produtos');
    }

    public function fornecedores()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        return view('fornecedores');
    }

    public function entrada()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        return view('entrada');
    }

    public function saida()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        return view('saida');
    }

    public function relatorios()
    {
        if (!session()->has('usuario_id')) {
            return redirect()->to('/');
        }

        return view('relatorios');
    }
}
