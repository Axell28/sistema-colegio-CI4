<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class CursoModel extends Model
{
   protected $table      = 'curso';
   protected $primaryKey = 'codcur';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['nombre', 'curabr', 'interno'];
   protected $useTimestamps = false;

   public function listarCursos($params = array())
   {
      $query = $this->select(
         array(
            'codcur',
            'nombre',
            'curabr',
            new RawSql("(CASE WHEN COALESCE(interno, 'N') = 'S' THEN 'SI' ELSE 'NO' END) AS interno")
         )
      );
      if (isset($params['interno'])) {
         $query->where(new RawSql("COALESCE(interno, 'N') = '" . $params['interno'] . "'"));
      }
      return $query->findAll();
   }

   public function eliminarCurso($codcur)
   {
      // validación si el curso esta en uso
      $curriculoModel = new CurriculoModel();
      $result = $curriculoModel->checkExistenciaCurso(array('curso' => $codcur));
      if (empty($result)) {
         $this->delete($codcur);
         return true;
      }
      return false;
   }
}
