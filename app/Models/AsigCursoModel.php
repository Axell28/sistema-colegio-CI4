<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AsigCursoModel extends Model
{
   protected $table      = 'asignacion_curso';
   protected $primaryKey = 'salon';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['salon', 'codcur', 'codemp'];
   protected $useTimestamps = false;

   public function guardarAsignacion($params)
   {
      $existeReg = $this->select()->where(array('salon' => $params['salon'], 'codcur' => $params['curso']))->first();
      if (empty($existeReg)) {
         $this->insert(array(
            'salon'  => $params['salon'],
            'codcur' => $params['curso'],
            'codemp' => !empty($params['docente']) ? $params['docente'] : null
         ));
      } else {
         $this->set(array(
            'codemp' => !empty($params['docente']) ? $params['docente'] : null
         ))->where(array(
            'salon'  => $params['salon'],
            'codcur' => $params['curso']
         ))->update();
      }
   }

   public function obtenerDatosAsignacionCurso(array $params)
   {
      $query = $this->db->table('asignacion_curso ac')
         ->select(array(
            "ac.salon",
            "c.codcur",
            "c.nombre AS curnom",
            "ac.codemp AS docentecod",
            "CONCAT(UPPER(p.nombres), ' ', p.apepat, ' ', p.apemat) AS docentenom"
         ))
         ->join("salon s", "s.salon = ac.salon", "INNER")
         ->join("curso c", "c.codcur = ac.codcur", "INNER")
         ->join("empleado e", "e.codemp = ac.codemp", "INNER")
         ->join("persona p", "p.codper = e.codper", "LEFT")
         ->where(array(
            's.anio' => $params['anio'],
            's.salon' => $params['salon'],
            'ac.codcur' => $params['curso']
         ));
      $result = $query->get();
      return $result->getRowArray();
   }

   public function listarGrupodeCursosAsignados(array $params)
   {
      $query = $this->db->table('curriculo cu')
         ->select(array(
            's.anio',
            's.nivel',
            's.grado',
            's.seccion',
            's.salon',
            's.nombre AS salondes',
            'c.codcur',
            'c.nombre AS curnom',
            'ac.codemp',
            new RawSql("CONCAT(s.salon, '-', c.codcur) AS ide"),
            new RawSql("CONCAT(s.nivel, s.grado, s.seccion, ' - ', s.nombre) AS ngs"),
            new RawSql("COALESCE(CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres), 'Ninguno') as encargado")
         ))
         ->join('salon s', 's.anio = cu.anio AND s.nivel = cu.nivel AND s.grado = cu.grado', 'INNER')
         ->join('curso c', 'c.codcur = cu.curso', 'INNER')
         ->join('asig_curso ac', 'ac.salon = s.salon', 'LEFT')
         ->join('empleado em', 'em.codemp = ac.codemp', 'LEFT')
         ->join('persona p', 'p.codper = em.codper', 'LEFT');
      $query->where('cu.anio', $params['anio']);
      $query->where(new RawSql("NOT EXISTS (SELECT 1 FROM curriculo t1 where t1.curpad = cu.curso)"));

      if (isset($params['nivel']) && !empty($params['nivel'])) {
         $query->where('s.nivel', $params['nivel']);
      }

      if (isset($params['grado']) && !empty($params['grado'])) {
         $query->where('s.grado', $params['grado']);
      }

      if (isset($params['seccion']) && !empty($params['seccion'])) {
         $query->where('s.seccion', $params['seccion']);
      }

      if (isset($params['curso']) && !empty($params['curso'])) {
         $query->where('cu.curso', $params['curso']);
      }

      if (isset($params['codemp']) && !empty($params['codemp'])) {
         $query->where('ac.codemp', $params['codemp']);
      }

      $query->orderBy('s.nivel, s.grado, s.seccion, cu.orden');
      $result = $query->get();
      return $result->getResultArray();
   }
}
