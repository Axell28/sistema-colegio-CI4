<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AnioPeriodoModel extends Model
{
     protected $table      = 'anio_periodo';
     protected $primaryKey = 'anio';

     protected $useAutoIncrement = false;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['anio', 'periodo', 'tipo', 'fecini', 'fecfin', 'estado'];
     protected $useTimestamps = false;

     public function existePeriodo(array $params)
     {
          $query = $this->select()->where(array('anio' => $params['anio'], 'periodo' => $params['periodo']))->first();
          return !empty($query);
     }

     public function listarPeridosxAnio($anio)
     {
          $query = $this->db->table('anio_periodo p')
               ->select(array(
                    "p.anio",
                    "p.periodo",
                    "p.tipo",
                    "p.fecini",
                    "p.fecfin",
                    "dd.descripcion AS periododes",
                    new RawSql("
                    case when now() between p.fecini and p.fecfin then 'A' 
                         when now() < p.fecfin then 'B'
                         else 'F' 
                    end as estado"),
                    new RawSql("
                    case when now() between p.fecini and p.fecfin then 'Vigente' 
                         when now() < p.fecfin then 'Bloqueado'
                         else 'Finalizado' 
                    end as estadodes")
               ))
               ->join("datosdet dd", "dd.coddat = '011' AND dd.coddet = p.tipo", "LEFT")
               ->where("p.anio", $anio)
               ->orderBy("p.anio DESC, p.periodo ASC");
          $result = $query->get();
          return $result->getResultArray();
     }
}
