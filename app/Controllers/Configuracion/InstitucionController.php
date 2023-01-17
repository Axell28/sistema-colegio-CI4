<?php

namespace App\Controllers\Configuracion;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class InstitucionController extends BaseController
{
   public $institucionModel;

   public function __construct()
   {
      $this->institucionModel = new Models\InstitucionModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $ubigeoModel = new Models\UbigeoModel();
      $viewData->set('listaDepartamentos', $ubigeoModel->listarDepartamentos());
      $viewData->set('listaProvincias', $ubigeoModel->listarProvincias());
      $viewData->set('listaDistritos', $ubigeoModel->listarDistritos());
      $viewData->set('datosInstitucion', $this->institucionModel->obtenerDatosInstitucion());
      return view('configuracion/institucion/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         switch ($caso):
            case 'guardar':
               $codigo = $this->request->getPost('codigo');
               $cargoImagen = $this->request->getPost('cargoImagen') == "S";
               $imagenLogo = $this->request->getFile("imagenLogo");
               $values = array(
                  'nombre'     => $this->request->getPost('nombre'),
                  'direccion'  => $this->request->getPost('direccion'),
                  'referencia' => $this->request->getPost('referencia'),
                  'telefono'   => $this->request->getPost('telefono'),
                  'ruc'        => $this->request->getPost('ruc'),
                  'correo'     => $this->request->getPost('correo'),
                  'web'        => $this->request->getPost('web'),
                  'director'   => $this->request->getPost('director'),
                  'administrador' => $this->request->getPost('administrador'),
                  'colorpri' => $this->request->getPost('colorpri'),
                  'colorsec'  => $this->request->getPost('colorsec'),
                  'ubgdir'    => Funciones::formatCodUbigeo(
                     $this->request->getPost('departamento'),
                     $this->request->getPost('provincia'),
                     $this->request->getPost('distrito')
                  )
               );
               $this->institucionModel->set($values)->update($codigo);
               // carga de imagen
               if ($cargoImagen && !empty($imagenLogo)) {
                  $validacion = $this->validate($this->rulesValidateImagen());
                  if (!$validacion) {
                     throw new \Exception("Se ha producido un error al intentar cargar la imagen");
                  }
                  $rutaDir = UPLOAD_PATH . DIRECTORY_SEPARATOR . "local";
                  if (is_dir($rutaDir)) {
                     $imagenLogo->move($rutaDir, "escudo.png", true);
                  }
               }
               $jsonData->set('message', 'OK');
               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }

   private function rulesValidateImagen()
   {
      return array(
         'imagenLogo' => [
            'uploaded[imagenLogo]',
            'mime_in[imagenLogo,image/png]',
         ]
      );
   }
}
