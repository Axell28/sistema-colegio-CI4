<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvRespNotaModel extends Model
{
   protected $table      = 'auv_resp_nota';
   protected $primaryKey = 'coditem';

   protected $useAutoIncrement = false;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['salon', 'coditem', 'codalu', 'fecenv', 'comentalu', 'nota', 'fecreg', 'fecmod', 'revisado'];

   protected $useTimestamps = false;
   protected $dateFormat    = 'datetime';

   public function insertarNotasBloque(array $params)
   {
      $auvGrupoModel = new AuvGrupoModel();
      $salon = $auvGrupoModel->select('salon')->find($params['grupo']);
      try {
         $this->db->query("INSERT INTO auv_resp_nota (salon, coditem, codalu, fecreg) SELECT m.salon, ?, m.codalu, NOW() 
            FROM matricula m INNER JOIN salon s ON s.salon = m.salon WHERE s.salon = ?", [$params['coditem'], $salon]);
      } catch (\Exception $ex) {
         throw new \Exception($ex->getMessage());
      }
   }

   public function eliminarRespuesta($salon, $alucod)
   {
      $auvRespAdjModel = new AuvRespAdjModel();
      $this->where(array('salon' => $salon, 'alucod' => $alucod))->delete();
      $auvRespAdjModel->where(array('salon' => $salon, 'alucod' => $alucod))->delete();
   }

   public function actualizarNotasBloque(array $set, array $where, array $procedure)
   {
      try {
         $this->db->transBegin();
         $this->set($set)
            ->where($where)
            ->update();
         $this->db->query("CALL actualizar_registro_notas(?, ?, ?);", $procedure);
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }

   public function listarRespuestasxSalon(array $params)
   {
      $auvRespAdjModel = new AuvRespAdjModel();
      $query = $this->db->table('auv_resp_nota arn')
         ->select(array(
            "arn.codalu",
            "CONCAT(p.apepat, ' ', p.apemat, ' ', p.nombres) AS nomcomp",
            "(CASE WHEN arn.fecenv IS NULL THEN 'N' ELSE 'S' END) AS enviado",
            "arn.nota",
            "arn.comentalu",
            "COALESCE(DATE_FORMAT(arn.fecenv, '%d/%m/%Y %h:%i %p'), '-') AS fecenv",
            "arn.revisado"
         ))
         ->join("alumno a", "a.codalu = arn.codalu", "INNER")
         ->join("persona p", "p.codper = a.codper", "LEFT")
         ->where('arn.coditem', $params['coditem']);

      if (isset($params['enviados']) && !empty($params['enviados'])) {
         if ($params['enviados'] == "E") {
            $query->where(new RawSql("arn.fecenv IS NOT NULL"));
         } else {
            $query->where(new RawSql("arn.fecenv IS NULL"));
         }
      }

      $query->orderBy('p.apepat, p.apemat');
      $result = $query->get()->getResultArray();
      foreach ($result as &$value) :
         $value['adjuntos'] = $auvRespAdjModel->listarRespuestasAdj(array('coditem' => $params['coditem'], 'codalu' => $value['codalu']));
      endforeach;
      return $result;
   }

   public function listarEvaluacionesPendientes($codalu)
   {
      $query = $this->db->table('auv_resp_nota rn')
         ->select(array(
            "rn.coditem",
            "ag.titulo",
            "rn.codalu",
            "ag2.salon",
            "ag2.curso",
            "cu.nombre AS curnom",
            "ag2.periodo",
            "ag.tipo",
            "ag.fecmax"
         ))
         ->join("auv_grupo_item ag", "ag.codigo = rn.coditem", "INNER")
         ->join("auv_grupo ag2", "ag2.codigo = ag.grupo", "INNER")
         ->join("curso cu", "cu.codcur = ag2.curso", "LEFT")
         ->where('rn.codalu', $codalu)
         ->whereIn('ag.tipo', array('T', 'E'))
         ->where("COALESCE(rn.revisado, 'N')", 'N')
         ->where(new RawSql("NOW() BETWEEN ag.fecpub AND ag.fecmax"))
         ->where(new RawSql("rn.fecenv IS NULL"))
         ->orderBy('ag.fecmax', 'DESC')
         ->limit(5);
      $result = $query->get()->getResultArray();
      return $result;
   }

   public function obtenerDetalle(array $where)
   {
      $queryR = $this->select("*, DATE_FORMAT(fecenv, '%d/%m/%Y a las %h:%i %p') AS fecenv_format")->where($where)->where('fecenv IS NOT NULL')->first();
      return $queryR;
   }

   public function existeRegistros($coditem)
   {
      $query = $this->select()->where('coditem', $coditem)->where('fecenv IS NOT NULL')->findAll();
      return empty($query);
   }
}
