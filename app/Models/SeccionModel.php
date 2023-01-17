<?php

namespace App\Models;

use CodeIgniter\Model;

class SeccionModel extends Model
{
   protected $table      = 'seccion';
   protected $primaryKey = 'seccion';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['nivel', 'grado', 'seccion', 'descripcion', 'orden'];
   protected $useTimestamps = false;

   public function listarSecciones()
   {
      $result = $this->findAll();
      $pivot = array();
      foreach ($result as $seccion) {
         $pivot[$seccion['nivel']][$seccion['grado']][$seccion['seccion']] = $seccion;
      }
      return $pivot;
   }

   public function listarSeccionPorGrado($nivel, $grado)
   {
      $result = $this->where(array('nivel' => $nivel, 'grado' => $grado))->orderBy('seccion')->findAll();
      return $result;
   }
}
