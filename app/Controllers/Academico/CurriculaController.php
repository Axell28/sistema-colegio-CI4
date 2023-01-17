<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class CurriculaController extends BaseController
{

   public $curriculoModel;

   public function __construct()
   {
      $this->curriculoModel = new Models\CurriculoModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $nivelModel = new Models\NivelModel();
      $anioModel = new Models\AnioModel();
      $viewData->set('anio', ANIO);
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaNivelGrados', $nivelModel->listarNivelGrados());
      return view('academico/plan-curricular/index', $viewData->get());
   }

   public function asignacion()
   {
      $viewData = new ViewData();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $cursoModel = new Models\CursoModel();
      try {
         $anio = $this->request->getPost('anio');
         $nivel = $this->request->getPost('nivel');
         $grado = $this->request->getPost('grado');
         $action = $this->request->getPost('action');
         $curso = $this->request->getPost('curso');
         $curpad = $this->request->getPost('curpad');
         $interno = $this->request->getPost('interno');
         $dataCurricula = array();
         if ($action == 'E') {
            $dataCurricula = $this->curriculoModel->obtenerDataCurricula(array(
               'anio' => $anio,
               'nivel' => $nivel,
               'grado' => $grado,
               'curso' => $curso
            ));
         }
         $viewData->set('anio', $anio);
         $viewData->set('nivel', $nivel);
         $viewData->set('grado', $grado);
         $viewData->set('curso', $interno == 'S' ? $curpad : $curso);
         $viewData->set('cursoI', $interno == 'S' ? $curso : '-');
         $viewData->set('action', $action);
         $viewData->set('interno', $interno);
         $viewData->set('listaNiveles', $nivelModel->listarNiveles());
         $viewData->set('listaGrados', $gradoModel->listarGradosPorNivel($nivel));
         $viewData->set('listaCursos', $cursoModel->listarCursos(array('interno' => 'N')));
         $viewData->set('listaCurInt', $cursoModel->listarCursos(array('interno' => 'S')));
         $viewData->set('dataCurricula', $dataCurricula);
         $viewData->isAjax(true);
         return view('academico/plan-curricular/asignacion', $viewData->get());
      } catch (\Exception $ex) {
         return $this->response->setStatusCode(401, $ex->getMessage());
      }
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $anio  = $this->request->getPost('anio');
      $nivel = $this->request->getPost('nivel');
      $grado = $this->request->getPost('grado');
      $tipo  = $this->request->getPost('tipo');
      try {
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $curso  = $this->request->getPost('curso');
               $cursoI = $this->request->getPost('cursoI');
               $values = array(
                  'tipcal'    => $this->request->getPost('tipcal'),
                  'orden'     => $this->request->getPost('orden'),
                  'horas'     => $this->request->getPost('horas'),
                  'nota_min'  => $this->request->getPost('nota_min'),
                  'nota_max'  => $this->request->getPost('nota_max'),
                  'nota_min_aprob' => $this->request->getPost('nota_min_aprob')
               );
               if ($action == 'I') {
                  $existeRegistro = $this->curriculoModel->where(array('anio' => $anio, 'nivel' => $nivel, 'grado' => $grado, 'curso' => !empty($cursoI) ? $cursoI : $curso))->first();
                  if (!empty($existeRegistro)) {
                     throw new \Exception('El curso ya se encuentra asignado para este nivel y grado');
                  }
                  if (!empty($cursoI)) {
                     $values['anio']  = $anio;
                     $values['nivel'] = $nivel;
                     $values['grado'] = $grado;

                     $existeRegistro = $this->curriculoModel->checkExistenciaCurso(array('anio' => $anio, 'nivel' => $nivel, 'grado' => $grado, 'curso' => $curso));
                     if (empty($existeRegistro)) {
                        $values['curso'] = $curso;
                        $this->curriculoModel->insert($values);
                     }
                     $values['curso'] = $cursoI;
                     $values['curpad'] = $curso;
                     $this->curriculoModel->insert($values);
                  } else {
                     $values['anio']  = $anio;
                     $values['nivel'] = $nivel;
                     $values['grado'] = $grado;
                     $values['curso'] = $curso;
                     $this->curriculoModel->insert($values);
                  }
               } else if ($action == 'E') {
                  $where = array('anio' => $anio, 'nivel' => $nivel, 'grado' => $grado, 'curso' => $curso);
                  $this->curriculoModel->set($values)->where($where)->update();
               }
               break;
         endswitch;
         if ($caso !== 'listar_ng') {
            $jsonData->set('listaCurriculoNG', $this->curriculoModel->listarCurriculaNG(
               array(
                  'anio'  => $anio,
                  'nivel' => $nivel,
                  'grado' => $grado,
                  'tipo'  => $tipo
               )
            ));
         }
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
