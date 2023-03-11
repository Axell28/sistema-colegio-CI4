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
      $notasModel = new Models\NotasModel();
      $salonModel = new Models\SalonModel();
      $curriculoModel = new Models\CurriculoModel();
      $matriculaModel = new Models\MatriculaModel();
      $anioPeriodoModel = new Models\AnioPeriodoModel();
      $listaPeriodos = $anioPeriodoModel->listarPeridosCombo(['anio' => ANIO]);
      $viewData->set('listaPeriodos', $listaPeriodos);
      $viewData->set('totalPeriodos', count($listaPeriodos));
      if ($this->esAlumno) {
         $datosCurricula = array();
         $datosMatricula = $matriculaModel->obtenerDatosMatricula(array('anio' => ANIO, 'codalu' => CODIGO));
         $datosMatricula = isset($datosMatricula[0]) ? $datosMatricula[0] : array();
         if (!empty($datosMatricula)) {
            $datosCurricula = $curriculoModel->listarCurriculaNG(array(
               'anio' => ANIO,
               'nivel' => $datosMatricula['nivel'],
               'grado' => $datosMatricula['grado'],
               'sin_area' => true
            ));
            $datosCalificaciones = $notasModel->listarCalificacionesAlumno(array(
               'salon'  => $datosMatricula['salon'],
               'codalu' => CODIGO
            ));
            foreach ($datosCurricula as &$value) {
               $curso = $value['curso'];
               $value['notas'] = isset($datosCalificaciones[$curso]) ? $datosCalificaciones[$curso] : array();
            }
         }
         $viewData->set('listaCurricula', $datosCurricula);
         return view('intranet/calificaciones/index_alu', $viewData->get());
      } else {
         $viewData->set('listaSalones', $salonModel->listarSalonesComboBox(['anio' => ANIO, 'codemp' => CODIGO]));
         $viewData->set('listaCursosCurricula', $curriculoModel->listarCursosCurriculaCombo(['anio' => ANIO, 'codemp' => CODIGO]));
         return view('intranet/calificaciones/index', $viewData->get());
      }
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $notasModel = new Models\NotasModel();;
      try {
         switch ($caso):
            case 'update-nota':
               $campo = $this->request->getPost('campo');
               $valor = $this->request->getPost('valor');
               $codigo = $this->request->getPost('codigo');
               $notasModel->actualizarNotas(array('campo' => $campo, 'valor' => $valor, 'codigo' => $codigo));
               break;
         endswitch;
         $jsonData->set('perdes', Funciones::numeroTipoPeriodo($this->request->getPost('fperiodo')));
         $jsonData->set('listaCalificacion', $notasModel->listarCalificaciones(array(
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
