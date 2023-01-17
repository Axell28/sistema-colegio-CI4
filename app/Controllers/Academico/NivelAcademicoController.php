<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class NivelAcademicoController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $nivelModel = new Models\NivelModel();
      $viewData->set('listaNiveles', $nivelModel->listarNiveles(false));
      return view('academico/nivel-academico/index', $viewData->get());
   }

   public function json($caso)
   {
      $jsonData = new JsonData();
      $nivelModel = new Models\NivelModel();
      $gradoModel = new Models\GradoModel();
      $seccionModel = new Models\SeccionModel();
      try {
         switch ($caso) {
            case 'listar':
               $tabla = $this->request->getPost('tabla');
               $nivel = $this->request->getPost('nivel');
               $grado = $this->request->getPost('grado');
               if ($tabla == 'N') {
                  $jsonData->set('listaNiveles', $nivelModel->listarNiveles(false));
               } else if ($tabla == 'G') {
                  $jsonData->set('listaGrados', $gradoModel->listarGradosPorNivel($nivel));
               } else if ($tabla == 'S') {
                  $jsonData->set('listaSecciones', $seccionModel->listarSeccionPorGrado($nivel, $grado));
               }
               break;
            case 'insert':
               $tabla = $this->request->getPost('tabla');
               $nivel = $this->request->getPost('nivel');
               $grado = $this->request->getPost('grado');
               $seccion = $this->request->getPost('seccion');
               $descripcion = $this->request->getPost('descripcion');
               if ($tabla == 'N') {
                  $nivelModel->insert(array('nivel' => $nivel, 'descripcion' => empty($descripcion) ? $nivel : $descripcion));
                  $jsonData->set('listaNiveles', $nivelModel->listarNiveles(false));
               } else if ($tabla == 'G') {
                  $gradoModel->insert(array('nivel' => $nivel, 'grado' => $grado, 'descripcion' => empty($descripcion) ? $grado : $descripcion));
                  $jsonData->set('listaGrados', $gradoModel->listarGradosPorNivel($nivel));
               } else if ($tabla == 'S') {
                  $seccionModel->insert(array('nivel' => $nivel, 'grado' => $grado, 'seccion' => $seccion, 'descripcion' => empty($descripcion) ? $seccion :  $descripcion));
                  $jsonData->set('listaSecciones', $seccionModel->listarSeccionPorGrado($nivel, $grado));
               }
               break;
            case 'update':
               $tabla = $this->request->getPost('tabla');
               $nivel = $this->request->getPost('nivel');
               $grado = $this->request->getPost('grado');
               $estado = $this->request->getPost('estado');
               $seccion = $this->request->getPost('seccion');
               $descripcion = $this->request->getPost('descripcion');
               if ($tabla == 'N') {
                  $nivelModel->set(array('descripcion' => $descripcion, 'estado' => $estado))->where(array('nivel' => $nivel))->update();
               } else if ($tabla == 'G') {
                  $gradoModel->set('descripcion', $descripcion)->where(array('nivel' => $nivel, 'grado' => $grado))->update();
               } else if ($tabla == 'S') {
                  $seccionModel->set('descripcion', $descripcion)->where(array('nivel' => $nivel, 'grado' => $grado, 'seccion' => $seccion))->update();
               }
               break;
            case 'delete':
               $tabla = $this->request->getPost('tabla');
               $nivel = $this->request->getPost('nivel');
               $grado = $this->request->getPost('grado');
               $seccion = $this->request->getPost('seccion');
               if ($tabla == 'N') {
                  $nivelModel->where(array('nivel' => $nivel))->delete();
                  $jsonData->set('listaNiveles', $nivelModel->listarNiveles(false));
               } else if ($tabla == 'G') {
                  $gradoModel->where(array('nivel' => $nivel, 'grado' => $grado))->delete();
                  $jsonData->set('listaGrados', $gradoModel->listarGradosPorNivel($nivel));
               } else if ($tabla == 'S') {
                  $seccionModel->where(array('nivel' => $nivel, 'grado' => $grado, 'seccion' => $seccion))->delete();
                  $jsonData->set('listaSecciones', $seccionModel->listarSeccionPorGrado($nivel, $grado));
               }
               break;
         }
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
