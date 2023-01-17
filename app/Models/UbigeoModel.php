<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class UbigeoModel extends Model
{
   protected $table      = 'ubigeo';
   protected $primaryKey = 'codubg';
   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;
   protected $allowedFields = ['codubg', 'nombre'];

   public function listarDepartamentos()
   {
      $resp = $this->select('codubg AS codigo, nombre')->where(new RawSql("LENGTH(TRIM(codubg)) = 2"))->orderBy('nombre ASC')->findAll();
      return $resp;
   }

   public function listarProvincias()
   {
      $query = $this->select(array(
         'codubg',
         'nombre',
         new RawSql("TRIM(SUBSTR(codubg, 1, 2)) AS dept")
      ))->where(new RawSql("LENGTH(TRIM(codubg)) = 4"))->orderBy('nombre ASC')->findAll();
      $resp = array();
      foreach ($query as $val) {
         $resp[$val['dept']][] = array('codigo' => $val['codubg'], 'nombre' => $val['nombre']);
      }
      return $resp;
   }

   public function listarDistritos()
   {
      $query = $this->select(array(
         'codubg',
         'nombre',
         new RawSql("TRIM(SUBSTR(codubg, 1, 4)) AS prov"),
         new RawSql("TRIM(SUBSTR(codubg, 1, 2)) AS dept")
      ))->where(new RawSql("LENGTH(TRIM(codubg)) = 6"))->orderBy('nombre ASC')->findAll();
      $resp = array();
      foreach ($query as $val) {
         $resp[$val['dept']][$val['prov']][] = array('codigo' => $val['codubg'], 'nombre' => $val['nombre']);
      }
      return $resp;
   }
}
