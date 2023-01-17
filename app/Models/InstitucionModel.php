<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class InstitucionModel extends Model
{
   protected $table      = 'institucion';
   protected $primaryKey = 'codigo';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codigo', 'nombre', 'direccion', 'referencia', 'ubgdir', 'telefono', 'ruc', 'correo', 'web', 'director', 'administrador', 'colorpri', 'colorsec'];
   protected $useTimestamps = false;

   public function obtenerNombre()
   {
      $result = $this->select('nombre')->first();
      return isset($result['nombre']) ? $result['nombre'] : null;
   }

   public function obtenerDatosInstitucion()
   {
      return $this->select(array(
         '*',
         new RawSql("TRIM(SUBSTR(ubgdir, 1, 2)) AS dept"),
         new RawSql("TRIM(SUBSTR(ubgdir, 1, 4)) AS prov")
      ))->first();
   }
}
