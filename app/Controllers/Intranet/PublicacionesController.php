<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Helpers\Funciones;
use App\Controllers\BaseController;

class PublicacionesController extends BaseController
{

   public $publicacionModel;
   public $pathPublicacion;

   public function __construct()
   {
      $this->publicacionModel = new Models\PublicacionModel();
      $this->pathPublicacion = UPLOAD_PATH . DIRECTORY_SEPARATOR . "publicacion";
   }

   public function index()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $perfilModel = new Models\PerfilModel();
      $viewData->set('listaTiposPub', $datosModel->listarDatos('009'));
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      $viewData->set('listaPublicaciones', $this->publicacionModel->listarPublicaciones(array(
         'fecdesde' => date('Y-m-d'),
         'fechasta' => date('Y-m-d')
      )));
      return view('intranet/publicaciones/index', $viewData->get());
   }

   public function editor($codpub = null)
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $perfilModel = new Models\PerfilModel();
      $publicacionDestModel = new Models\PublicacionDestModel();
      $datosPublicacion = $this->publicacionModel->getDatosDefault();
      $action = 'I';
      if (!empty($codpub)) {
         $datosPublicacion = $this->publicacionModel->find($codpub);
         $action = 'E';
      }
      $viewData->set('action', $action);
      $viewData->set('codpub', $codpub);
      $viewData->set('listaTiposPub', $datosModel->listarDatos('009'));
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      $viewData->set('listarDestinatarios', $publicacionDestModel->listarDestinatarios($codpub));
      $viewData->set('datosPublicacion', $datosPublicacion);
      return view('intranet/publicaciones/editor', $viewData->get());
   }

   public function json($caso)
   {
      $statusCode = 200;
      $viewJson = new JsonData();
      $publicacionDestModel = new Models\PublicacionDestModel();
      $publicacionFilesModel = new Models\PublicacionFilesModel();
      $tipoPub = $this->request->getPost('tipopub');
      $fecdesde = $this->request->getPost('fecdesde');
      $fechasta = $this->request->getPost('fechasta');
      try {
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $destinatarios = (string) $this->request->getPost('destinatarios');
               $cargoArchivo = $this->request->getPost('cargoArchivo') == "S";
               $adjuntos = $this->request->getFileMultiple("adjuntos");
               $destinatarios = json_decode($destinatarios, true);

               if (empty($destinatarios)) {
                  throw new \Exception("Debe seleccionar los perfiles destinados de esta publicación.");
               }

               // guardar publicacion
               $values = array(
                  'codpub' => $this->request->getPost('codpub'),
                  'titulo' => $this->request->getPost('titulo'),
                  'tipo' => $this->request->getPost('tipo'),
                  'fecpubini' => $this->request->getPost('fecpubini'),
                  'cuerpo' => Funciones::minificarHtml((string) $this->request->getPost('cuerpo')),
                  'destinatarios' => $this->request->getPost('destinatarios')
               );
               $codpub = $this->publicacionModel->guardarPublicacion($values, $action);

               // guardar destinatarios
               $publicacionDestModel->guardarDestinatarios($codpub, $destinatarios);

               // guardar archivos
               if ($cargoArchivo && !empty($adjuntos)) {
                  $cont = 1;
                  foreach ($adjuntos as $archivo) {
                     if (!is_dir($this->pathPublicacion . DIRECTORY_SEPARATOR . $codpub)) {
                        mkdir($this->pathPublicacion . DIRECTORY_SEPARATOR . $codpub);
                     }
                     $publicacionFilesModel->insert(array(
                        'codpub'  => $codpub,
                        'orden'   => $cont,
                        'tamanio' => $archivo->getSizeByUnit(),
                        'nombre'  => $archivo->getClientName(),
                        'ruta'    => "/uploads/publicacion/{$codpub}/" . $archivo->getClientName()
                     ));
                     $archivo->move($this->pathPublicacion . DIRECTORY_SEPARATOR . $codpub, $archivo->getClientName());
                     $cont++;
                  }
               }
               break;
            case 'eliminar':
               $codpub = $this->request->getGet('codpub');
               if (!is_numeric($codpub)) {
                  throw new \Exception('El código de la publicación es de formato incorrecto');
               }
               $this->publicacionModel->delete($codpub);
               Funciones::eliminarDirectorio($this->pathPublicacion . DIRECTORY_SEPARATOR . $codpub);
               break;
         endswitch;
         $viewJson->set('listaPublicaciones', $this->publicacionModel->listarPublicaciones(array(
            'tipo' => $tipoPub,
            'fecdesde' => $fecdesde,
            'fechasta' => $fechasta
         )));
      } catch (\Exception $ex) {
         $statusCode = 401;
         $viewJson->set('message', $ex->getMessage());
      } finally {
         return $this->response->setJSON($viewJson->get())->setStatusCode($statusCode);
      }
   }
}
