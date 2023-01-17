<?php

namespace App\Helpers;

use App\Models;

class ViewData
{

   private $data;
   private $isByAjax;

   public function __construct()
   {
      $this->data = array();
   }

   public function isAjax(bool $value = false)
   {
      $this->isByAjax = $value;
   }

   public function set(string $key, $value)
   {
      $this->data[$key] = $value;
   }

   public function get()
   {
      if (!$this->isByAjax) {
         $uri = new \CodeIgniter\HTTP\URI(current_url());
         $fullPathUrl = $uri->getSegments();
         $menuModel = new Models\MenuModel();
         $moduloModel = new Models\ModuloModel();
         $usuarioModel = new Models\UsuarioModel();
         $institucionModel = new Models\InstitucionModel();
         $tmpModulos = SUPER_ADMIN ? $moduloModel->listarModulos() : $moduloModel->listarModulosxPerfil(array('perfil' => PERFIL));
         $tmpArbolMenu = $menuModel->listarArbolMenu(array('modulo' => MODULO, 'perfil' => PERFIL));
         $this->set('layout_modulos', $tmpModulos);
         $this->set('layout_mod_name', $moduloModel->obtenerNombre(MODULO));
         $this->set('layout_menu_name', isset($fullPathUrl[1]) ? "/" . $fullPathUrl[1] : null);
         $this->set('layout_menuArbol', $tmpArbolMenu);
         $this->set('institucion_nombre', $institucionModel->obtenerNombre());
         $this->set('usuario_photo', $usuarioModel->obtenerFoto());
      }
      return $this->data;
   }
}
