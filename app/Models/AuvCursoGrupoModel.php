<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class AuvCursoGrupoModel extends Model
{
   protected $table      = 'auv_cur_grupo';
   protected $primaryKey = 'codigo';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codigo', 'salon', 'curso', 'periodo', 'titulo', 'ocultar', 'fecreg', 'usureg', 'fecmod', 'usumod'];

   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function guardarAuvGrupo(array $params, $action = 'I')
   {
      $codigo = $params['codigo'];
      if ($action == 'I') {
         $codigo = $this->insert(array(
            'salon'   => $params['salon'],
            'curso'   => $params['curso'],
            'periodo' => $params['periodo'],
            'titulo'  => $params['titulo'],
            'ocultar' => $params['ocultar'],
            'usureg'  => USUARIO
         ), true);
      } else {
         $this->set(array(
            'titulo'  => $params['titulo'],
            'ocultar' => $params['ocultar'],
            'usumod'  => USUARIO
         ))->update($codigo);
      }
      return $codigo;
   }

   public function listarAuvGrupos(array $params)
   {
      $query = $this->db->table('auv_cur_grupo acg')->select();
      if (isset($params['salon'])) {
         $query->where('acg.salon', $params['salon']);
      }
      if (isset($params['curso'])) {
         $query->where('acg.curso', $params['curso']);
      }
      if (isset($params['periodo'])) {
         $query->where('acg.periodo', $params['periodo']);
      }
      $query->orderBy('codigo', 'DESC');
      $result = $query->get();
      return $result->getResultArray();
   }

   public function listarAuvGruposxPeriodo(array $params)
   {
      $query = $this->db->table('auv_cur_grupo acg')->select();
      if (isset($params['salon'])) {
         $query->where('acg.salon', $params['salon']);
      }
      if (isset($params['curso'])) {
         $query->where('acg.curso', $params['curso']);
      }
      if (isset($params['periodo'])) {
         $query->where('acg.periodo', $params['periodo']);
      }
      $query->orderBy('codigo', 'DESC');
      $result = $query->get()->getResultArray();
      $newResult = array();
      foreach ($result as $value) {
         $newResult['P' . $value['periodo']][] = $value;
      }
      return $newResult;
   }
}
