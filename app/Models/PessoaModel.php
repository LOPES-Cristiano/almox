<?php

namespace App\Models;

use CodeIgniter\Model;

class PessoaModel extends Model
{
    protected $table = 'pessoa';
    protected $primaryKey = 'pes_id';
    protected $allowedFields = ['pes_nome', 'pes_datacadastro', 'pes_observacao', 'pes_ativo','pest_id'];

    public function countAtivos() {
        return $this->where('pro_ativo', 1)->countAllResults();
    }
    
    public function countInativos() {
        return $this->where('pro_ativo', 0)->countAllResults();
    }

    public function getPessoaComTipo($id)
{
    return $this->select('pessoa.*, pessoatipo.pest_descricao')
                ->join('pessoatipo', 'pessoatipo.pest_id = pessoa.pest_id')
                ->where('pessoa.pes_id', $id)
                ->first();
}
}
?>
