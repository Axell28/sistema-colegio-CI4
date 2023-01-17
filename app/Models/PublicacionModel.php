<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class PublicacionModel extends Model
{
   protected $table      = 'publicacion';
   protected $primaryKey = 'codpub';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codpub', 'titulo', 'tipo', 'cuerpo', 'fecpubini', 'fecpubfin', 'adjunto', 'vistas', 'matricula', 'usureg', 'usumod', 'fecreg', 'fecmod', 'estado'];
   protected $useTimestamps = true;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function listarPublicaciones(array $params = array())
   {
      $this->db->query('SET @row_number := 0');
      $query = $this->db->table('publicacion p')
         ->select(
            array(
               new RawSql("(@row_number:=@row_number + 1) AS fila"),
               'p.codpub',
               'p.titulo',
               'p.tipo',
               'dd.descripcion as tipodes',
               "DATE_FORMAT(p.fecpubini, '%d/%m/%Y %h:%i %p') AS fecpubini",
               "DATE_FORMAT(p.fecpubfin, '%d/%m/%Y %h:%i %p') AS fecpubfin",
               "DATE_FORMAT(p.fecreg, '%d/%m/%Y %h:%i %p') AS fecreg",
               'p.adjunto',
               'p.usureg',
               'p.fecreg',
               'p.estado',
               'u.nombre AS usuario_nomb',
               new RawSql("(CASE WHEN p.fecpubini <= NOW() THEN 'S' ELSE 'N' END) AS es_visible")
            )
         )
         ->join('usuario u', 'u.usuario = p.usureg', 'INNER')
         ->join('datosdet dd', 'dd.coddat = 009 and dd.coddet = p.tipo', 'LEFT');

      if (isset($params['tipo']) && !empty($params['tipo'])) {
         $query->where('p.tipo', $params['tipo']);
      }

      /* if (isset($params['fecdesde']) && isset($params['fechasta'])) {
         $query->where(new RawSql("p.fecpubini BETWEEN '" . $params['fecdesde'] . "' AND '" . $params['fechasta'] . "'"));
      } */

      $query->orderBy('p.fecpubini', 'DESC');
      return $query->get()->getResultArray();
   }

   public function listarPublicacionesIndex()
   {
      $query = $this->db->table('publicacion p')
         ->select(
            array(
               'p.codpub',
               'p.titulo',
               'p.tipo',
               'p.cuerpo',
               'dd.descripcion as tipodes',
               "DATE_FORMAT(p.fecpubini, '%d/%m/%Y %h:%i %p') AS fecpubini",
               "DATE_FORMAT(p.fecpubfin, '%d/%m/%Y %h:%i %p') AS fecpubfin",
               'p.adjunto',
               'p.usureg',
               'p.fecreg',
               'p.estado',
               'u.nombre AS usuario_nomb',
               'e.fotourl',
               new RawSql("(SELECT ps.sexo FROM persona ps WHERE ps.codper = e.codper LIMIT 1) AS per_sexo")
            )
         )
         ->join('usuario u', 'u.usuario = p.usureg', 'INNER')
         ->join('datosdet dd', 'dd.coddat = 009 and dd.coddet = p.tipo', 'LEFT')
         ->join('empleado e', 'e.codemp = u.codigo', "LEFT")
         ->where(new RawSql("p.fecpubini <= NOW()"));
      $query->orderBy('p.fecpubini', 'DESC');
      return $query->get()->getResultArray();
   }

   public function guardarPublicacion(array $params, $action)
   {
      try {
         $this->db->transBegin();
         $codpub = $params['codpub'];
         $valuesPub = array(
            'titulo' => $params['titulo'],
            'tipo'   => $params['tipo'],
            'cuerpo' => $params['cuerpo'],
            'fecpubini' => !empty($params['fecpubini']) ? $params['fecpubini'] : null,
            'cuerpo' => $params['cuerpo'],
            'usureg' => USUARIO,
            'estado' => 'A'
         );
         if ($action == 'I') {
            $this->insert($valuesPub);
         } else if ($action == 'E') {
            $this->set($valuesPub)->where('codpub', $codpub)->update();
         }
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage(), $ex->getCode());
      }
   }

   public function getDatosDefault()
   {
      return array(
         'titulo' => "",
         'tipo'   => "",
         'cuerpo' => "",
         'fecpubini' => date('Y-m-d\TH:i:s'),
         'fecpubfin' => ""
      );
   }
}
