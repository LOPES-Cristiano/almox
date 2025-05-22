<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentoModel extends Model
{
    protected $table = 'movimento';
    protected $primaryKey = 'mov_id';
    protected $allowedFields = ['mov_data', 'movt_id', 'pro_id', 'usu_id', 'mov_observacao'];
    protected $returnType = 'array';
}
