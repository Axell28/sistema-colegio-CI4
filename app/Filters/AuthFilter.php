<?php

namespace App\Filters;

use App\Models\ModuloModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{

   public function before(RequestInterface $request, $arguments = null)
   {
      if (!session()->get('loggedIn')) {
         return redirect()->to('/auth/login');
      }
      $moduloModel = new ModuloModel();
      $moduloURL = "/" . $request->uri->getSegment(1, '');
      $datosModulo = $moduloModel->buscarPorUrl($moduloURL);
      session()->set('modulo', $datosModulo['codmod']);
      session()->set('modulo_url', $datosModulo['url']);
      session()->set('modulo_name', $datosModulo['nombre']);
   }

   public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
   {
   }
}
