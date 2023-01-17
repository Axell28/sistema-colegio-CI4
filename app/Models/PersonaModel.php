<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
   protected $table      = 'persona';
   protected $primaryKey = 'codper';
   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';
   protected $useTimestamps = true;

   protected $allowedFields = [
      'nombres', 'apepat', 'apemat', 'fecnac', 'sexo', 'tipdoc', 'numdoc', 'estcivil',
      'ruc', 'celular1', 'celular2', 'email', 'direccion', 'referencia', 'ubgdir', 'lugnac',
      'nacionalidad', 'profesion', 'religion', 'infoaca', 'usureg', 'fecmod', 'usumod'
   ];
}
