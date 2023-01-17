<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuloModel extends Model
{
   protected $table      = 'modulo';
   protected $primaryKey = 'codmod';
   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;
   protected $allowedFields = ['nombre', 'url', 'estado'];

   public function listarModulos()
   {
      $query = $this->select()->where('estado', 'A');
      $result = $query->findAll();
      return $result;
   }

   public function buscarPorUrl($url)
   {
      return $this->where('url', $url)->first();
   }

   public function obtenerNombre($modulo)
   {
      $resp = $this->where('codmod', $modulo)->first();
      return isset($resp['nombre']) ? $resp['nombre'] : null;
   }

   public function listarModulosxPerfil(array $params)
   {
      $query = $this->db->table('modulo md')
         ->select('md.codmod, md.nombre, md.url')
         ->join('menu m', 'm.codmod = md.codmod', 'INNER')
         ->join('menu_rol mr', 'mr.codmenu = m.codmenu', 'INNER')
         ->where('md.estado', 'A');
      $query->where('mr.perfil', $params['perfil']);
      //$query->where('mo.codmod', $params['modulo']);
      $query->groupBy('m.codmod, md.nombre, md.url');
      $result = $query->get();
      return $result->getResultArray();
   }
}
