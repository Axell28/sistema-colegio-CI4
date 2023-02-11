<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class MatriculaModel extends Model
{
   protected $table      = 'matricula';
   protected $primaryKey = 'codmat';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codmat', 'anio', 'salon', 'codalu', 'fecmat', 'fecsal', 'sitaca', 'motvret', 'observacion', 'modalidad', 'condicion', 'fecreg', 'usureg', 'fecmod', 'usumod'];

   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function listarRegistroMatricula(array $params)
   {
      $query = $this->db->table('matricula m')
         ->select(array(
            "m.*",
            "s.nivel",
            "s.grado",
            "s.seccion",
            "s.nombre AS salondes",
            "CONCAT(s.nivel, s.grado, s.seccion) AS ngs",
            "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS alunomb",
            "p.numdoc",
            "COALESCE(u.nombre, u.usuario) AS usunomreg",
            "dt.descripcion AS condes"
         ))
         ->join("alumno a", "a.codalu = m.codalu", "INNER")
         ->join("persona p", "p.codper = a.codper", "INNER")
         ->join("salon s", "s.salon = m.salon", "INNER")
         ->join("usuario u", "u.usuario = m.usureg", "LEFT")
         ->join("datosdet dt", "dt.coddat = '012' and dt.coddet = m.condicion", "LEFT");

      $query->where("m.anio", $params['anio']);

      if (isset($params['nivel']) && !empty($params['nivel'])) {
         $query->where("s.nivel", $params['nivel']);
      }

      if (isset($params['grado']) && !empty($params['grado'])) {
         $query->where("s.grado", $params['grado']);
      }

      if (isset($params['seccion']) && !empty($params['seccion'])) {
         $query->where("s.seccion", $params['seccion']);
      }

      if (isset($params['salon']) && !empty($params['salon'])) {
         $query->where("s.salon", $params['salon']);
      }

      if (isset($params['codalu'])) {
         $query->where('m.codalu', $params['codalu']);
         return $query->get()->getRowArray();
      }

      $result = $query->get();
      return $result->getResultArray();
   }

   public function registrarMatricula(array $params)
   {
      try {
         $this->db->transBegin();
         $alumnoModel = new AlumnoModel();
         $salonModel = new SalonModel();
         $codigoMatr = $this->generarCodigo($params['anio']);
         $this->insert(array(
            'codmat' => $codigoMatr,
            'anio'  => $params['anio'],
            'salon' => $params['salon'],
            'codalu' => $params['codalu'],
            'fecmat' => date('Y-m-d'),
            'condicion' => $params['condicion'],
            'sitaca' => 'V',
            'usureg' => USUARIO
         ));
         $datosSalon = $salonModel->find($params['salon']);
         $alumnoModel->set(array(
            'nivel' => $datosSalon['nivel'],
            'grado' => $datosSalon['grado'],
            'seccion' => $datosSalon['seccion'],
            'matricula' => 'S'
         ))->update($params['codalu']);
         $this->db->transCommit();
         return $codigoMatr;
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }

   public function obtenerDatosMatricula(array $params, $keyPorAlumno = false)
   {
      $query = $this->db->table('matricula m')
         ->select(array(
            "m.*",
            "s.nombre AS salonnom",
            "se.nivel",
            "se.grado",
            "se.seccion",
            "n.descripcion AS niveldes",
            "g.descripcion AS gradodes",
            "se.descripcion AS secciondes",
            "dd.descripcion AS sitacades",
            "dd2.descripcion AS condiciondes"
         ))
         ->join("salon s", "s.salon = m.salon", "INNER")
         ->join("nivel n", "n.nivel = s.nivel", "INNER")
         ->join("grado g", "g.nivel = s.nivel AND g.grado = s.grado", "INNER")
         ->join("seccion se", "se.nivel = s.nivel AND se.grado = s.grado AND se.seccion = s.seccion", "INNER")
         ->join("datosdet dd", "dd.coddat = '013' and dd.coddet = m.sitaca", "LEFT")
         ->join("datosdet dd2", "dd.coddat = '012' and dd.coddet = m.condicion", "LEFT");

      if (isset($params['codmat'])) {
         $query->where('m.codmat', $params['codmat']);
         $result = $query->get();
         return $result->getRowArray();
      }

      if (isset($params['codalu'])) {
         $query->where('m.codalu', $params['codalu']);
      }
      $result = $query->get()->getResultArray();
      $nuevoResult = array();
      if ($keyPorAlumno) {
         foreach ($result as $value) {
            $nuevoResult[$value['codalu']][] = $value;
         }
      } else {
         $nuevoResult = $result;
      }
      return $nuevoResult;
   }

   public function generarCodigo($anio)
   {
      $result = $this->db->query("SELECT fu_generar_codigo('M', '{$anio}') AS codigo")->getRow();
      if (isset($result->codigo)) {
         $existeCod = empty($this->find($result->codigo));
         return $existeCod ? $result->codigo : $this->generarCodigo($anio);
      }
   }
}
