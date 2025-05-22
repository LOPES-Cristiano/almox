<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoCategoriaModel extends Model
{
    protected $table = 'produtocategoria';
    protected $primaryKey = 'procat_id';
    protected $allowedFields = ['procat_descricao', 'procat_observacao'];
    protected $returnType = 'array';
}
