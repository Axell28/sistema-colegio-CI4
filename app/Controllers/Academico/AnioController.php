<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class AnioController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $anioModel = new Models\AnioModel();
      $viewData->set('listaAnios', $anioModel->listarAnios());
      return view('academico/anio/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $anioModel = new Models\AnioModel();
      try {
         switch ($caso):
            case 'insert':
               $anio = $this->request->getPost('anio');
               if (empty($anio)) {
                  throw new \Exception('Debe ingresar el año academico', 2);
               }
               $nombre = $this->request->getPost('nombre');
               $fecini = $this->request->getPost('fecini');
               $fecfin = $this->request->getPost('fecfin');
               $existeAnio = $anioModel->find($anio);
               if (empty($existeAnio)) {
                  $anioModel->insert(array('anio' => $anio, 'nombre' => !empty($nombre) ? $nombre : $anio, 'fecini' => $fecini, 'fecfin' => $fecfin));
               } else {
                  throw new \Exception("El año " . $anio . " ya se encuentra registrado", 1);
               }
               break;
            case 'update':
               $anio = $this->request->getPost('anio');
               $nombre = $this->request->getPost('nombre');
               $fecini = $this->request->getPost('fecini');
               $fecfin = $this->request->getPost('fecfin');
               $anioModel->set(array('nombre' => !empty($nombre) ? $nombre : $anio, 'fecfini' => $fecini, 'fecfin' => $fecfin))->where('anio', $anio)->update();
               break;
            case 'update-matricula':
               $anio = $this->request->getPost('anio');
               $matricula = $this->request->getPost('valor');
               $anioModel->set(array('matricula' => 'N'))->update();
               $anioModel->set(array('matricula' => $matricula))->where('anio', $anio)->update();
               break;
            case 'update-vigente':
               $anio = $this->request->getPost('anio');
               $vigente = $this->request->getPost('valor');
               $anioModel->set(array('vigente' => 'N'))->update();
               $anioModel->set(array('vigente' => $vigente))->where('anio', $anio)->update();
               break;
         endswitch;
         $jsonData->set('listaAnios', $anioModel->listarAnios());
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('code', $ex->getCode());
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(400);
      }
   }
}
