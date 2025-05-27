<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'pro_id';
    protected $allowedFields = ['pro_descricao', 'pro_datacadastro', 'pro_observacao', 'pro_ativo', 'procat_id', 'proum_id'];
    protected $returnType = 'array';

    public function countAtivos() {
        return $this->where('pes_ativo', 1)->countAllResults();
    }
    
    public function countInativos() {
        return $this->where('pes_ativo', 0)->countAllResults();
    }
    
}
