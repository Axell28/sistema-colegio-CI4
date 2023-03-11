<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class NotasModel extends Model
{
     protected $table      = 'notas';
     protected $primaryKey = 'codigo';

     protected $useAutoIncrement = true;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['codigo', 'salon', 'codalu', 'curso', 'periodo', 'nota_act', 'nota_exm', 'nota_con', 'nota_pp'];
     protected $useTimestamps = false;

     public function actualizarNotas(array $params)
     {
          try {
               $this->db->transBegin();
               $this->set($params['campo'], $params['valor'])->update($params['codigo']);
               $this->actualizarNotaPP($params['codigo']);
               $this->db->transCommit();
          } catch (\Exception $ex) {
               $this->db->transRollback();
               throw new \Exception($ex->getMessage());
          }
     }

     public function listarCalificaciones(array $params)
     {
          $curso = !empty($params['curso']) ? $params['curso'] : '000';
          $periodo = $params['periodo'];
          $this->db->query('SET @row_number := 0');
          $query = $this->db->table('notas n')
               ->select(array(
                    new RawSql("(@row_number:=@row_number + 1) AS fila"),
                    "n.codigo",
                    "S.salon",
                    "s.nombre AS salondes",
                    "n.codalu",
                    "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp",
                    "n.periodo",
                    "n.nota_act",
                    "n.nota_exm",
                    "n.nota_con",
                    "n.nota_pp"
               ))
               ->join("matricula m", "m.codalu = n.codalu", "INNER")
               ->join("salon s", "s.salon = m.salon", "INNER")
               ->join('alumno a', "a.codalu = m.codalu", "INNER")
               ->join("persona p", "p.codper = a.codper", "LEFT");
          $query->where('s.salon', $params['salon']);
          $query->where('n.curso', $curso);
          $query->where('n.periodo', $periodo);
          $result = $query->get();
          return $result->getResultArray();
     }

     public function listarCalificacionesAlumno(array $params)
     {
          $query = $this->db->table('notas n')
               ->select(array(
                    "n.codalu",
                    "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp",
                    "n.curso",
                    "n.periodo",
                    "n.nota_act",
                    "n.nota_exm",
                    "n.nota_con",
                    "n.nota_pp"
               ))
               ->join("matricula m", "m.codalu = n.codalu", "INNER")
               ->join("salon s", "s.salon = m.salon", "INNER")
               ->join('alumno a', "a.codalu = m.codalu", "INNER")
               ->join("persona p", "p.codper = a.codper", "LEFT");
          $query->where('s.salon', $params['salon']);
          $query->where('n.codalu', $params['codalu']);
          $result = $query->get()->getResultArray();
          $nuevoArray = array();
          foreach ($result as $value) {
               $curso = $value['curso'];
               $periodo = $value['periodo'];
               $nuevoArray[$curso][$periodo] = $value;
          }
          return $nuevoArray;
     }

     private function actualizarNotaPP($codigo)
     {
          $result = $this->select()->find($codigo);
          if (!empty($result)) {
               $nota_act = (int) $result['nota_act'];
               $nota_exm = (int) $result['nota_exm'];
               $nota_con = (int) $result['nota_con'];
               $promedio = round(($nota_act + $nota_exm + $nota_con) / 3);
               $this->set('nota_pp', $promedio)->update($codigo);
          }
     }
}
