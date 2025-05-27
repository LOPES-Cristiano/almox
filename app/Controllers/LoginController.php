<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\PessoaModel;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('usu_email', $email)->first();

        if (!$usuario) {
            return redirect()->back()->with('erro', 'Email ou Senha incorreta.')->withInput();
        }

        if (!password_verify($senha, $usuario['usu_senha'])) {
            return redirect()->back()->with('erro', 'Email ou Senha incorreta.')->withInput();
        }

        $pessoaModel = new PessoaModel();
        $pessoa = $pessoaModel->where('pes_id', $usuario['pes_id'])->first();

        session()->set('usuario_id', $usuario['usu_id']);
        
        session()->set('usuario_tipo_descricao', $pessoa['pes_tipo_descricao'] ?? '');

        return redirect()->to('/home');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/')->with('sucesso', 'Logout efetuado com sucesso!');
    }
}
