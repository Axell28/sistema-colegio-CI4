<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class MantenimientoAlumnoController extends BaseController
{

   public $alumnoModel;
   public $pathFotoAlumno;

   public function __construct()
   {
      $this->alumnoModel = new Models\AlumnoModel();
      $this->pathFotoAlumno = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'alumnos' . DIRECTORY_SEPARATOR . 'foto';
   }

   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $datosModel = new Models\DatosModel();
      $ubigeoModel = new Models\UbigeoModel();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $seccionModel = new Models\SeccionModel();
      $familiaModel = new Models\FamiliaModel();
      $matriculaModel = new Models\MatriculaModel();
      $viewData->set('listaAnios', $anioModel->listarAnios());
      $viewData->set('listaNiveles', $nivelModel->listarNiveles());
      $viewData->set('listaGrados', $gradoModel->listarGrados());
      $viewData->set('listaSecciones', $seccionModel->listarSecciones());
      $viewData->set('listaDocumentosIde', $datosModel->listarDatos('003'));
      $viewData->set('listaNacionalidad', $datosModel->listarDatos('006', true));
      $viewData->set('listaDistritos', $ubigeoModel->listarDistritos());
      $viewData->set('listaProvincias', $ubigeoModel->listarProvincias());
      $viewData->set('listaDepartamentos', $ubigeoModel->listarDepartamentos());
      $viewData->set('listaAlumnos', $this->alumnoModel->listarAlumnos(array('estado' => 'A')));
      $viewData->set('listaFamilias', $familiaModel->listarFamiliasCombo());
      $viewData->set('listaHistorialMatricula', $matriculaModel->obtenerDatosMatricula(array(), true));
      return view('academico/mantenimiento-alumno/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         // filtros grilla
         $filEstado = $this->request->getPost('filestado');
         $filsexo = $this->request->getPost('filsexo');
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $objImagen = $this->request->getFile('imagenUp');
               $cargoImagen = $this->request->getPost('cargoImagen') == 'S';
               $values = array(
                  'codalu'  => $this->request->getPost('codalu'),
                  'codper'  => $this->request->getPost('codper'),
                  'codfam'  => $this->request->getPost('codfam'),
                  'estado'  => $this->request->getPost('estado'),
                  'anioing' => $this->request->getPost('anioing'),
                  'nombres' => $this->request->getPost('nombres'),
                  'apepat'  => $this->request->getPost('apepat'),
                  'apemat'  => $this->request->getPost('apemat'),
                  'fecnac'  => $this->request->getPost('fecnac'),
                  'sexo'    => $this->request->getPost('sexo'),
                  'tipdoc'  => $this->request->getPost('tipdoc'),
                  'numdoc'  => $this->request->getPost('numdoc'),
                  'email'   => $this->request->getPost('email'),
                  'direccion'    => $this->request->getPost('direccion'),
                  'referencia'   => $this->request->getPost('referencia'),
                  'lugnac'       => $this->request->getPost('lugnac'),
                  'religion'     => $this->request->getPost('religion'),
                  'nacionalidad' => $this->request->getPost('nacionalidad'),
                  'fecing' => $this->request->getPost('fecing'),
                  'fecsal' => $this->request->getPost('fecsal'),
                  'motsal' => $this->request->getPost('motsal'),
                  'nivel' => $this->request->getPost('nivel'),
                  'grado' => $this->request->getPost('grado'),
                  'seccion' => $this->request->getPost('seccion'),
                  'ubgdir' => Funciones::formatCodUbigeo(
                     $this->request->getPost('departamento'),
                     $this->request->getPost('provincia'),
                     $this->request->getPost('distrito')
                  )
               );
               $codalu = $this->alumnoModel->guardarDatosAlumno($values, $action);
               if ($cargoImagen) {
                  $nombImagen = sprintf("%s.%s", $codalu, $objImagen->guessExtension());
                  if (is_dir($this->pathFotoAlumno)) {
                     if ($objImagen->isValid()) {
                        $objImagen->move($this->pathFotoAlumno, $nombImagen, true);
                        $this->alumnoModel->set('fotourl', "/uploads/alumnos/foto/{$nombImagen}")->update($codalu);
                     }
                  }
               }
               break;
            case 'eliminar':
               $codalu = $this->request->getGet('codalu');
               $dataAlumno = $this->alumnoModel->select("fotourl")->find($codalu);
               if (!empty($dataAlumno['fotourl'])) {
                  $pathDirFile = MY_PUBLIC_PATH . DIRECTORY_SEPARATOR . $dataAlumno['fotourl'];
                  if (is_file($pathDirFile)) {
                     unlink($pathDirFile);
                  }
               }
               $this->alumnoModel->eliminarAlumno($codalu);
               break;
            case 'eliminar-foto':
               $codalu = $this->request->getGet('codalu');
               $dataAlumno = $this->alumnoModel->select("fotourl")->find($codalu);
               $pathDirFile = MY_PUBLIC_PATH . DIRECTORY_SEPARATOR . $dataAlumno['fotourl'];
               if (is_file($pathDirFile)) {
                  unlink($pathDirFile);
               }
               $this->alumnoModel->set('fotourl', null)->update($codalu);
               break;
            case 'activar-usuario':
               $usuarioModel = new Models\UsuarioModel();
               $datosAcceso = $usuarioModel->generarUsuario(array(
                  'perfil'    => '003',
                  'apellidos' => $this->request->getPost('apellidos'),
                  'nombres'   => $this->request->getPost('nombres'),
                  'nomcomp'   => $this->request->getPost('nomcomp'),
                  'codigo'    => $this->request->getPost('codigo'),
                  'entidad'   => 'ALU'
               ));
               $jsonData->set('usuario', $datosAcceso['usuario']);
               $jsonData->set('password', $datosAcceso['password']);
               break;
         endswitch;
         $jsonData->set('listaAlumnos', $this->alumnoModel->listarAlumnos(array(
            'estado' => $filEstado,
            'sexo'   => $filsexo
         )));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
