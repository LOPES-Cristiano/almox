<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentoTipoModel extends Model
{
    protected $table = 'movimentotipo';
    protected $primaryKey = 'movt_id';
    protected $allowedFields = ['mov_descricao', 'mov_observacao'];
    protected $returnType = 'array';
}
