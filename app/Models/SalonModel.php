<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class SalonModel extends Model
{
   protected $table      = 'salon';
   protected $primaryKey = 'salon';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['salon', 'anio', 'nivel', 'grado', 'seccion', 'nombre', 'tutor', 'cotutor', 'coordinador', 'vacantes', 'aula', 'turno', 'modalidad'];
   protected $useTimestamps = false;

   public function listarSalones(array $params)
   {
      $query = $this->db->table('salon s')
         ->select(array(
            's.*',
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) as tutor_nomb"),
            'd1.descripcion as modalidad_des',
            'd2.descripcion as turno_des'
         ))
         ->join('datosdet d1', "d1.coddat = '001' and d1.coddet = s.modalidad", 'LEFT')
         ->join('datosdet d2', "d2.coddat = '002' and d2.coddet = s.turno", 'LEFT')
         ->join('empleado em', 'em.codemp = s.tutor', 'LEFT')
         ->join('persona p', 'p.codper = em.codper', 'LEFT');

      if (isset($params['anio']) && !empty($params['anio'])) {
         $query->where('s.anio', $params['anio']);
      }

      if (isset($params['nivel']) && !empty($params['nivel'])) {
         $query->where('s.nivel', $params['nivel']);
      }

      $query->orderBy('s.nivel, s.grado, s.seccion');

      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarSalonesComboBox(array $params)
   {
      $query = $this->db->table('salon s')->select('s.salon, s.anio, s.nombre');

      if (isset($params['anio']) && !empty($params['anio'])) {
         $query->where('s.anio', $params['anio']);
      }

      if (isset($params['codemp']) && !SUPER_ADMIN) {
         $query->join("asignacion_curso ac", "ac.salon = s.salon", "INNER");
         $query->where('ac.codemp', $params['codemp']);
         $query->groupBy('s.salon');
      }

      $query->orderBy('s.nivel, s.grado, s.seccion');

      $result = $query->get();
      return $result->getResultArray();
   }

   public function obtenerDatosSalon($salon)
   {
      $query = $this->db->table('salon s')
         ->select(array(
            's.*',
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) as tutor_nomb"),
            'd1.descripcion as modalidad_des',
            'd2.descripcion as turno_des'
         ))
         ->join('datosdet d1', "d1.coddat = '001' and d1.coddet = s.modalidad", 'LEFT')
         ->join('datosdet d2', "d2.coddat = '002' and d2.coddet = s.turno", 'LEFT')
         ->join('empleado em', 'em.codemp = s.tutor', 'LEFT')
         ->join('persona p', 'p.codper = em.codper', 'LEFT');

      $query->where('s.salon', !empty($salon) ? $salon : 'S0000000');
      $result = $query->get();
      return $result->getRowArray();
   }

   public function obtenerDatosSalonxNGS(array $params)
   {
      $query = $this->db->table('salon s')
         ->select(array(
            's.*',
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) as tutor_nomb"),
            'd1.descripcion as modalidad_des',
            'd2.descripcion as turno_des'
         ))
         ->join('datosdet d1', "d1.coddat = '001' and d1.coddet = s.modalidad", 'LEFT')
         ->join('datosdet d2', "d2.coddat = '002' and d2.coddet = s.turno", 'LEFT')
         ->join('empleado em', 'em.codemp = s.tutor', 'LEFT')
         ->join('persona p', 'p.codper = em.codper', 'LEFT');
      $query->where('s.anio', $params['anio']);
      $query->where('s.nivel', $params['nivel']);
      $query->where('s.grado', $params['grado']);
      $query->where('s.seccion', $params['seccion']);
      $result = $query->get();
      return $result->getRowArray();
   }

   public function existeTutorSalon($tutor)
   {
      if (empty($tutor)) return false;
      $query = $this->select()->where('tutor', $tutor)->first();
      return empty($query) ? false : true;
   }

   public function existeSalon(array $params)
   {
      $query = $this->select()->where(array('anio' => $params['anio'], 'nivel' => $params['nivel'], 'grado' => $params['grado'], 'seccion' => $params['seccion']))->first();
      return empty($query) ? false : true;
   }

   public function generarCodigo($anio)
   {
      $result = $this->db->query("SELECT fu_generar_codigo('S', '{$anio}') AS codigo")->getRow();
      if (isset($result->codigo)) {
         $existeCod = empty($this->find($result->codigo));
         return $existeCod ? $result->codigo : $this->generarCodigo($anio);
      }
   }
}
