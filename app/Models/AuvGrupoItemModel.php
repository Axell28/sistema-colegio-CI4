<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvGrupoItemModel extends Model
{
     protected $table      = 'auv_grupo_item';
     protected $primaryKey = 'codigo';

     protected $useAutoIncrement = true;
     protected $returnType     = 'array';
     protected $useSoftDeletes = false;

     protected $allowedFields = ['codigo', 'grupo', 'titulo', 'tipo', 'cuerpo', 'fecpub', 'fecmax', 'ocultar', 'adjunto', 'evaluar'];

     protected $useTimestamps = false;
     protected $dateFormat    = 'datetime';

     public function guardar(array $params)
     {
          $evaluar = in_array($params['tipo'], array('E', 'T'));
          $auvRespNotaModel = new AuvRespNotaModel();
          try {
               $this->db->transBegin();
               $codigo = $this->insert(array(
                    'grupo'   => $params['grupo'],
                    'titulo'  => $params['titulo'],
                    'tipo'    => $params['tipo'],
                    'cuerpo'  => $params['cuerpo'],
                    'fecpub'  => $params['fecpub'],
                    'fecmax'  => !empty($params['fecmax']) ? $params['fecmax'] : null,
                    'adjunto' => $params['adjunto'],
                    'evaluar' => $evaluar ? 'S' : null
               ), true);
               if ($evaluar) {
                    $auvRespNotaModel->insertarNotasBloque(array('coditem' => $codigo, 'grupo' => $params['grupo']));
               }
               $this->db->transCommit();
               return $codigo;
          } catch (\Exception $ex) {
               $this->db->transRollback();
               throw new \Exception($ex->getMessage());
          }
     }

     public function obtenerDatosItem($coditem)
     {
          $query = $this->db->table('auv_grupo_item agi')
               ->select("agi.codigo, agi.grupo, agi.titulo, ag.curso, ag.periodo, ag.salon, c.nombre, dt.descripcion AS tipodes")
               ->join('auv_grupo ag', "ag.codigo = agi.grupo", "INNER")
               ->join('curso c', "c.codcur = ag.curso", "LEFT")
               ->join('datosdet dt', "dt.coddat = '014' and dt.coddet = agi.tipo", 'LEFT')
               ->where('agi.codigo', $coditem);
          $result = $query->get();
          return $result->getRowArray();
     }

     public function listarItems($params = array())
     {
          $auvGrupoAdjModel = new AuvGrupoAdjModel();
          $auvRespAdjModel = new AuvRespAdjModel();
          $auvRespNotaModel = new AuvRespNotaModel();
          $modoAlumno = isset($params['codalu']);
          $query = $this->db->table('auv_grupo_item aip')
               ->select(array(
                    "aip.*",
                    new RawSql("(CASE WHEN aip.tipo = 'T' THEN CASE WHEN CURRENT_TIMESTAMP > aip.fecmax THEN 'S' ELSE 'N' END
                    ELSE NULL END) AS fecha_vencidad")
               ));
          if (isset($params['grupo'])) {
               $query->where('aip.grupo', $params['grupo']);
          }
          if (isset($params['tipo'])) {
               $query->where('aip.tipo', $params['tipo']);
          }
          if ($modoAlumno) {
               $query->where(new RawSql("aip.fecpub < NOW()"));
          }
          $result = $query->get()->getResultArray();
          foreach ($result as &$item) :
               $item['adjuntos'] = $auvGrupoAdjModel->obtenerAdjuntosAuvGrupoItem($item['codigo']);
               if ($modoAlumno) {
                    $item['respuesta'] = $auvRespNotaModel->obtenerDetalle(array('coditem' => $item['codigo'], 'codalu' => $params['codalu']));
               }
          endforeach;
          return $result;
     }
}
