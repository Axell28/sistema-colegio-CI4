<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class AsignacionCursoController extends BaseController
{

   public $asignacionCursoModel;

   public function __construct()
   {
      $this->asignacionCursoModel = new Models\AsigCursoModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $nivelModel = new Models\NivelModel();
      $cursoModel = new Models\CursoModel();
      $gradoModel = new Models\GradoModel();
      $seccionModel = new Models\SeccionModel();
      $empleadoModel = new Models\EmpleadoModel();
      $curriculoModel = new Models\CurriculoModel();
      $viewData->set('anio', ANIO);
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaCursos', $cursoModel->listarCursos());
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSecciones', $seccionModel->listarSecciones());
      $viewData->set('listaDocentes', $empleadoModel->listarComboEmpleados(array('docentes' => true)));
      $viewData->set('listaCursosAsignados', $curriculoModel->listarCursosAsignados(array('anio' => ANIO)));
      return view('academico/asignacion-curso/index', $viewData->get());
   }

   public function asignacion()
   {
      $viewData = new ViewData();
      $salonModel = new Models\SalonModel();
      $cursoModel = new Models\CursoModel();
      $empleadoModel = new Models\EmpleadoModel();
      $viewData->isAjax(true);
      $anio = $this->request->getPost('anio');
      $salon = $this->request->getPost('salon');
      $viewData->set('salon', $salon);
      $viewData->set('codcur', $this->request->getPost('curso'));
      $viewData->set('docente', $this->request->getPost('docente'));
      $viewData->set('listaCursos', $cursoModel->listarCursos());
      $viewData->set('listaSalones', $salonModel->listarSalonesComboBox(array('anio' => $anio)));
      $viewData->set('listaDocentes', $empleadoModel->listarComboEmpleados(array('docentes' => true)));
      return view('academico/asignacion-curso/asignacion', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $curriculoModel = new Models\CurriculoModel();
      try {
         $anioF = $this->request->getPost('anioF');
         $nivelF = $this->request->getPost('nivelF');
         $gradoF = $this->request->getPost('gradoF');
         $seccionF = $this->request->getPost('seccionF');
         $cursoF = $this->request->getPost('cursoF');
         $docenteF = $this->request->getPost('docenteF');
         switch ($caso):
            case 'guardar':
               $values = array(
                  'salon' => $this->request->getPost('salon'),
                  'curso' => $this->request->getPost('curso'),
                  'docente' => $this->request->getPost('docente')
               );
               $this->asignacionCursoModel->guardarAsignacion($values);
               break;
         endswitch;
         $jsonData->set('listaCursosAsignados', $curriculoModel->listarCursosAsignados(array(
            'anio'  => $anioF,
            'nivel' => $nivelF,
            'grado' => $gradoF,
            'curso' => $cursoF,
            'docente' => $docenteF
         )));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
