<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;
use App\Helpers\Funciones;

class PublicacionesController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $perfilModel = new Models\PerfilModel();
      $publicacionModel = new Models\PublicacionModel();
      $viewData->set('listaTiposPub', $datosModel->listarDatos('009'));
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      $viewData->set('listaPublicaciones', $publicacionModel->listarPublicaciones(array(
         'fecdesde' => date('Y-m-d'),
         'fechasta' => date('Y-m-d')
      )));
      return view('intranet/publicaciones/index', $viewData->get());
   }

   public function editor($codpub = null)
   {
      $viewData = new ViewData();
      $perfilModel = new Models\PerfilModel();
      $datosModel = new Models\DatosModel();
      $publicacionModel = new Models\PublicacionModel();
      $datosPublicacion = $publicacionModel->getDatosDefault();
      $action = 'I';
      if (!empty($codpub)) {
         $datosPublicacion = $publicacionModel->find($codpub);
         $action = 'E';
      }
      $viewData->set('action', $action);
      $viewData->set('codpub', $codpub);
      $viewData->set('listaTiposPub', $datosModel->listarDatos('009'));
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      $viewData->set('datosPublicacion', $datosPublicacion);
      return view('intranet/publicaciones/editor', $viewData->get());
   }

   public function json($caso)
   {
      $statusCode = 200;
      $viewJson = new JsonData();
      $publicacionModel = new Models\PublicacionModel();
      $tipoPub = $this->request->getPost('tipopub');
      $fecdesde = $this->request->getPost('fecdesde');
      $fechasta = $this->request->getPost('fechasta');
      try {
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $values = array(
                  'codpub' => $this->request->getPost('codpub'),
                  'titulo' => $this->request->getPost('titulo'),
                  'tipo' => $this->request->getPost('tipo'),
                  'fecpubini' => $this->request->getPost('fecpubini'),
                  'cuerpo' => Funciones::minificarHtml((string) $this->request->getPost('cuerpo')),
                  'destinatarios' => $this->request->getPost('destinatarios')
               );
               $publicacionModel->guardarPublicacion($values, $action);
               break;
            case 'eliminar':
               $codpub = $this->request->getGet('codpub');
               if (!is_numeric($codpub)) {
                  throw new \Exception('El código de la publicación es de formato incorrecto');
               }
               $publicacionModel->delete($codpub);
               break;
         endswitch;
         $viewJson->set('listaPublicaciones', $publicacionModel->listarPublicaciones(array(
            'tipo' => $tipoPub,
            'fecdesde' => $fecdesde,
            'fechasta' => $fechasta
         )));
      } catch (\Exception $ex) {
         $statusCode = 400;
         $viewJson->set('message', $ex->getMessage());
      } finally {
         return $this->response->setJSON($viewJson->get())->setStatusCode($statusCode);
      }
   }
}
