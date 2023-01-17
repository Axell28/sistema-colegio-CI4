<?php

namespace App\Models;

use CodeIgniter\Model;

class PerfilModel extends Model
{
   protected $table      = 'perfil';
   protected $primaryKey = 'perfil';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['nombre', 'estado'];
   protected $useTimestamps = false;

   public function listarPerfiles($activos = true, $not = null)
   {
      $query = $this->select();
      if ($activos) {
         $query->where('estado', 'A');
      }
      if(!empty($not)) {
         $query->whereNotIn('perfil', $not);
      }
      return $query->findAll();
   }
}
