<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class CursosController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $cursoModel = new Models\CursoModel();
      $viewData->set('listaCursos', $cursoModel->listarCursos());
      return view('academico/cursos/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $cursoModel = new Models\CursoModel();
      try {
         switch ($caso):
            case 'insert':
               $nombre = $this->request->getPost('nombre');
               $curabr = $this->request->getPost('curabr');
               $interno = $this->request->getPost('interno');
               $cursoModel->insert(array('nombre' => $nombre, 'curabr' => $curabr, 'interno' => $interno));
               break;
            case 'update':
               $codcur = $this->request->getPost('codcur');
               $nombre = $this->request->getPost('nombre');
               $curabr = $this->request->getPost('curabr');
               $cursoModel->set(array('nombre' => $nombre, 'curabr' => $curabr))->where('codcur', $codcur)->update();
               break;
            case 'delete':
               $codcur = $this->request->getGet('codcur');
               $result = $cursoModel->eliminarCurso($codcur);
               if (!$result) {
                  throw new \Exception("El curso esta siendo utilizado en una curricula, no se puede eliminar");
               }
               break;
         endswitch;
         $jsonData->set('listaCursos', $cursoModel->findAll());
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(400);
      }
   }
}
