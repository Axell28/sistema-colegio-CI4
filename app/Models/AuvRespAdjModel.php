<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvRespAdjModel extends Model
{
   protected $table      = 'auv_resp_adj';
   protected $primaryKey = 'cgitem';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['cgitem', 'codalu', 'orden', 'nota', 'comentalu', 'comentdoc', 'nombre', 'tamanio', 'fecenv', 'ruta', 'revisado'];

   protected $useTimestamps = false;
   protected $dateFormat    = 'datetime';

   public function listarRespuestasAdj(array $params)
   {
      $query = $this->db->table('auv_resp_adj ard')->select();
      $query->where('ard.cgitem', $params['coditem']);
      if (isset($params['codalu'])) {
         $query->where('ard.codalu', $params['codalu']);
      }
      $result = $query->get();
      return $result->getResultArray();
   }
}
