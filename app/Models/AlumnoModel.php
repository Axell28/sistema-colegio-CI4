<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AlumnoModel extends Model
{
   protected $table      = 'alumno';
   protected $primaryKey = 'codalu';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codalu', 'codfam', 'codper', 'anioing', 'fecing', 'fecsal', 'nivel', 'grado', 'seccion', 'matricula', 'usureg', 'fecreg', 'usumod', 'fecmod', 'fotourl', 'estado'];
   protected $useTimestamps = true;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function listarAlumnos(array $params = array())
   {
      $query = $this->db->table('alumno a')
         ->select(array(
            'a.codalu', 'a.codfam', 'a.anioing', 'a.fecing',
            'a.fecsal', 'a.nivel', 'a.grado', 'a.seccion',
            "COALESCE(a.matricula, 'N') AS matricula", 'a.estado',
            'a.fecing', 'a.fecsal', 'a.motsal', 'a.fotourl',
            'n.descripcion AS nivel_des', 'g.descripcion AS grado_des',  'p.*',
            new RawSql("TRIM(SUBSTR(p.ubgdir, 1, 2)) AS dept"),
            new RawSql("TRIM(SUBSTR(p.ubgdir, 1, 4)) AS prov"),
            new RawSql("(CASE WHEN a.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) AS estado_des"),
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp")
         ))
         ->join('persona p', 'p.codper = a.codper', 'INNER')
         ->join('nivel n', 'n.nivel = a.nivel', 'LEFT')
         ->join('grado g', 'g.nivel = n.nivel AND g.grado = a.grado', 'LEFT');

      if (isset($params['estado']) && !empty($params['estado'])) {
         $query->where('a.estado', $params['estado']);
      }

      if (isset($params['sexo']) && !empty($params['sexo'])) {
         $query->where('p.sexo', $params['sexo']);
      }

      if (isset($params['nivel']) && !empty($params['nivel'])) {
         $query->where('a.nivel', $params['nivel']);
      }

      $query->orderBy('p.apepat, p.apemat');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarAlumnosNoMatriculados()
   {
      $query = $this->db->table('alumno a')
         ->select(array(
            'a.codalu', 'a.codfam', 'a.anioing', 'a.fecing',
            'a.fecsal', 'a.nivel', 'a.grado', 'a.seccion', 'a.matricula', 'a.estado',
            'a.fecing', 'a.fecsal', 'a.motsal', 'a.fotourl', 'p.numdoc',
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp")
         ))
         ->join('persona p', 'p.codper = a.codper', 'INNER');

      $query->where(new RawSql("COALESCE(a.matricula, 'N') = 'N'"));
      $query->orderBy('p.apepat, p.apemat');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function guardarDatosAlumno(array $params, $action = 'I')
   {
      $this->db->transBegin();
      try {
         $codalu = $params['codalu'];
         $personaModel = new PersonaModel();
         $valuesPer = array(
            'nombres'  => trim($params['nombres']),
            'apepat'   => !empty($params['apepat']) ? mb_strtoupper(trim($params['apepat']), 'UTF-8') : null,
            'apemat'   => !empty($params['apemat']) ? mb_strtoupper(trim($params['apemat']), 'UTF-8') : null,
            'fecnac'   => !empty($params['fecnac']) ? $params['fecnac'] : null,
            'sexo'     => $params['sexo'],
            'tipdoc'   => $params['tipdoc'],
            'numdoc'   => trim($params['numdoc']),
            'email'    => !empty($params['email']) ? trim($params['email']) : null,
            'direccion'  => !empty($params['direccion']) ? trim($params['direccion']) : null,
            'referencia' => !empty($params['referencia']) ? trim($params['referencia']) : null,
            'ubgdir'   => !empty($params['ubgdir']) ? $params['ubgdir'] : null,
            'lugnac'   => !empty($params['lugnac']) ? trim($params['lugnac']) : null,
            'religion' => !empty($params['religion']) ? trim($params['religion']) : null,
            'nacionalidad' => !empty($params['nacionalidad']) ? trim($params['nacionalidad']) : null
         );
         if ($action == 'I') {
            $valuesPer['usureg'] = USUARIO;
            $codper = $personaModel->insert($valuesPer, true);
            $codalu = $this->generarCodigo(ANIO);
            $valuesAlu = array(
               'codalu'  => $codalu,
               'codfam'  => $params['codfam'],
               'codper'  => $codper,
               'anioing' => $params['anioing'],
               'nivel'   => !empty($params['nivel']) ? $params['nivel'] : null,
               'grado'   => !empty($params['nivel']) ? $params['grado'] : null,
               'seccion' => !empty($params['seccion']) ? $params['seccion'] : null,
               'fecing'  => !empty($params['fecing']) ? $params['fecing'] : null,
               'fecsal'  => !empty($params['fecsal']) ? $params['fecsal'] : null,
               'motsal'  => !empty($params['motsal']) ? $params['motsal'] : null,
               'estado'  => $params['estado'],
               'usureg'  => USUARIO
            );
            $this->insert($valuesAlu);
         } else if ($action == 'E') {
            $valuesPer['usumod'] = USUARIO;
            $valuesAlu = array(
               'codfam'  => $params['codfam'],
               'anioing' => $params['anioing'],
               'fecing'  => !empty($params['fecing']) ? $params['fecing'] : null,
               'fecsal'  => !empty($params['fecsal']) ? $params['fecsal'] : null,
               'motsal'  => !empty($params['motsal']) ? $params['motsal'] : null,
               'estado'  => $params['estado'],
               'usumod'  => USUARIO
            );
            $personaModel->set($valuesPer)->update($params['codper']);
            $this->set($valuesAlu)->update($params['codalu']);
         }
         $this->db->transCommit();
         return $codalu;
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage(), $ex->getCode());
      }
   }

   public function eliminarAlumno($codalu)
   {
      $this->db->transBegin();
      try {
         $this->where('codalu', $codalu)->delete();
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage(), $ex->getCode());
      }
   }

   public function generarCodigo($anio)
   {
      $result = $this->db->query("SELECT fu_generar_codigo('A', '{$anio}') AS codigo")->getRow();
      if (isset($result->codigo)) {
         $existeCod = empty($this->find($result->codigo));
         return $existeCod ? $result->codigo : $this->generarCodigo($anio);
      }
   }
}
