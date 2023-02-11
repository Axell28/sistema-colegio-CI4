<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class CurriculoModel extends Model
{
   protected $table      = 'curriculo';
   protected $primaryKey = 'anio';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['anio', 'nivel', 'grado', 'curso', 'tipcal', 'peso', 'curpad', 'orden', 'horas', 'nota_min', 'nota_max', 'nota_min_aprob'];
   protected $useTimestamps = false;

   public function obtenerDataCurricula(array $params)
   {
      $query = $this->db->table('curriculo c')->select('*');
      if (isset($params['anio'])) {
         $query->where('anio', $params['anio']);
      }
      if (isset($params['nivel'])) {
         $query->where('nivel', $params['nivel']);
      }
      if (isset($params['grado'])) {
         $query->where('grado', $params['grado']);
      }
      if (isset($params['curso'])) {
         $query->where('curso', $params['curso']);
      }
      $result = $query->get();
      return $result->getFirstRow();
   }

   public function listarCurriculaNG(array $params = array())
   {
      $query = $this->db->table('curriculo c')
         ->select(array(
            'c.*',
            'cu.nombre AS curnom',
            'cu.interno',
            new RawSql("(CASE WHEN c.tipcal = 'L' THEN 'Letra' WHEN c.tipcal = 'N' THEN 'Número' ELSE null END) AS tipcaldes")
         ))
         ->join('curso cu', 'cu.codcur = c.curso', 'INNER')
         ->where(array('c.anio' => $params['anio'], 'c.nivel' => $params['nivel'], 'c.grado' => $params['grado']));

      if (isset($params['tipo'])) {
         if ($params['tipo'] == 'I') {
            $query->where('cu.interno', 'S');
         } else if ($params['tipo'] == 'A') {
            $query->where('cu.interno', 'N');
         }
      }

      $query->orderBy('c.orden, COALESCE(c.curpad, curso)');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function checkExistenciaCurso(array $params)
   {
      $query = $this->select('1 AS item');
      if (isset($params['anio'])) {
         $query->where('anio', $params['anio']);
      }
      if (isset($params['nivel'])) {
         $query->where('nivel', $params['nivel']);
      }
      if (isset($params['grado'])) {
         $query->where('grado', $params['grado']);
      }
      if (isset($params['curso'])) {
         $query->where(new RawSql("(curpad = " . $params['curso'] . " OR curso = " . $params['curso'] . ")"));
      }
      return $query->first();
   }

   public function listarCursosAsignados(array $params)
   {
      $query = $this->db->table('curriculo cu')
         ->select(array(
            's.anio',
            's.nivel',
            's.grado',
            's.seccion',
            's.salon',
            's.nombre AS salon_nomb',
            'c.codcur',
            'c.nombre AS curnom',
            'ac.codemp',
            new RawSql("CONCAT(s.salon, '-', c.codcur) AS ide"),
            new RawSql("CONCAT(s.nivel, s.grado, s.seccion, ' - ', s.nombre) AS ngs"),
            new RawSql("COALESCE(CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres), 'Ninguno') as encargado")
         ))
         ->join('salon s', 's.anio = cu.anio AND s.nivel = cu.nivel AND s.grado = cu.grado', 'INNER')
         ->join('curso c', 'c.codcur = cu.curso', 'INNER')
         ->join('asignacion_curso ac', 'ac.salon = s.salon AND cu.curso = ac.codcur', 'LEFT')
         ->join('empleado em', 'em.codemp = ac.codemp', 'LEFT')
         ->join('persona p', 'p.codper = em.codper', 'LEFT');

      $query->where('cu.anio', $params['anio']);

      if (isset($params['nivel']) && !empty($params['nivel'])) {
         $query->where('s.nivel', $params['nivel']);
      }

      if (isset($params['grado']) && !empty($params['grado'])) {
         $query->where('s.grado', $params['grado']);
      }

      if (isset($params['curso']) && !empty($params['curso'])) {
         $query->where('cu.curso', $params['curso']);
      }

      if (isset($params['docente']) && !empty($params['docente'])) {
         $query->where('ac.codemp', $params['docente']);
      }

      $query->where(new RawSql("NOT EXISTS (SELECT 1 FROM curriculo t1 where t1.anio = cu.anio AND t1.nivel = cu.nivel AND t1.grado = cu.grado AND t1.curpad = cu.curso)"));

      $query->orderBy('s.nivel, s.grado, s.seccion, cu.orden');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarCursosIntranet(array $params)
   {
      $porEngargado = isset($params['codemp']);
      $query = $this->db->table('curriculo c')
         ->distinct()
         ->select(array(
            "s.salon",
            "s.nombre as salondes",
            "s.tutor",
            "c.nivel",
            "c.grado",
            "c.curso",
            "cu.nombre as curnomb",
            "ac.codemp as docentecod",
            "CONCAT(UPPER(p.nombres), ' ', p.apepat, ' ', p.apemat) AS docentenom"
         ))
         ->join("salon s", "s.nivel = c.nivel and s.grado = c.grado", "INNER")
         ->join("curso cu", "cu.codcur = c.curso", "INNER")
         ->join("asignacion_curso ac", "ac.salon = s.salon and ac.codcur = c.curso", $porEngargado ? "INNER" : "LEFT")
         ->join("empleado e", "e.codemp = ac.codemp", $porEngargado ? "INNER" : "LEFT")
         ->join("persona p", "p.codper = e.codper", "LEFT")
         ->where('s.anio', $params['anio']);

      $query->where(new RawSql("NOT EXISTS (SELECT 1 FROM curriculo t1 where t1.anio = c.anio AND t1.nivel = c.nivel AND t1.grado = c.grado AND t1.curpad = c.curso)"));
      $query->orderBy('s.nivel, s.grado, s.seccion, c.orden');

      if ($porEngargado) {
         $query->where('ac.codemp', $params['codemp']);
      }

      if (isset($params['salon'])) {
         $query->where('s.salon', $params['salon']);
      }

      $result = $query->get()->getResultArray();
      $nuevoArray = array();
      foreach ($result as $value) :
         $nuevoArray[$value['salon']]['nombre'] = $value['salondes'];
         $nuevoArray[$value['salon']]['codigo'] = $value['salon'];
         $nuevoArray[$value['salon']]['cursos'][] = $value;
      endforeach;
      return $nuevoArray;
   }
}
