<?php

namespace App\Models;

use CodeIgniter\Model;

class DatosModel extends Model
{
   public function listarDatos($coddat = null, $order = false)
   {
      $query = $this->db->table('datos d')
         ->select('dd.coddet AS codigo, dd.descripcion, dd.default')
         ->join('datosdet dd', 'dd.coddat = d.coddat', 'INNER')
         ->where('d.coddat', $coddat);
      if ($order) :
         $query->orderBy('dd.descripcion', 'ASC');
      endif;
      $resp = $query->get();
      return $resp->getResultArray();
   }
}
