<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class MatriculaController extends BaseController
{

   public $matriculaModel;

   public function __construct()
   {
      $this->matriculaModel = new Models\MatriculaModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $salonModel = new Models\SalonModel();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $anioMatricula = $anioModel->getAnioMatricula();
      $viewData->set('anioVigente', ANIO);
      $viewData->set('anioMatricula', $anioMatricula);
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSalones', $salonModel->listarSalonesComboBox(array('anio' => ANIO)));
      $viewData->set('listaRegistroMatricula', $this->matriculaModel->listarRegistroMatricula(array('anio' => $anioMatricula)));
      return view('academico/matricula/index', $viewData->get());
   }

   public function registro()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $anioModel = new Models\AnioModel();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $seccionModel = new Models\SeccionModel();
      $alumnoModel = new Models\AlumnoModel();
      $viewData->set('listaCondicion', $datosModel->listarDatos('012'));
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSecciones', $seccionModel->listarSecciones());
      $viewData->set('listaAnioMatricula', $anioModel->listarAniosMatricula());
      $viewData->set('listaFamiliarResponsable', $alumnoModel->listarFamResponsable());
      $viewData->set('listaAlumnosNoMatriculados', $alumnoModel->listarAlumnosNoMatriculados());
      $viewData->set('listaHistorialMatricula', $this->matriculaModel->obtenerDatosMatricula(array(), true));
      return view('academico/matricula/registro', $viewData->get());
   }

   public function json($case)
   {
      $jsonData = new JsonData();
      $salonModel = new Models\SalonModel();
      $alumnoModel = new Models\AlumnoModel();
      try {
         $anioF = $this->request->getPost('anioF');
         $nivelF = $this->request->getPost('nivelF');
         $gradoF = $this->request->getPost('gradoF');
         switch ($case):
            case 'listar':
               $jsonData->set('listaRegistroMatricula', $this->matriculaModel->listarRegistroMatricula(array(
                  'anio'  => $anioF,
                  'nivel' => $nivelF,
                  'grado' => $gradoF
               )));
               break;
            case 'save-matricula':
               $nivel   = $this->request->getPost('nivel');
               $grado   = $this->request->getPost('grado');
               $seccion = $this->request->getPost('seccion');
               $salonDest = $salonModel->obtenerDatosSalonxNGS(array(
                  'anio'    => $this->request->getPost('anio'),
                  'nivel'   => $nivel,
                  'grado'   => $grado,
                  'seccion' => $seccion
               ));

               if (empty($salonDest) || is_null($salonDest)) {
                  throw new \Exception("Error! Debe registrar un salón con el NIVEL, GRADO Y SECCIÓN correspondiente.");
               }

               $values  = array(
                  'anio'   => $this->request->getPost('anio'),
                  'codalu' => $this->request->getPost('alumno'),
                  'fecmat' => $this->request->getPost('fecmat'),
                  'salon'  => $salonDest['salon'],
                  'condicion' => $this->request->getPost('condicion')
               );

               $existeMatricula = $this->matriculaModel->verificarMatriculaNG(array('nivel' => $nivel, 'grado' => $grado, 'codalu' => $values['codalu']));

               if (!empty($existeMatricula)) {
                  throw new \Exception("Error! Existe registros de matricula de este estudiante con el mismo nivel y grado");
               }

               $codMatricula = $this->matriculaModel->registrarMatricula($values);

               $jsonData->set('codmat', $codMatricula);
               $jsonData->set('listaAlumnosNoMatriculados', $alumnoModel->listarAlumnosNoMatriculados());
               break;
            case 'eliminar':
               $codmat = $this->request->getPost('codmat');
               $codalu = $this->request->getPost('codalu');
               $this->matriculaModel->eliminarMatricula($codmat);
               $alumnoModel->set('matricula', null)->update($codalu);
               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
