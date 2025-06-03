<?php

namespace App\Models;

use CodeIgniter\Model;

class ArmazemModel extends Model
{
    protected $table = 'armazem';
    protected $primaryKey = 'arm_id';
    protected $allowedFields = ['pro_id', 'arm_quantidade', 'arm_datacadastro', 'arm_observacao', 'arm_ativo', 'arm_valor'];
    protected $returnType = 'array';
}
