<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class MatriculaController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $salonModel = new Models\SalonModel();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $viewData->set('anioVigente', ANIO);
      $viewData->set('anioMatricula', $anioModel->getAnioMatricula());
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSalones', $salonModel->listarSalonesComboBox(array('anio' => ANIO)));
      return view('academico/matricula/index', $viewData->get());
   }

   public function registro()
   {
      $viewData = new ViewData();
      $alumnoModel = new Models\AlumnoModel();
      $viewData->isAjax(true);
      $viewData->set('listaAlumnosNoMatriculados', $alumnoModel->listarAlumnosNoMatriculados());
      return view('academico/matricula/matricula', $viewData->get());
   }
}
