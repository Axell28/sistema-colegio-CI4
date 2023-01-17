<?php

namespace App\Models;

use CodeIgniter\Model;

class GradoModel extends Model
{
   protected $table      = 'grado';
   protected $primaryKey = 'grado';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['nivel', 'grado', 'descripcion', 'orden'];
   protected $useTimestamps = false;

   public function listarGrados()
   {
      $result = $this->findAll();
      $pivot = array();
      foreach ($result as $grado) :
         $pivot[$grado['nivel']][$grado['grado']] = $grado;
      endforeach;
      return $pivot;
   }

   public function listarGradosPorNivel($nivel)
   {
      $result = $this->where('nivel', $nivel)->orderBy('grado')->findAll();
      return $result;
   }
}