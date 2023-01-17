<?php

namespace App\Controllers\Configuracion;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class MantenimientoUsuarioController extends BaseController
{
   public $usuarioModel;

   public function __construct()
   {
      $this->usuarioModel = new Models\UsuarioModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $perfilModel = new Models\PerfilModel();
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      $viewData->set('listaUsuarios', $this->usuarioModel->listarUsuarios(array('estado' => 'A')));
      return view('configuracion/mantenimiento-usuario/index', $viewData->get());
   }

   public function usuario()
   {
      $viewData = new ViewData();
      $perfilModel = new Models\PerfilModel();
      $viewData->isAjax(true);
      $viewData->set('action', 'I');
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles());
      return view('configuracion/mantenimiento-usuario/usuario', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         $perfilF = $this->request->getPost('perfilF');
         $estadoF = $this->request->getPost('estadoF');
         switch ($caso):
            case 'update-estado':
               $usuario = $this->request->getPost('usuario');
               $estado  = $this->request->getPost('estado');
               $this->usuarioModel->set('estado', $estado)->update($usuario);
               break;
         endswitch;
         $jsonData->set('listaUsuarios', $this->usuarioModel->listarUsuarios(array('estado' => $estadoF, 'perfil' => $perfilF)));
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
