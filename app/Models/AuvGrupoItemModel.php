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

     protected $allowedFields = ['codigo', 'grupo', 'titulo', 'tipo', 'cuerpo', 'fecpub', 'ocultar', 'adjunto'];

     protected $useTimestamps = false;
     protected $dateFormat    = 'datetime';

     public function guardar(array $params, $action = "I")
     {
          try {

               $codigo = $this->insert(array(
                    'grupo'   => $params['grupo'],
                    'titulo'  => $params['titulo'],
                    'tipo'    => $params['tipo'],
                    'cuerpo'  => $params['cuerpo'],
                    'fecpub'  => $params['fecpub'],
                    'fecmax'  => !empty($params['fecmax']) ? $params['fecmax'] : null,
                    'adjunto' => $params['adjunto']
               ), true);
               return $codigo;
          } catch (\Exception $ex) {
               throw new \Exception($ex->getMessage());
          }
     }

     public function listarItems($params = array())
     {
          $auvGrupoAdjModel = new AuvGrupoAdjModel();
          $auvRespAdjModel = new AuvRespAdjModel();
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
               if($modoAlumno) {
                    $item['respuesta'] = $auvRespAdjModel->listarRespuestasAdj(array(
                         'coditem' => $item['codigo'],
                         'codalu'  => $params['codalu'] 
                    ));
               }
          endforeach;
          return $result;
     }
}
