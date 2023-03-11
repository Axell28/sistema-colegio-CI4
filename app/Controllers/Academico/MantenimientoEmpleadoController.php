<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class MantenimientoEmpleadoController extends BaseController
{

   public $empleadoModel;
   public $pathFotoEmpleado;

   public function __construct()
   {
      $this->empleadoModel = new Models\EmpleadoModel();
      $this->pathFotoEmpleado = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'empleados' . DIRECTORY_SEPARATOR . 'foto';
   }

   public function index()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $ubigeoModel = new Models\UbigeoModel();
      $perfilModdel = new Models\PerfilModel();
      $viewData->set('listaDocumentosIde', $datosModel->listarDatos('003'));
      $viewData->set('listaAreasLab', $datosModel->listarDatos('004'));
      $viewData->set('listaCargosLab', $datosModel->listarDatos('005'));
      $viewData->set('listaNacionalidad', $datosModel->listarDatos('006', true));
      $viewData->set('listaEstadoCivil', $datosModel->listarDatos('007'));
      $viewData->set('listaDepartamentos', $ubigeoModel->listarDepartamentos());
      $viewData->set('listaProvincias', $ubigeoModel->listarProvincias());
      $viewData->set('listaDistritos', $ubigeoModel->listarDistritos());
      $viewData->set('listaPerfiles', $perfilModdel->listarPerfiles(true, array('003', '004')));
      $viewData->set('listaEmpleados', $this->empleadoModel->listarEmpleados(array('estado' => 'A')));
      return view('academico/mantenimiento-empleado/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         // filtros grilla
         $filestado = $this->request->getPost('filestado');
         $filarea  = $this->request->getPost('filarea');

         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $objImagen = $this->request->getFile('imagen');
               $cargoImagen = $this->request->getPost('cargoImagen') == 'S';
               $values = array(
                  'codemp'  => $this->request->getPost('codemp'),
                  'codper'  => $this->request->getPost('codper'),
                  'estado'  => $this->request->getPost('estado'),
                  'nombres' => $this->request->getPost('nombres'),
                  'apepat'  => $this->request->getPost('apepat'),
                  'apemat'  => $this->request->getPost('apemat'),
                  'fecnac'  => $this->request->getPost('fecnac'),
                  'sexo'    => $this->request->getPost('sexo'),
                  'tipdoc'  => $this->request->getPost('tipdoc'),
                  'numdoc'  => $this->request->getPost('numdoc'),
                  'estcivil'  => $this->request->getPost('estcivil'),
                  'ruc'     => $this->request->getPost('ruc'),
                  'perfil'  => $this->request->getPost('perfil'),
                  'celular1'  => $this->request->getPost('celular1'),
                  'celular2'  => $this->request->getPost('celular2'),
                  'email'   => $this->request->getPost('email'),
                  'direccion'    => $this->request->getPost('direccion'),
                  'referencia'   => $this->request->getPost('referencia'),
                  'lugnac'       => $this->request->getPost('lugnac'),
                  'religion'     => $this->request->getPost('religion'),
                  'nacionalidad' => $this->request->getPost('nacionalidad'),
                  'fecing'  => $this->request->getPost('fecing'),
                  'fecsal'  => $this->request->getPost('fecsal'),
                  'motsal'  => $this->request->getPost('motsal'),
                  'area'    => $this->request->getPost('area'),
                  'cargo'   => $this->request->getPost('cargo'),
                  'docente' => $this->request->getPost('docente'),
                  'profesion' => $this->request->getPost('profesion'),
                  'infoaca'   => $this->request->getPost('infoaca'),
                  'ubgdir'    => Funciones::formatCodUbigeo(
                     $this->request->getPost('departamento'),
                     $this->request->getPost('provincia'),
                     $this->request->getPost('distrito')
                  )
               );
               $codemp = $this->empleadoModel->guardarEmpleado($values, $action);
               if ($cargoImagen) {
                  $nombImagen = sprintf("%s.%s", $codemp, $objImagen->guessExtension());
                  if (is_dir($this->pathFotoEmpleado)) {
                     if ($objImagen->isValid()) {
                        $objImagen->move($this->pathFotoEmpleado, $nombImagen, true);
                        $this->empleadoModel->set('fotourl', "/uploads/empleados/foto/{$nombImagen}")->update($codemp);
                     }
                  }
               }
               break;
            case 'eliminar':
               $codemp = $this->request->getGet('codemp');
               $datoEmpleado = $this->empleadoModel->select('fotourl')->find($codemp);
               if (!empty($datoEmpleado['fotourl'])) {
                  $pathDirFile = MY_PUBLIC_PATH . DIRECTORY_SEPARATOR . $datoEmpleado['fotourl'];
                  if (is_file($pathDirFile)) {
                     unlink($pathDirFile);
                  }
               }
               $this->empleadoModel->eliminarEmpleado($codemp);
               break;
            case 'eliminar-foto':
               $codemp = $this->request->getPost('codemp');
               $datoEmpleado = $this->empleadoModel->select('fotourl')->find($codemp);
               $pathDirFile = MY_PUBLIC_PATH . DIRECTORY_SEPARATOR . $datoEmpleado['fotourl'];
               if (is_file($pathDirFile)) {
                  unlink($pathDirFile);
               }
               $this->empleadoModel->set('fotourl', null)->update($codemp);
               break;
            case 'activar-usuario':
               $usuarioModel = new Models\UsuarioModel();
               $datosAcceso = $usuarioModel->generarUsuario(array(
                  'perfil'    => $this->request->getPost('perfil'),
                  'apellidos' => $this->request->getPost('apellidos'),
                  'nombres'   => $this->request->getPost('nombres'),
                  'nomcomp'   => $this->request->getPost('nomcomp'),
                  'codigo'    => $this->request->getPost('codigo'),
                  'entidad'   => 'EMP'
               ));
               $jsonData->set('usuario', $datosAcceso['usuario']);
               $jsonData->set('password', $datosAcceso['password']);
               $this->enviarEmailActivacion(array(
                  'nombre' => $this->request->getPost('nombre'),
                  'usuario' => $datosAcceso['usuario'],
                  'passwd'  => $datosAcceso['password']
               ));
               break;
         endswitch;
         $jsonData->set('listaEmpleados', $this->empleadoModel->listarEmpleados(array(
            'estado' => $filestado,
            'area'   => $filarea
         )));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }

   public function enviarEmailActivacion(array $params)
   {
      try {
         $email = \Config\Services::email();
         $email->setFrom('test_email@test.com', 'Plataforma Educativa');
         $email->setTo('axel18virgo@gmail.com');
         $email->setSubject('ACCESO A PLATAFORMA');
         $email->setMessage('
         <h3>Hola, ' . $params['nombre'] . '</h3>
         <p>Tu cuenta de usuario ha sido activado para el ingreso a la plataforma.</p>
         <p><b>Usuario:</b> &nbsp;&nbsp; ' . $params['usuario'] . '</p>
         <p><b>Contraseña :</b> &nbsp;&nbsp; ' . $params['passwd'] . '</p>
         <p>
         <p>Ingreso a la plaforma en este <a href="' . base_url('auth/login') . '" target="_blank">enlace</a></p>
      ');
         $email->send();
      } catch (\Exception $ex) {
      }
   }
}
