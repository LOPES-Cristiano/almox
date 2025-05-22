<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'pro_id';
    protected $allowedFields = ['pro_descricao', 'pro_datacadastro', 'pro_observacao', 'pro_ativo', 'procat_id', 'proum_id'];
    protected $returnType = 'array';
}
