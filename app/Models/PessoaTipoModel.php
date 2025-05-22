<?php

namespace App\Models;

use CodeIgniter\Model;

class PessoaTipoModel extends Model
{
    protected $table = 'pessoatipo';
    protected $primaryKey = 'pest_id';
    protected $allowedFields = ['pest_descricao'];
}
