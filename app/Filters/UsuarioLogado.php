<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UsuarioModel;
use App\Models\PessoaModel;
use App\Models\PessoaTipoModel;

class UsuarioLogado implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

      
        if (!$session->has('usuario_id')) {
            return redirect()->to('/');
        }

        $usuarioModel = new UsuarioModel();
        $pessoaModel = new PessoaModel();
        $pessoaTipoModel = new PessoaTipoModel();

        $usuario = $usuarioModel->find($session->get('usuario_id'));
        $pessoa = $pessoaModel->find($usuario['pes_id']);
        $tipoDescricao = '';
        if ($pessoa && isset($pessoa['pest_id'])) {
            $tipo = $pessoaTipoModel->find($pessoa['pest_id']);
            $tipoDescricao = $tipo['pest_descricao'] ?? '';
        }

        
        \Config\Services::renderer()->setVar('usuario_nome', $pessoa['pes_nome'] ?? '');
        \Config\Services::renderer()->setVar('usuario_tipo_descricao', $tipoDescricao);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
       
    }
}
