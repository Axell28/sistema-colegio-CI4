<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;
use PhpParser\Node\Expr\Empty_;

class EmpleadoModel extends Model
{
   protected $table      = 'empleado';
   protected $primaryKey = 'codemp';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codemp', 'codper', 'fecing', 'fecsal', 'motsal', 'docente', 'area', 'cargo', 'usureg', 'fecreg', 'usumod', 'fecmod', 'fotourl', 'estado'];
   protected $useTimestamps = true;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function listarEmpleados(array $params = array())
   {
      $query = $this->db->table('empleado e')
         ->select(array(
            'e.codemp', 'e.fecing', 'e.fecsal', 'e.motsal', 'e.docente',
            'e.cargo', 'e.area', 'e.fotourl', 'e.estado', 'p.*',
            'u.perfil', 'pe.nombre AS perfilnomb',
            "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) as nomcomp",
            new RawSql("TRIM(SUBSTR(p.ubgdir, 1, 2)) AS dept"),
            new RawSql("TRIM(SUBSTR(p.ubgdir, 1, 4)) AS prov"),
            new RawSql("(CASE WHEN e.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) AS estado_des"),
            new RawSql("(SELECT descripcion FROM datosdet WHERE coddat = '004' AND coddet = e.area) AS area_des")
         ))
         ->join('persona p', 'p.codper = e.codper', 'INNER')
         ->join('usuario u', 'u.codigo = e.codemp', 'LEFT')
         ->join('perfil pe', 'pe.perfil = u.perfil', 'LEFT');

      if (isset($params['area']) && !empty($params['area'])) {
         $query->where('e.area', $params['area']);
      }

      if (isset($params['estado']) && !empty($params['estado'])) {
         $query->where('e.estado', $params['estado']);
      }

      $query->orderBy('p.apepat, p.apemat');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarComboEmpleados(array $params = array())
   {
      $query = $this->db->table('empleado e')
         ->select(array(
            'e.codemp AS codigo',
            "CONCAT(p.apepat, ' ', p.apemat, ', ', p.nombres) as nombre",
         ))
         ->join('persona p', 'p.codper = e.codper', 'INNER')
         ->where('e.estado', 'A');

      if (isset($params['docentes'])) {
         $query->where('e.docente', 'S');
      }

      $query->orderBy('p.apepat, p.apemat');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function guardarEmpleado(array $params, $action = 'I')
   {
      $this->db->transBegin();
      try {
         $codemp = $params['codemp'];
         $codper = $params['codper'];
         $usuarioModel = new UsuarioModel();
         $personaModel = new PersonaModel();
         $valuesPer = array(
            'nombres'  => trim($params['nombres']),
            'apepat'   => !empty($params['apepat']) ? mb_strtoupper(trim($params['apepat']), 'UTF-8') : null,
            'apemat'   => !empty($params['apemat']) ? mb_strtoupper(trim($params['apemat']), 'UTF-8') : null,
            'fecnac'   => !empty($params['fecnac']) ? $params['fecnac'] : null,
            'sexo'     => $params['sexo'],
            'tipdoc'   => $params['tipdoc'],
            'numdoc'   => trim($params['numdoc']),
            'estcivil'  => !empty($params['estcivil']) ? trim($params['estcivil']) : null,
            'ruc'       => !empty($params['ruc']) ? trim($params['ruc']) : null,
            'celular1'  => !empty($params['celular1']) ? trim($params['celular1']) : null,
            'celular2'  => !empty($params['celular2']) ? trim($params['celular2']) : null,
            'email'    => !empty($params['email']) ? trim($params['email']) : null,
            'direccion'  => !empty($params['direccion']) ? trim($params['direccion']) : null,
            'referencia' => !empty($params['referencia']) ? trim($params['referencia']) : null,
            'ubgdir'   => !empty($params['ubgdir']) ? $params['ubgdir'] : null,
            'lugnac'   => !empty($params['lugnac']) ? trim($params['lugnac']) : null,
            'profesion' => !empty($params['profesion']) ? trim($params['profesion']) : null,
            'religion' => !empty($params['religion']) ? trim($params['religion']) : null,
            'nacionalidad' => !empty($params['nacionalidad']) ? trim($params['nacionalidad']) : null,
            'infoaca' => !empty($params['infoaca']) ? $params['infoaca'] : null
         );
         if ($action == 'I') {
            $valuesPer['usureg'] = USUARIO;
            $codper = $personaModel->insert($valuesPer, true);
            $codemp = $this->generarCodigo(ANIO);
            $valuesEmp = array(
               'codemp'  => $codemp,
               'codper'  => $codper,
               'fecing'  => !empty($params['fecing']) ? $params['fecing'] : null,
               'fecsal'  => !empty($params['fecsal']) ? $params['fecsal'] : null,
               'motsal'  => !empty($params['motsal']) ? $params['motsal'] : null,
               'area'    => !empty($params['area']) ? $params['area'] : null,
               'cargo'   => !empty($params['cargo']) ? $params['cargo'] : null,
               'docente' => !empty($params['docente']) ? $params['docente'] : null,
               'estado'  => $params['estado'],
               'usureg'  => USUARIO
            );
            $this->insert($valuesEmp);
            $usuarioModel->insert(array(
               'usuario' => $usuarioModel->generarCodigoUsuario($params['apepat'] . " " . $params['apemat'], $params['nombres']),
               'perfil' => $params['perfil'],
               'nombre' => mb_strtoupper(trim($params['apepat']), 'UTF-8') . " " . mb_strtoupper(trim($params['apemat']), 'UTF-8') .  ", " .  trim($params['nombres']),
               'passwd' => password_hash("12345", PASSWORD_DEFAULT),
               'codigo' => $codemp,
               'entidad' => 'EMP',
               'estado' => 'I'
            ));
         } else if ($action == 'E') {
            $valuesEmp = array(
               'fecing'  => !empty($params['fecing']) ? $params['fecing'] : null,
               'fecsal'  => !empty($params['fecsal']) ? $params['fecsal'] : null,
               'motsal'  => !empty($params['motsal']) ? $params['motsal'] : null,
               'area'    => !empty($params['area']) ? $params['area'] : null,
               'cargo'   => !empty($params['cargo']) ? $params['cargo'] : null,
               'docente' => !empty($params['docente']) ? $params['docente'] : null,
               'estado'  => $params['estado'],
               'usumod'  => USUARIO
            );
            $personaModel->set($valuesPer)->update($codper);
            $this->set($valuesEmp)->update($codemp);
         }
         $this->db->transCommit();
         return $codemp;
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage(), $ex->getCode());
      }
   }

   public function eliminarEmpleado($codemp)
   {
      $this->db->transBegin();
      try {
         $this->where('codemp', $codemp)->delete();
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage(), $ex->getCode());
      }
   }

   public function verificarDocente($codigo)
   {
      $query = $this->select()->where(array('codemp' => $codigo, 'docente' => 'S'))->first();
      return !empty($query);
   }

   public function generarCodigo($anio)
   {
      $result = $this->db->query("SELECT fu_generar_codigo('E', '{$anio}') AS codigo")->getRow();
      if (isset($result->codigo)) {
         $existeCod = empty($this->find($result->codigo));
         return $existeCod ? $result->codigo : $this->generarCodigo($anio);
      }
   }
}
