<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\Reportes;
use App\Controllers\BaseController;

class ReporteController extends BaseController
{

   public function generate()
   {
      $params = $this->request->getPost();
      $codrep = $params['codrep'];
      $reporte = null;
      switch ($codrep) {
         case '0001':
            $reporte = new Reportes\ReporteDeUsuarios();
            break;
         case '0002':
            $reporte = new Reportes\ReporteEmpleados();
            break;
         case '003':
            $reporte = new Reportes\ReporteAlumnos();
            break;
         case '004':
            $reporte = new Reportes\ConstanciaMatricula();
            break;
         case '005':
            $reporte = new Reportes\FichaMatricula();
            break;
      }
      $reporte->execute($params);
   }
}
