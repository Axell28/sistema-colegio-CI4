<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class PublicacionDestModel extends Model
{
     protected $table      = 'publicacion_dest';
     protected $primaryKey = 'iditem';

     protected $useAutoIncrement = true;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['codpub', 'perfil'];
     protected $useTimestamps = false;

     public function guardarDestinatarios($codpub, $destinatarios)
     {
          $this->where('codpub', $codpub)->delete();
          foreach ($destinatarios as $value) {
               $this->insert(array('codpub' => $codpub, 'perfil' => $value));
          }
     }
}
