<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class MantenimientoFamiliaController extends BaseController
{

   public $familiaModel;

   public function __construct()
   {
      $this->familiaModel = new Models\FamiliaModel();
   }

   public function index()
   {
      $viewData = new ViewData();
      $datosModel = new Models\DatosModel();
      $familiaModel = new Models\FamiliaModel();
      $viewData->set('listaParentesco', $datosModel->listarDatos('008', true));
      $viewData->set('listaDocumentosIde', $datosModel->listarDatos('003'));
      $viewData->set('listaNacionalidad', $datosModel->listarDatos('006', true));
      $viewData->set('listaEstadoCivil', $datosModel->listarDatos('007'));
      $viewData->set('listaFamilias', $this->familiaModel->listarFamilias());
      $viewData->set('listaFamiliaHijos', $familiaModel->listarHijosFamilia());
      return view('academico/mantenimiento-familia/index', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      try {
         switch ($caso):
            case 'guardar':
               $action = $this->request->getPost('action');
               $values = array(
                  'codfam' => $this->request->getPost('codigoFam'),
                  'nombreFam' => $this->request->getPost('nombreFam'),
                  'fecingFam' => $this->request->getPost('fecingFam'),
                  'direccionFam' => $this->request->getPost('direccionFam'),
                  'correoFam' => $this->request->getPost('correoFam'),
                  'celularFam' => $this->request->getPost('celularFam'),

                  'codper1' => $this->request->getPost('codper1'),
                  'parentesco1' => $this->request->getPost('parentesco1'),
                  'nombre1' => $this->request->getPost('nombre1'),
                  'apepat1' => $this->request->getPost('apepat1'),
                  'apemat1' => $this->request->getPost('apemat1'),
                  'fecnac1' => $this->request->getPost('fecnac1'),
                  'tipdoc1' => $this->request->getPost('tipdoc1'),
                  'numdoc1' => $this->request->getPost('numdoc1'),
                  'estcivil1' => $this->request->getPost('estcivil1'),
                  'nacionalidad1' => $this->request->getPost('nacionalidad1'),
                  'celular1' => $this->request->getPost('celular1'),
                  'correo1'  => $this->request->getPost('correo1'),
                  'responsable1' => $this->request->getPost('responsable1'),

                  'codper2' => $this->request->getPost('codper2'),
                  'parentesco2' => $this->request->getPost('parentesco2'),
                  'nombre2' => $this->request->getPost('nombre2'),
                  'apepat2' => $this->request->getPost('apepat2'),
                  'apemat2' => $this->request->getPost('apemat2'),
                  'fecnac2' => $this->request->getPost('fecnac2'),
                  'tipdoc2' => $this->request->getPost('tipdoc2'),
                  'numdoc2' => $this->request->getPost('numdoc2'),
                  'estcivil2' => $this->request->getPost('estcivil2'),
                  'nacionalidad2' => $this->request->getPost('nacionalidad2'),
                  'celular2' => $this->request->getPost('celular2'),
                  'correo2'  => $this->request->getPost('correo2'),
                  'responsable2' => $this->request->getPost('responsable2')
               );
               $this->familiaModel->guardarDatosFamilia($values, $action);
               break;
         endswitch;
         $jsonData->set('listaFamilias', $this->familiaModel->listarFamilias());
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }
}
