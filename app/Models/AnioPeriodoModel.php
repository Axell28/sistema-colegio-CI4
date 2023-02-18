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

     public function listarPeridosCombo($params)
     {
          $query = $this->db->table('anio_periodo p')
               ->select(array(
                    'p.anio',
                    'p.periodo',
                    'dd.descripcion AS periododes'
               ))->join("datosdet dd", "dd.coddat = '011' AND dd.coddet = p.tipo", "LEFT")
               ->where("p.anio", $params['anio'])
               ->orderBy("p.anio DESC, p.periodo ASC");
          $result = $query->get();
          return $result->getResultArray();
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
                    end as estadodes"),
                    new RawSql("t1.activo AS activo")
               ))
               ->join("datosdet dd", "dd.coddat = '011' AND dd.coddet = p.tipo", "LEFT")
               ->join(
                    new RawSql("(SELECT 'S' AS activo, anio, MAX(periodo) AS permax FROM anio_periodo WHERE fecini <= NOW() GROUP BY anio ORDER BY periodo DESC) AS t1"),
                    "t1 ON t1.anio = p.anio AND t1.permax = p.periodo",
                    "LEFT"
               )
               ->where("p.anio", $anio)
               ->orderBy("p.anio DESC, p.periodo ASC");
          $result = $query->get();
          return $result->getResultArray();
     }
}
