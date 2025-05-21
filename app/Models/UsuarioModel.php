<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'usu_id';
    protected $allowedFields = ['usu_email', 'usu_senha', 'usu_cadastro', 'usu_ativo', 'usu_observacao', 'pes_id'];
}
