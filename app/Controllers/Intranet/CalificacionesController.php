<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class CalificacionesController extends BaseController
{
   public $esAlumno;

   public function __construct()
   {
      $codigo = session()->get('codigo');
      $alumnoModel = new Models\AlumnoModel();
      $this->esAlumno = $alumnoModel->verificarAlumno($codigo);
   }

   public function index()
   {
      $viewData = new ViewData();
      $salonModel = new Models\SalonModel();
      $anioPeriodoModel = new Models\AnioPeriodoModel();
      $curriculoModel = new Models\CurriculoModel();
      $viewData->set('listaPeriodos', $anioPeriodoModel->listarPeridosCombo(['anio' => ANIO]));
      $viewData->set('listaSalones', $salonModel->listarSalonesComboBox(['anio' => ANIO]));
      $viewData->set('listaCursosCurricula', $curriculoModel->listarCursosCurriculaCombo(['anio' => ANIO]));
      if ($this->esAlumno) {
         return view('intranet/calificaciones/index_alu', $viewData->get());
      } else {
         return view('intranet/calificaciones/index', $viewData->get());
      }
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $alumnoModel = new Models\AlumnoModel();
      $calificacionModel = new Models\CalificacionModel();
      try {
         switch ($caso): endswitch;
         $jsonData->set('perdes', Funciones::numeroTipoPeriodo($this->request->getPost('fperiodo')));
         $jsonData->set('listaCalificacion', $calificacionModel->listarCalificacion(array(
            'salon' => $this->request->getPost('fsalon'),
            'curso' => $this->request->getPost('fcurso'),
            'periodo' => $this->request->getPost('fperiodo')
         )));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
