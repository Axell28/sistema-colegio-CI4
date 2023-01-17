<?php

namespace App\Models;

use CodeIgniter\Model;

class NivelModel extends Model
{
   protected $table      = 'nivel';
   protected $primaryKey = 'nivel';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['nivel', 'descripcion', 'orden', 'estado'];
   protected $useTimestamps = false;

   public function listarNiveles($activos = true)
   {
      if ($activos) {
         return $this->where('estado', 'A')->findAll();
      }
      $result = $this->findAll();
      foreach ($result as &$item) :
         $item['activo'] = ($item['estado'] == 'A');
      endforeach;
      return $result;
   }

   public function listarNivelGrados()
   {
      $query = $this->db->table('nivel n')
         ->select(array(
            'n.nivel',
            'g.grado',
            'n.descripcion AS niveldes',
            'g.descripcion AS gradodes'
         ))
         ->join('grado g', 'g.nivel = n.nivel', 'JOIN')
         ->where('n.estado', 'A')
         ->orderBy('g.nivel, g.grado');
      $result = $query->get();
      return $result->getResultArray();
   }
}
