<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvRespAdjModel extends Model
{
   protected $table      = 'auv_resp_adj';
   protected $primaryKey = 'coditem';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['salon', 'coditem', 'codalu', 'orden', 'nombre', 'tamanio', 'ruta', 'fecreg'];

   protected $useTimestamps = false;
   protected $dateFormat    = 'datetime';

   public function listarRespuestasAdj(array $params)
   {
      $query = $this->db->table('auv_resp_adj ard')->select();
      $query->where('ard.coditem', $params['coditem']);
      $query->where('ard.codalu', $params['codalu']);
      $query->orderBy('ard.orden');
      $result = $query->get();
      return $result->getResultArray();
   }
}
