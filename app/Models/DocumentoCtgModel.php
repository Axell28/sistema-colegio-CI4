<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class DocumentoCtgModel extends Model
{
   protected $table      = 'documento_ctg';
   protected $primaryKey = 'codcat';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codcat', 'nombre', 'estado'];
   protected $useTimestamps = false;

   public function listarDocsCategorias()
   {
      return $this->select('codcat AS codigo, nombre, estado')->findAll();
   }
}
