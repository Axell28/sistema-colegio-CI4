<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AnioModel extends Model
{
   protected $table      = 'anio';
   protected $primaryKey = 'anio';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['anio', 'nombre', 'fecini', 'fecfin', 'vigente', 'matricula'];
   protected $useTimestamps = false;

   public function getAnioVigente()
   {
      $resp = $this->select('anio')->where('vigente', 'S')->first();
      return isset($resp['anio']) ? $resp['anio'] : null;
   }

   public function getAnioMatricula()
   {
      $resp = $this->select('anio')->where('matricula', 'S')->first();
      return isset($resp['anio']) ? $resp['anio'] : null;
   }

   public function listarAnios()
   {
      $resp = $this->select(array(
         'anio',
         'nombre',
         'vigente',
         'matricula',
         new RawSql("DATE_FORMAT(fecini, '%d/%m/%Y') AS fecini"),
         new RawSql("DATE_FORMAT(fecfin, '%d/%m/%Y') AS fecfin"),
         new RawSql("(CASE WHEN fecfin < NOW() THEN 'C' ELSE 'A' END) AS estado")
      ))->orderBy('anio', 'DESC')->findAll();
      $nuevo = array();
      foreach ($resp as $val) :
         $nuevo[] = array(
            'anio'      => $val['anio'],
            'nombre'    => $val['nombre'],
            'fecini'    => $val['fecini'],
            'fecfin'    => $val['fecfin'],
            'vigente'   => $val['vigente'] == 'S',
            'matricula' => $val['matricula'] == 'S',
            'estado'    => $val['estado']
         );
      endforeach;
      return $nuevo;
   }
}
