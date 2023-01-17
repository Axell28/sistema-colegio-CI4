<?php

namespace App\Models;

use CodeIgniter\Model;

class FamiliaDetModel extends Model
{
   protected $table      = 'familia_det';
   protected $primaryKey = 'codigo';

   protected $useAutoIncrement = true;

   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codigo', 'codfam', 'codper', 'tipofam', 'responsable'];
   protected $useTimestamps = false;

   public function guardarDatosFamilia(array $params, $action = 'I')
   {
      try {
         $this->db->transBegin();

         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }
}
