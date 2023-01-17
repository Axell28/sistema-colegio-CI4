<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class SalonesController extends BaseController
{
   public $salonModel;

   public function __construct()
   {
      $this->salonModel = new Models\SalonModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $nivelModel = new Models\NivelModel();
      $viewData->set('anioVigente', $anioModel->getAnioVigente());
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaSalones', $this->salonModel->listarSalones(array(
         'anio' => ANIO
      )));
      return view('academico/salones/index', $viewData->get());
   }

   public function registro()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $seccionModel = new Models\SeccionModel();
      $empleadoModel = new Models\EmpleadoModel();
      $viewData->isAjax(true);
      $salon = $this->request->getPost('salon');
      $datosSalon = $this->salonModel->obtenerDatosSalon($salon);
      $viewData->set('anio', $this->request->getPost('anio'));
      $viewData->set('action', $this->request->getPost('action'));
      $viewData->set('modalidades', $datosModel->listarDatos('001'));
      $viewData->set('turnos', $datosModel->listarDatos('002'));
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSecciones', $seccionModel->listarSecciones());
      $viewData->set('listaDocentes', $empleadoModel->listarComboEmpleados(array('docentes' => true)));
      $viewData->set('listaEmpleados', $empleadoModel->listarComboEmpleados());
      $viewData->set('datosSalon', $datosSalon);
      return view('academico/salones/registro', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         $filter_anio = $this->request->getPost('filter_anio');
         $filter_nivel = $this->request->getPost('filter_nivel');
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $salon = $this->request->getPost('salon');
               $anio = $this->request->getPost('anio');

               if ($action == 'I') {
                  $existeSalon = $this->salonModel->existeSalon(array(
                     'anio' => $anio,
                     'nivel' => $this->request->getPost('nivel'),
                     'grado' => $this->request->getPost('grado'),
                     'seccion' => $this->request->getPost('seccion')
                  ));
                  if ($existeSalon) {
                     throw new \Exception("Ya existe un salón registrado con el mismo nivel, grado y sección");
                  }
               }

               $values = array(
                  'aula' => $this->request->getPost('aula'),
                  'nombre' => $this->request->getPost('nombre'),
                  'nivel' => $this->request->getPost('nivel'),
                  'grado' => $this->request->getPost('grado'),
                  'seccion' => $this->request->getPost('seccion'),
                  'tutor' => $this->request->getPost('tutor'),
                  'cotutor' => $this->request->getPost('cotutor'),
                  'coordinador' => $this->request->getPost('coordinador'),
                  'vacantes' => $this->request->getPost('vacantes'),
                  'modalidad' => $this->request->getPost('modalidad'),
                  'turno' => $this->request->getPost('turno')
               );
               if ($action == 'I') {
                  $values['anio'] = $anio;
                  $values['salon'] = $this->salonModel->generarCodigo($anio);
                  $this->salonModel->insert($values);
               } else if ($action == 'E') {
                  $this->salonModel->set($values)->update($salon);
               }
               break;
            case 'eliminar':
               $salon = $this->request->getPost('salon');
               $this->salonModel->delete($salon);
               break;
         endswitch;
         $jsonData->set('listaSalones', $this->salonModel->listarSalones(array(
            'anio'  => $filter_anio,
            'nivel' => $filter_nivel
         )));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(400);
      }
   }
}
