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

     public function listarDestinatarios($codpub)
     {
          if (is_null($codpub)) return array();
          $query = $this->db->table('publicacion_dest pd')
               ->select('pd.*, p.nombre')
               ->join('perfil p', 'p.perfil = pd.perfil', 'INNER')
               ->where('pd.codpub', $codpub);
          $result = $query->get();
          return $result->getResultArray();
     }
}
