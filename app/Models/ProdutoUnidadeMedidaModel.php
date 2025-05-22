<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoUnidadeMedidaModel extends Model
{
    protected $table = 'produtounidademedida';
    protected $primaryKey = 'proum_id';
    protected $allowedFields = ['proum_descricao', 'proum_observacao'];
    protected $returnType = 'array';
}
