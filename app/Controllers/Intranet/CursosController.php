<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class CursosController extends BaseController
{

   public $anio;
   public $esAlumno;
   public $esDocente;
   public $esAdmin;
   public $pathAdjuntosGrupo;
   public $pathRespuestaAdjunto;

   public function __construct()
   {
      $this->anio = session()->get('anio');
      $codigo = session()->get('codigo');
      $alumnoModel = new Models\AlumnoModel();
      $empleadoModel = new Models\EmpleadoModel();
      $this->esAlumno = $alumnoModel->verificarAlumno($codigo);
      $this->esDocente = $empleadoModel->verificarDocente($codigo);
      $this->esAdmin = session()->get('perfil') == '001';
      $this->pathAdjuntosGrupo = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'auv' . DIRECTORY_SEPARATOR . 'adjuntos';
      $this->pathRespuestaAdjunto = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'auv' . DIRECTORY_SEPARATOR . 'respuesta';
   }

   public function index()
   {
      $viewData = new ViewData();
      $curriculoModel = new Models\CurriculoModel();
      $matriculaModel = new Models\MatriculaModel();
      $params = array('anio' => ANIO);
      $listaCursosIntranet = array();
      if (!SUPER_ADMIN) {
         if ($this->esDocente) {
            $params['codemp'] = CODIGO;
         } else if ($this->esAlumno) {
            $datosMatricula = $matriculaModel->listarRegistroMatricula(array(
               'anio' => $this->anio,
               'codalu' => CODIGO
            ));
            if(!empty($datosMatricula)) {
               $params['salon'] = $datosMatricula['salon'];
               $listaCursosIntranet = $curriculoModel->listarCursosIntranet($params);
            }
         }
      } else {
         $listaCursosIntranet = $curriculoModel->listarCursosIntranet($params);
      }
      $viewData->set('esAlumno', $this->esAlumno);
      $viewData->set('listaCursosIntranet', $listaCursosIntranet);
      return view('intranet/cursos/index', $viewData->get());
   }

   public function enviados($salon, $codigo = null)
   {
      $viewData = new ViewData();
      $auvGrupoItemModel = new Models\AuvGrupoItemModel();
      $auvRespNotaModel = new Models\AuvRespNotaModel();
      $salonModel = new Models\SalonModel();
      $datosSalon = $salonModel->obtenerDatosSalon($salon);
      $datosItemAuv = $auvGrupoItemModel->obtenerDatosItem($codigo);
      $listaRespuestas = $auvRespNotaModel->listarRespuestasxSalon(array('salon' => $salon, 'coditem' => $codigo));
      $viewData->set('coditem', $codigo);
      $viewData->set('listaRespuestas', $listaRespuestas);
      $viewData->set('salonNombre', $datosSalon['nombre']);
      $viewData->set('datosItemAuv', $datosItemAuv);
      return view('intranet/cursos/enviados', $viewData->get());
   }

   public function respuesta()
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $viewData->set('salon', $this->request->getPost('salon'));
      $viewData->set('grupo', $this->request->getPost('grupo'));
      $viewData->set('auvitem', $this->request->getPost('auvitem'));
      return view('intranet/cursos/respuesta', $viewData->get());
   }

   public function auv($salon = null, $curso = null)
   {
      $viewData = new ViewData();
      $cursoModel = new Models\CursoModel();
      $anioPeriodo = new Models\AnioPeriodoModel();
      $asigCursoModel = new Models\AsigCursoModel();
      $auvGrupoModel = new Models\AuvGrupoModel();
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
      $viewData->set('listaAuvGrupos', $auvGrupoModel->listarAuvGruposxPeriodo(array(
         'salon' => $salon,
         'curso' => $curso
      )));
      $viewData->set('esDocente', $this->esDocente);
      $viewData->set('esAdmin', $this->esAdmin);
      return view('intranet/cursos/auv', $viewData->get());
   }

   public function auvgrupo()
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $AuvGrupoModel = new Models\AuvGrupoModel();
      $viewData->set('anio', $this->anio);
      $viewData->set('codigo', $this->request->getPost('codigo'));
      $viewData->set('action', $this->request->getPost('action'));
      $viewData->set('periodo', $this->request->getPost('periodo'));
      $viewData->set('salon', $this->request->getPost('salon'));
      $viewData->set('curso', $this->request->getPost('curso'));
      $viewData->set('datosGrupo', $AuvGrupoModel->find($this->request->getPost('codigo')));
      return view('intranet/cursos/auv-grupo', $viewData->get());
   }

   public function auvGrupoItems()
   {
      $viewData = new ViewData();
      $datosDet = new Models\DatosModel();
      $auvGrupoItemModel = new Models\AuvGrupoItemModel();
      $viewData->isAjax(true);
      $grupo = $this->request->getPost('grupo');
      $viewData->set('listaTiposItems', $datosDet->listarDatos('014'));
      $viewData->set('esDocente', $this->esDocente);
      $viewData->set('esAdmin', $this->esAdmin);
      $viewData->set('esAlumno', $this->esAlumno);
      $viewData->set('grupo', $grupo);
      $viewData->set('salon', $this->request->getPost('salon'));
      if ($this->esAlumno) {
         $viewData->set('listaItemsPub', $auvGrupoItemModel->listarItems(['grupo' => $grupo, 'codalu' => CODIGO]));
      } else {
         $viewData->set('listaItemsPub', $auvGrupoItemModel->listarItems(['grupo' => $grupo]));
      }
      return view('intranet/cursos/auv-items', $viewData->get());
   }

   public function auvEditor()
   {
      $viewData = new ViewData();
      $viewData->isAjax(true);
      $datosDet = new Models\DatosModel();
      $viewData->set('listaTiposItems', $datosDet->listarDatos('014', true));
      $viewData->set('grupo', $this->request->getPost('grupo'));
      $viewData->set('action', $this->request->getPost('action'));
      return view('intranet/cursos/auv-editor', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $AuvGrupoModel = new Models\AuvGrupoModel();
      $auvRespNotaModel = new Models\AuvRespNotaModel();
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
               $AuvGrupoModel->guardarAuvGrupo($params, $action);
               $jsonData->set('listaAuvGrupos', $AuvGrupoModel->listarAuvGrupos(array(
                  'salon' => $params['salon'],
                  'curso' => $params['curso'],
                  'periodo' => $params['periodo']
               )));
               break;
            case 'eliminar-grupo':
               $codigo = $this->request->getPost('codigo');
               $AuvGrupoModel->where(array('codigo' => intval($codigo)))->delete();
               if (is_dir($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $codigo)) {
                  Funciones::eliminarDirectorio($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $codigo);
               }
               $jsonData->set('listaAuvGrupos', $AuvGrupoModel->listarAuvGrupos(array(
                  'salon' => $this->request->getPost('salon'),
                  'curso' => $this->request->getPost('curso'),
                  'periodo' => $this->request->getPost('periodo')
               )));
               break;
            case 'guardar-item':
               $auvGrupoItemModel = new Models\AuvGrupoItemModel();
               $auvGrupoAdjModel = new Models\AuvGrupoAdjModel();
               $action = $this->request->getPost('action');
               $cargoArchivos = $this->request->getPost('cargoArchivos') == "S";
               $adjuntos = $this->request->getFileMultiple("adjuntos");
               $values = array(
                  'grupo'   => $this->request->getPost('grupo'),
                  'titulo'  => $this->request->getPost('titulo'),
                  'tipo'    => $this->request->getPost('tipo'),
                  'cuerpo'  => Funciones::minificarHtml((string) $this->request->getPost('cuerpo')),
                  'fecpub'  => $this->request->getPost('fecpub'),
                  'fecmax'  => $this->request->getPost('fecmax'),
                  'adjunto' => $cargoArchivos ? 'S' : null
               );
               $grupo = $values['grupo'];
               $coditem = $auvGrupoItemModel->guardar($values, $action);
               if ($cargoArchivos && !empty($adjuntos)) {
                  if (!is_dir($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $grupo)) {
                     mkdir($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $grupo);
                  }
                  $orden = 1;
                  foreach ($adjuntos as $fileUp) {
                     $nombreAdjunto = sprintf("%s.%s", "F_" . $coditem . "_" . $orden, $fileUp->guessExtension());
                     $auvGrupoAdjModel->guardarAdjunto(array(
                        'codigo'  => $coditem,
                        'orden'   => $orden,
                        'nombre'  => $fileUp->getClientName(),
                        'tamanio' => $fileUp->getSizeByUnit(),
                        'ruta'    => "/uploads/auv/adjuntos/{$grupo}/" . $nombreAdjunto
                     ));
                     $fileUp->move($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $grupo, $nombreAdjunto, true);
                     $orden++;
                  }
               }
               $jsonData->set('listaItems', $auvGrupoItemModel->listarItems(array('grupo' => $grupo)));
               break;
            case 'eliminar-item':
               $auvGrupoItemModel = new Models\AuvGrupoItemModel();
               $auvRespAdjModel = new Models\AuvRespAdjModel();
               $grupo = $this->request->getPost('grupo');
               $item = $this->request->getPost('item');
               if (!$auvRespNotaModel->existeRegistros($item)) {
                  throw new \Exception("No se puede eliminar por que hay soluciones subidas por los estudiantes.");
               }
               $auvGrupoItemModel->where('codigo', $item)->delete();
               $auvRespAdjModel->where('coditem', $item)->delete();
               Funciones::eliminarArchivosxItemAuv($this->pathAdjuntosGrupo . DIRECTORY_SEPARATOR . $grupo, $item);
               break;
            case 'enviar-resp':
               $auvRespNotaModel = new Models\AuvRespNotaModel();
               $auvRespAdjModel = new Models\AuvRespAdjModel();
               $adjuntos = $this->request->getFileMultiple('adjuntos');
               $grupo = $this->request->getPost('grupo');
               $salon = $this->request->getPost('salon');
               $coditem = $this->request->getPost('auvitem');
               if (!is_dir($this->pathRespuestaAdjunto  . DIRECTORY_SEPARATOR . $grupo)) {
                  mkdir($this->pathRespuestaAdjunto . DIRECTORY_SEPARATOR . $grupo);
               }
               $comentario = $this->request->getPost('comentario');
               $auvRespNotaModel->set(array('fecenv' => date('Y-m-d H:i:s'), 'comentalu' => $comentario))
                  ->where(array('coditem' => $coditem, 'codalu' => CODIGO))->update();
               if (!empty($adjuntos)) {
                  $orden = 1;
                  foreach ($adjuntos as $archivo) {
                     $nombreAdjunto = sprintf("%s.%s", "R_" . $coditem . "_" . $orden, $archivo->guessExtension());
                     $auvRespAdjModel->insert(array(
                        'coditem'   => $coditem,
                        'codalu'    => CODIGO,
                        'salon'     => $salon,
                        'orden'     => $orden,
                        'nombre'    => $archivo->getClientName(),
                        'tamanio'   => $archivo->getSizeByUnit(),
                        'ruta'   => "/uploads/auv/respuesta/{$grupo}/" . $nombreAdjunto,
                        'fecreg' => date('Y-m-d H:i:s')
                     ));
                     $archivo->move($this->pathRespuestaAdjunto . DIRECTORY_SEPARATOR . $grupo, $nombreAdjunto, true);
                     $orden++;
                  }
               }
               break;
            case 'save-respuestas':
               $salon = $this->request->getPost('salon');
               $curso = $this->request->getPost('curso');
               $periodo = $this->request->getPost('periodo');
               $listado = $this->request->getPost('listado');
               $listado = !empty($listado) ? json_decode((string) $listado, true) : [];
               foreach ($listado as $value) {
                  $auvRespNotaModel->actualizarNotasBloque(
                     array('nota' => $value['nota'], 'revisado' => $value['revisado']),
                     array('coditem' => $this->request->getPost('coditem'), 'codalu' => $value['codalu']),
                     array($salon, $curso, intval($periodo))
                  );
               }
               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
