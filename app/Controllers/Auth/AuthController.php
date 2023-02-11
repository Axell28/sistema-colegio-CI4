<?php

namespace App\Controllers\Auth;

use App\Models\AnioModel;
use App\Models\ModuloModel;
use App\Models\UsuarioModel;
use App\Controllers\BaseController;
use App\Models\InstitucionModel;

class AuthController extends BaseController
{
   public function login()
   {
      //echo password_hash('12345', PASSWORD_DEFAULT);
      return view('auth/login');
   }

   public function authenticate()
   {
      $validateForm = $this->validate($this->getLoginRules());
      if (!$validateForm) {
         return redirect()->back(401)->withInput()->with('error', $this->validator->getErrors());
      }
      try {
         $usuario = $this->request->getPost('usuario');
         $password = (string) $this->request->getPost('password');
         $anioModel = new AnioModel();
         $moduloModel = new ModuloModel();
         $usuarioModel = new UsuarioModel();
         $usuarioData = $usuarioModel->buscarUsuario($usuario);
         if (empty($usuarioData)) {
            throw new \Exception('Usuario no registrado');
         }
         if (!password_verify($password, $usuarioData['passwd'])) {
            throw new \Exception('Contraseña incorrecta');
         }
         if ($usuarioData['estado'] !== 'A') {
            throw new \Exception('Su usuario esta inactivo');
         }
         if ($usuarioData['supadm'] !== 'S') {
            $tieneModulos = $moduloModel->listarModulosxPerfil(array('perfil' => $usuarioData['perfil']));
            if (empty($tieneModulos)) {
               throw new \Exception('Su usuario no tiene un módulo asignado, consulte con su administrador');
            }
         }
         session()->set(array(
            'usuario'  => $usuarioData['usuario'],
            'perfil'   => $usuarioData['perfil'],
            'supadm'   => $usuarioData['supadm'] == 'S',
            'codigo'   => $usuarioData['codigo'],
            'entidad'  => $usuarioData['entidad'],
            'anio'     => $anioModel->getAnioVigente(),
            'loggedIn' => TRUE
         ));
         return redirect()->to('/', 200);
      } catch (\Exception $ex) {
         return redirect()->back(401)->withInput()->with('errorMsg', $ex->getMessage());
      }
   }

   public function logout()
   {
      $usuarioModel = new UsuarioModel();
      $usuarioModel->marcarConexion(session()->get('usuario'));
      session()->destroy();
      return redirect()->to('/auth/login');
   }

   private function getLoginRules()
   {
      return array(
         'usuario' => [
            'rules' => 'required|alpha_numeric|max_length[20]',
            'errors' => [
               'required' => 'Ingresar nombre de usuario',
               'alpha_numeric' => 'Formato invalido',
               'max_length' => 'Usuario debe tener menos de 20 caracteres'
            ]
         ],
         'password' => [
            'rules'  => 'required|max_length[20]',
            'errors' => [
               'required' => 'Ingresar contraseña',
               'max_length' => 'La contraseña debe tener menos de 20 caracteres'
            ]
         ]
      );
   }
}
