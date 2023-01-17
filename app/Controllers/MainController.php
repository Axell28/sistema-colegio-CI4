<?php

namespace App\Controllers;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Models\PerfilModel;
use App\Models\UsuarioModel;

class MainController extends BaseController
{

   public function index()
   {

      if (is_null(session()->get('loggedIn'))) {
         session()->destroy();
         return redirect()->to('/auth/login');
      }

      $moduloModel = new Models\ModuloModel();
      if (SUPER_ADMIN) {
         return redirect()->to('/academico');
      }
      $modulos = $moduloModel->listarModulosxPerfil(array('perfil' => PERFIL));

      // validar si tiene asignado el modulo intranet
      $tieneModIntranet = array_search("02", array_column($modulos, 'codmod'));
      if ($tieneModIntranet) {
         return redirect()->to('/intranet');
      } else {
         foreach ($modulos as $value) {
            return redirect()->to($value['url']);
         }
      }
   }

   public function verPerfil()
   {
      $viewData = new ViewData();
      $usuarioModel = new UsuarioModel();
      $perfilModel = new PerfilModel();
      $viewData->isAjax(true);
      $viewData->set('listaPerfiles', $perfilModel->listarPerfiles(true));
      $viewData->set('usuario_photo', $usuarioModel->obtenerFoto());
      $viewData->set('datosUsuario', $usuarioModel->buscarUsuario(USUARIO));
      return view('template/info-perfil', $viewData->get());
   }
}
