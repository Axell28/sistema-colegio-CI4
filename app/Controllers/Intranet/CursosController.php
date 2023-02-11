<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class CursosController extends BaseController
{

   public $anio;
   public $esAlumno;
   public $esDocente;

   public function __construct()
   {
      $this->anio = session()->get('anio');
      $codigo = session()->get('codigo');
      $alumnoModel = new Models\AlumnoModel();
      $empleadoMdeol = new Models\EmpleadoModel();
      $this->esAlumno = $alumnoModel->verificarAlumno($codigo);
      $this->esDocente = $empleadoMdeol->verificarDocente($codigo);
   }

   public function index()
   {
      $viewData = new ViewData();
      $curriculoModel = new Models\CurriculoModel();
      $matriculaModel = new Models\MatriculaModel();
      $params = array('anio' => ANIO);
      if (!SUPER_ADMIN) {
         if ($this->esDocente) {
            $params['codemp'] = CODIGO;
         } else if ($this->esAlumno) {
            $datosMatricula = $matriculaModel->listarRegistroMatricula(array(
               'anio' => $this->anio,
               'codalu' => CODIGO
            ));
            $params['salon'] = $datosMatricula['salon'];
         }
      }
      $viewData->set('listaCursosIntranet', $curriculoModel->listarCursosIntranet($params));
      return view('intranet/cursos/index', $viewData->get());
   }

   public function classroom($salon = null, $curso = null)
   {
      $viewData = new ViewData();
      $cursoModel = new Models\CursoModel();
      $anioPeriodo = new Models\AnioPeriodoModel();
      $asigCursoModel = new Models\AsigCursoModel();
      $auvCursoGrupoModel = new Models\AuvCursoGrupoModel();
      $cursoNombre = $cursoModel->select('nombre')->find($curso)['nombre'];
      $cursoEncargado = $asigCursoModel->obtenerDatosAsignacionCurso(array(
         'anio' => $this->anio,
         'salon' => $salon,
         'curso' => $curso
      ));
      $viewData->set('anio', $this->anio);
      $viewData->set('salon', $salon);
      $viewData->set('curso', $curso);
      $viewData->set('cursoNombre', $cursoNombre);
      $viewData->set('cursoEncargado', !empty($cursoEncargado) ? $cursoEncargado['docentenom'] : 'NO ASIGNADO');
      $viewData->set('listaPeriodos', $anioPeriodo->listarPeridosxAnio($this->anio));
      $viewData->set('listaAuvGrupos', $auvCursoGrupoModel->listarAuvGruposxPeriodo(array(
         'salon' => $salon,
         'curso' => $curso
      )));
      return view('intranet/cursos/classroom', $viewData->get());
   }

   public function auvgrupo()
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $auvCursoGrupoModel = new Models\AuvCursoGrupoModel();
      $viewData->set('anio', $this->anio);
      $viewData->set('codigo', $this->request->getPost('codigo'));
      $viewData->set('action', $this->request->getPost('action'));
      $viewData->set('periodo', $this->request->getPost('periodo'));
      $viewData->set('salon', $this->request->getPost('salon'));
      $viewData->set('curso', $this->request->getPost('curso'));
      $viewData->set('datosGrupo', $auvCursoGrupoModel->find($this->request->getPost('codigo')));
      return view('intranet/cursos/auv-grupo', $viewData->get());
   }

   public function auvGrupoItems()
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $viewData->set('listaTiposItems', array(
         'N' => 'Notificación',
         'A' => 'Actividad',
         'T' => 'Tarea'
      ));
      return view('intranet/cursos/auv-items', $viewData->get());
   }

   public function auvEditor($codigo = null)
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $viewData->set('listaTiposItems', array(
         'N' => 'Notificación',
         'A' => 'Actividad',
         'T' => 'Tarea'
      ));
      $viewData->set('grupo', $this->request->getPost('grupo'));
      return view('intranet/cursos/auv-editor', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $auvCursoGrupoModel = new Models\AuvCursoGrupoModel();
      try {
         switch ($caso):
            case 'save-grupo':
               $action = $this->request->getPost('action');
               $params = array(
                  'action' => $this->request->getPost('action'),
                  'codigo' => $this->request->getPost('codigo'),
                  'salon' => $this->request->getPost('salon'),
                  'curso' => $this->request->getPost('curso'),
                  'periodo' => $this->request->getPost('periodo'),
                  'titulo' => $this->request->getPost('titulo'),
                  'ocultar' => $this->request->getPost('ocultar')
               );
               $codgrupo = $auvCursoGrupoModel->guardarAuvGrupo($params, $action);
               $jsonData->set('listaAuvGrupos', $auvCursoGrupoModel->listarAuvGrupos(array(
                  'salon' => $params['salon'],
                  'curso' => $params['curso'],
                  'periodo' => $params['periodo']
               )));
               break;
            case 'eliminar-grupo':
               $codigo = $this->request->getGet('codigo');
               $auvCursoGrupoModel->where(array('codigo' => intval($codigo)))->delete();
               $jsonData->set('listaAuvGrupos', $auvCursoGrupoModel->listarAuvGrupos(array(
                  'salon' => $this->request->getPost('salon'),
                  'curso' => $this->request->getPost('curso'),
                  'periodo' => $this->request->getPost('periodo')
               )));
               break;
            case 'guardar-item':

               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
