<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class PublicacionFilesModel extends Model
{
   protected $table      = 'publicacion_files';
   protected $primaryKey = 'codpub';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codpub', 'orden', 'nombre', 'tamanio', 'ruta'];
   protected $useTimestamps = false;

   public function obtenerAdjuntosPublicacion($codpub)
   {
      $query = $this->select()->where('codpub', $codpub)->orderBy('orden', 'ASC');
      return $query->findAll();
   }
}
