<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class FamiliaModel extends Model
{
   protected $table      = 'familia';
   protected $primaryKey = 'codfam';

   protected $useAutoIncrement = false;

   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codfam', 'nombre', 'direccion', 'telefono', 'email', 'usureg', 'fecreg', 'usumod', 'fecmod', 'estado'];

   // Dates
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'usureg';
   protected $updatedField  = 'fecmod';
   protected $useTimestamps = true;

   public function guardarDatosFamilia(array $params, $action = 'I')
   {
      try {
         $this->db->transBegin();
         $personaModel = new PersonaModel();
         $familiaDetModel = new FamiliaDetModel();
         $codfam = $params['codfam'];

         // datos Persona
         $valuesPer1 = array(
            'nombres'  => trim($params['nombre1']),
            'apepat'   => !empty($params['apepat1']) ? mb_strtoupper(trim($params['apepat1']), 'UTF-8') : null,
            'apemat'   => !empty($params['apemat1']) ? mb_strtoupper(trim($params['apemat1']), 'UTF-8') : null,
            'fecnac'   => !empty($params['fecnac1']) ? $params['fecnac1'] : null,
            'tipdoc'   => $params['tipdoc1'],
            'numdoc'   => trim($params['numdoc1']),
            'estcivil'  => !empty($params['estcivil1']) ? trim($params['estcivil1']) : null,
            'nacionalidad' => !empty($params['nacionalidad1']) ? trim($params['nacionalidad1']) : null,
            'celular1'  => !empty($params['celular1']) ? trim($params['celular1']) : null,
            'email'    => !empty($params['correo1']) ? trim($params['correo1']) : null
         );

         $valuesPer2 = array(
            'nombres'  => trim($params['nombre2']),
            'apepat'   => !empty($params['apepat2']) ? mb_strtoupper(trim($params['apepat2']), 'UTF-8') : null,
            'apemat'   => !empty($params['apemat2']) ? mb_strtoupper(trim($params['apemat2']), 'UTF-8') : null,
            'fecnac'   => !empty($params['fecnac2']) ? $params['fecnac2'] : null,
            'tipdoc'   => $params['tipdoc2'],
            'numdoc'   => trim($params['numdoc2']),
            'estcivil'  => !empty($params['estcivil2']) ? trim($params['estcivil2']) : null,
            'nacionalidad' => !empty($params['nacionalidad2']) ? trim($params['nacionalidad2']) : null,
            'celular1'  => !empty($params['celular2']) ? trim($params['celular2']) : null,
            'email'    => !empty($params['correo2']) ? trim($params['correo2']) : null
         );

         $valuesFam = array(
            'nombre' => $params['nombreFam'],
            'fecing' => $params['fecingFam'],
            'direccion' => $params['direccionFam'],
            'telefono' => $params['celularFam'],
            'email' => $params['correoFam']
         );
         if ($action == 'I') {
            $codfam = $this->generarCodigo(ANIO);
            $valuesPer1['usureg'] = USUARIO;
            $valuesPer2['usureg'] = USUARIO;
            $codper1 = $personaModel->insert($valuesPer1, true);
            $codper2 = $personaModel->insert($valuesPer2, true);

            $valuesFam['codfam'] = $codfam;
            $valuesFam['usureg'] = USUARIO;
            $this->insert($valuesFam);

            $familiaDetModel->insert(array(
               'codfam' => $codfam,
               'codper' => $codper1,
               'tipofam' => $params['parentesco1'],
               'responsable' => $params['responsable1'],
               'orden' => 1
            ));

            $familiaDetModel->insert(array(
               'codfam' => $codfam,
               'codper' => $codper2,
               'tipofam' => $params['parentesco2'],
               'responsable' => $params['responsable2'],
               'orden' => 2
            ));
         } else if ($action == 'E') {
            $valuesFam['usumod'] = USUARIO;
            //$this->set($valuesFam)->update($codfam);
         }
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }

   public function listarFamilias(array $params = array())
   {
      $query = $this->db->table('familia f')->select(array("f.*"));
      $query->orderBy('f.nombre', 'ASC');
      $result = $query->get()->getResultArray();
      foreach ($result as &$value) :
         $value['famdet'] = $this->listarFamiliaDetalle($value['codfam']);
      endforeach;
      return $result;
   }

   public function listarFamiliasCombo()
   {
      $query = $this->db->table('familia f')->select(array("f.codfam AS codigo", "f.nombre"));
      $query->orderBy('f.nombre', 'ASC');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarHijosFamilia(array $params = array())
   {
      $query = $this->db->table('familia f')
         ->select(array(
            "a.codalu", "a.codfam", "a.codper", "a.anioing",
            "a.nivel", "a.grado", "a.seccion", "a.estado", "a.matricula",
            new RawSql("(CASE WHEN a.matricula = 'S' THEN 'SI' ELSE 'NO' END) AS estado_des"),
            new RawSql("CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) AS nomcomp")
         ))
         ->join("alumno a", "a.codfam = f.codfam", "INNER")
         ->join("persona p", "p.codper = a.codper", "LEFT");

      if (isset($params['codfam']) && !empty($params['codfam'])) {
         $query->where("f.codfam", $params['codfam']);
      }

      $result = $query->get()->getResultArray();
      $pivotResult = array();
      foreach ($result as $value) :
         $pivotResult[$value['codfam']][] = $value;
      endforeach;
      return $pivotResult;
   }

   private function listarFamiliaDetalle($codfam)
   {
      $query = $this->db->table('familia f')
         ->select(array(
            "f.codfam",
            "fd.codigo AS fdcodigo",
            "fd.codper",
            "fd.tipofam",
            new RawSql("(SELECT dd.descripcion FROM datosdet dd WHERE dd.coddat = '008' AND dd.coddet = fd.tipofam) AS tipfam_des"),
            "fd.responsable",
            "p.nombres",
            "p.apemat",
            "p.apepat",
            "p.fecnac",
            "p.tipdoc",
            "p.numdoc",
            "p.nacionalidad",
            "p.estcivil",
            "p.celular1",
            "p.email"
         ))
         ->join("familia_det fd", "fd.codfam = f.codfam", "INNER")
         ->join("persona p", "p.codper = fd.codper", "INNER")
         ->where("f.codfam", $codfam)
         ->orderBy('fd.orden', 'ASC');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function generarCodigo($anio)
   {
      $result = $this->db->query("SELECT fu_generar_codigo('F', '{$anio}') AS codigo")->getRow();
      if (isset($result->codigo)) {
         $existeCod = empty($this->find($result->codigo));
         return $existeCod ? $result->codigo : $this->generarCodigo($anio);
      }
   }
}
