<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class MatriculaModel extends Model
{
   protected $table      = 'matricula';
   protected $primaryKey = 'codmat';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = [];
   protected $useTimestamps = true;
}
