<?php

namespace App\Controllers\Configuracion;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class PerfilesController extends BaseController
{
   private $perfilModel;

   public function __construct()
   {
      $this->perfilModel = new Models\PerfilModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $moduloModel = new Models\ModuloModel();
      $viewData->set('listaPerfiles', $this->perfilModel->listarPerfiles(false));
      $viewData->set('listaModulos', $moduloModel->listarModulos());
      return view('configuracion/perfiles/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $menuModel = new Models\MenuModel();
      try {
         switch ($caso):
            case 'list-menu':
               $modulo = $this->request->getPost('modulo');
               $perfil = $this->request->getPost('perfil');
               $jsonData->set('listaMenus', $menuModel->listarMenusAsigPerfil(array('modulo' => $modulo, 'perfil' => $perfil)));
               break;
            case 'save-menu':
               $modulo = $this->request->getPost('modulo');
               $perfil = $this->request->getPost('perfil');
               $listaMenus = (string) $this->request->getPost('listaMenus');
               $listaMenus = !empty($listaMenus) ? json_decode($listaMenus, true) : null;
               $menuModel->guardarRolesPerfil(array(
                  'perfil' => $perfil,
                  'modulo' => $modulo,
                  'listaMenus' => $listaMenus
               ));
               $jsonData->set('message', 'OK');
               break;
            case 'save-perfil':
               $perfil = $this->request->getPost('perfil');
               $nombre = $this->request->getPost('nombre');
               if (empty($perfil)) {
                  $this->perfilModel->insert(array('nombre' => $nombre));
               } else {
                  $this->perfilModel->set(array('nombre' => $nombre))->where('perfil', $perfil)->update();
               }
               $jsonData->set('listaPerfiles', $this->perfilModel->findAll());
               break;
            case 'delete-perfil':
               $perfil = $this->request->getPost('perfil');
               $this->perfilModel->delete($perfil);
               $jsonData->set('listaPerfiles', $this->perfilModel->findAll());
               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
