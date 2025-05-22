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
        $usuario = $usuarioModel
            ->where('usu_email', $email)
            ->where('usu_senha', $senha)
            ->first();

        if ($usuario) {
            $pessoaModel = new PessoaModel();
            $pessoa = $pessoaModel
                ->where('pes_id', $usuario['pes_id'])   
                ->first();

            session()->set('usuario_id', $usuario['usu_id']);
            session()->set('usuario_email', $usuario['usu_email']);
            session()->set('usuario_nome', $pessoa ? $pessoa['pes_nome'] : 'Sem nome');
            session()->set('usuario_pes_id', $usuario['pes_id']);

            return redirect()->to('/home');
        } else {
            return redirect()->back()->with('erro', 'Email ou senha inválidos');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
