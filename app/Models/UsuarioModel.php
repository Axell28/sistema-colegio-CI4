<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class UsuarioModel extends Model
{
   protected $table      = 'usuario';
   protected $primaryKey = 'usuario';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['usuario', 'perfil', 'nombre', 'passwd', 'codigo', 'entidad', 'fecreg', 'fecmod', 'ultcon', 'estado', 'supadm'];
   protected $useTimestamps = true;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function generarUsuario(array $params)
   {
      $retorno = array();
      try {
         $this->db->transBegin();
         $nombreUsuario = $this->generarCodigoUsuario($params['apellidos'], $params['nombres']);
         $passwdUsuario = self::generarPassword(10);
         $this->insert(array(
            'usuario' => $nombreUsuario,
            'perfil'  => $params['perfil'],
            'nombre'  => $params['nomcomp'],
            'passwd'  => password_hash($passwdUsuario, PASSWORD_DEFAULT),
            'codigo'  => $params['codigo'],
            'entidad' => $params['entidad'],
            'estado'  => 'A'
         ));
         $this->db->transCommit();
         $retorno['usuario'] = $nombreUsuario;
         $retorno['password'] = $passwdUsuario;
         return $retorno;
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }

   public function listarUsuarios(array $params = array())
   {
      $query = $this->db->table("usuario u")
         ->select(array(
            "u.usuario",
            "u.perfil",
            "u.nombre",
            "u.codigo",
            "u.entidad",
            "u.estado",
            "DATE_FORMAT(u.fecreg, '%d/%m/%Y %h:%i:%s') AS fecreg",
            "DATE_FORMAT(u.fecmod, '%d/%m/%Y %h:%i:%s') AS fecmod",
            "DATE_FORMAT(u.ultcon, '%d/%m/%Y %h:%i:%s') AS ultcon",
            "p.nombre AS perfil_nomb",
            new RawSql("(CASE WHEN u.estado = 'A' THEN 'Activo' ELSE 'Inactivo' END) AS estado_des")
         ))
         ->join("perfil p", "p.perfil = u.perfil", "INNER")
         ->where(new RawSql("COALESCE(supadm, 'N') <> 'S'"));

      if (isset($params['estado']) && !empty($params['estado'])) {
         $query->where('u.estado', $params['estado']);
      }

      if (isset($params['perfil']) && !empty($params['perfil'])) {
         $query->where('u.perfil', $params['perfil']);
      }

      $query->orderBy('u.fecreg', 'ASC');

      $result = $query->get()->getResultArray();

      foreach ($result as &$value) :
         $value['estado_bool'] = ($value['estado'] == 'A');
      endforeach;

      return $result;
   }

   public function buscarUsuario($usuario)
   {
      return $this->select()->where(new RawSql("BINARY usuario = '{$usuario}'"))->first();
   }

   public function obtenerNombre($usuario)
   {
      $result = $this->select('nombre')->where(new RawSql("BINARY usuario = '{$usuario}'"))->first();
      return isset($result['nombre']) ? $result['nombre'] : null;
   }

   public function obtenerFoto()
   {
      $fotoUrl = "/img/default/man.png";
      switch (ENTIDAD) {
         case 'EMP':
            $rowdata = $this->db->table('usuario u')->select('e.fotourl, p.sexo')
               ->join("empleado e", "e.codemp = u.codigo", "LEFT")
               ->join('persona p', "p.codper = e.codper", "LEFT")
               ->where(new RawSql("BINARY u.usuario = '" . USUARIO . "'"))->get()->getRowArray();
            $fotoUrl = isset($rowdata['fotourl']) ? $rowdata['fotourl'] : null;
            if (empty($fotoUrl)) {
               $fotoUrl = isset($rowdata['sexo']) ? ($rowdata['sexo'] == 'F' ? "/img/default/woman.png" : "/img/default/man.png") : "/img/default/man.png";
            }
            break;
         case 'ALU':
            $rowdata = $this->db->table('usuario u')->select('a.fotourl, p.sexo')
               ->join("alumno a", "a.codalu = u.codigo", "LEFT")
               ->join('persona p', "p.codper = a.codper", "LEFT")
               ->where(new RawSql("BINARY u.usuario = '" . USUARIO . "'"))->get()->getRowArray();
            $fotoUrl = isset($rowdata['fotourl']) ? $rowdata['fotourl'] : null;
            if (empty($fotoUrl)) {
               $fotoUrl = isset($rowdata['sexo']) ? ($rowdata['sexo'] == 'F' ? "/img/default/woman.png" : "/img/default/man.png") : "/img/default/man.png";
            }
            break;
      }
      return $fotoUrl;
   }

   public function marcarConexion($usuario)
   {
      $this->set('ultcon', date('Y-m-d H:i:s'))->where('usuario', $usuario)->update();
   }

   public function generarCodigoUsuario($apellidos = null, $nombres = null)
   {
      if (empty($apellidos) && empty($nombres)) {
         die('Error al generar el código de usuario');
      }
      // separamos nombres y apellidos
      $arrayNombres = explode(" ", $nombres);
      $arrayApellidos = explode(" ", $apellidos);

      $primerNombre = mb_strtoupper($arrayNombres[0], 'UTF-8');
      $segundoNombre = isset($arrayNombres[1]) ? mb_strtoupper($arrayNombres[1], 'UTF-8') : "";

      $primerApellido = mb_strtoupper($arrayApellidos[0], 'UTF-8');
      $segundoApellido = isset($arrayApellidos[1]) ? mb_strtoupper($arrayApellidos[1], 'UTF-8') : "";

      // quitamos espacios en blanco
      $primerNombre     = preg_replace('/[ <>\'\"]/', '', $primerNombre);
      $segundoNombre    = preg_replace('/[ <>\'\"]/', '', $segundoNombre);
      $primerApellido   = preg_replace('/[ <>\'\"]/', '', $primerApellido);
      $segundoApellido  = preg_replace('/[ <>\'\"]/', '', $segundoApellido);

      // creación de código
      $caso = 1;
      $usuarioLibre = FALSE;
      $usuarioCodigo = null;
      while (!$usuarioLibre) :
         switch ($caso) {
            case 1:
               $usuarioCodigo = self::cortarString(substr($primerNombre, 0, 1) . $primerApellido, 12);
               $existeUsuario = !empty($this->buscarUsuario($usuarioCodigo));
               if (!$existeUsuario) {
                  $usuarioLibre = TRUE;
               }
               break;
            case 2:
               $usuarioCodigo = self::cortarString(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 3) . $segundoApellido, 12);
               $existeUsuario = !empty($this->buscarUsuario($usuarioCodigo));
               if (!$existeUsuario) {
                  $usuarioLibre = TRUE;
               }
               break;
            default:
               $usuarioCodigo = self::cortarString(substr($primerNombre, 0, 1) . $primerApellido, 10) . rand(0, 99);
               $existeUsuario = !empty($this->buscarUsuario($usuarioCodigo));
               if (!$existeUsuario) {
                  $usuarioLibre = TRUE;
               }
               break;
         }
         $caso++;
      endwhile;
      return $usuarioCodigo;
   }

   private static function cortarString($s, $t)
   {
      return (substr($s, 0, $t));
   }

   private static function generarPassword($tamanio = 9)
   {
      $pwd = "";
      $pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*#@&";
      $max = strlen($pattern) - 1;
      for ($i = 0; $i < $tamanio; $i++) {
         $pwd .= substr($pattern, mt_rand(0, $max), 1);
      }
      return $pwd;
   }
}
