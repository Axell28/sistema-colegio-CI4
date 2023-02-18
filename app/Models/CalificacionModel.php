<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class CalificacionModel extends Model
{
     protected $table      = 'calificacion';
     protected $primaryKey = 'codcal';

     protected $useAutoIncrement = true;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['codcal', 'salon', 'codalu', 'curso', 'periodo', 'nota_c1', 'nota_c2', 'nota_c3', 'nota_c4', 'nota_pp'];
     protected $useTimestamps = false;

     public function listarCalificacion(array $params)
     {
          $curso = !empty($params['curso']) ? $params['curso'] : '000';
          $periodo = $params['periodo'];
          $this->db->query('SET @row_number := 0');
          $query = $this->db->table('alumno a')
               ->select(array(
                    new RawSql("(@row_number:=@row_number + 1) AS fila"),
                    "S.salon",
                    "s.nombre AS salondes",
                    "a.codalu",
                    "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp",
                    "c.periodo",
                    "c.nota_c1",
                    "c.nota_c2",
                    "c.nota_c3",
                    "c.nota_c4",
                    "c.nota_pp"
               ))
               ->join("matricula m", "m.codalu = a.codalu", "INNER")
               ->join("salon s", "s.salon = m.salon", "INNER")
               ->join("calificacion c", "c.salon AND c.codalu = a.codalu AND c.curso = '{$curso}' AND c.periodo = {$periodo}", "LEFT")
               ->join("persona p", "p.codper = a.codper", "LEFT");
          $query->where('s.salon', $params['salon']);
          $result = $query->get();
          return $result->getResultArray();
     }
}
