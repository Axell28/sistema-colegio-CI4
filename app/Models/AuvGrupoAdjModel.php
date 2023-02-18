<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvGrupoAdjModel extends Model
{
     protected $table      = 'auv_grupo_adj';
     protected $primaryKey = 'coditem';

     protected $useAutoIncrement = true;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['coditem', 'orden', 'nombre', 'tamanio', 'ruta'];

     protected $useTimestamps = false;

     public function guardarAdjunto(array $params)
     {
          $this->insert(array(
               'coditem' => $params['codigo'],
               'orden' => $params['orden'],
               'nombre' => $params['nombre'],
               'tamanio' => $params['tamanio'],
               'ruta' => $params['ruta']
          ));
     }

     public function obtenerAdjuntosAuvGrupoItem($item)
     {
          $query = $this->select()->where('coditem', $item)->orderBy('orden', 'ASC');
          return $query->findAll();
     }
}
