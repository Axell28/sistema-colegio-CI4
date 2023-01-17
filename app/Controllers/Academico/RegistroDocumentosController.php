<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class RegistroDocumentosController extends BaseController
{
   public $documentoModel;
   public $pathDocumentos;

   public function __construct()
   {
      $this->documentoModel = new Models\DocumentoModel();
      $this->pathDocumentos = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'documentos';
   }

   public function index()
   {
      $viewData = new ViewData();
      $documentoCtg = new Models\DocumentoCtgModel();
      $viewData->set('listaCategorias', $documentoCtg->listarDocsCategorias());
      return view('academico/registro-documentos/index', $viewData->get());
   }

   public function documento()
   {
      $viewData = new ViewData();
      $documentoCtg = new Models\DocumentoCtgModel();
      $action = $this->request->getPost('action');
      $viewData->isAjax(true);
      $viewData->set('action', $action);
      $viewData->set('listaCategorias', $documentoCtg->listarDocsCategorias());
      return view('academico/registro-documentos/documento', $viewData->get());
   }

   public function json($caso = null)
   {
      $jsonData = new JsonData();
      $documentoCtg = new Models\DocumentoCtgModel();
      try {
         switch ($caso):
            case 'listar-doc':
               $codcat = $this->request->getPost('codcat');
               $jsonData->set('listaDocumentos', $this->documentoModel->listarDocumentosxCateg($codcat));
               break;
            case 'guardar-ctg':
               $action = $this->request->getPost('action');
               $codigo = $this->request->getPost('codigo');
               $nombre = $this->request->getPost('nombre');
               if ($action == 'I') {
                  $documentoCtg->insert(array('nombre' => $nombre, 'estado' => 'A'));
               } else if ($action == 'E') {
                  $documentoCtg->set('nombre', $nombre)->update($codigo);
               }
               $jsonData->set('listaCategorias', $documentoCtg->listarDocsCategorias());
               break;
            case 'eliminar-ctg':
               $codcat = $this->request->getPost('codcat');
               $this->eliminarArchivosxCategoria($codcat);
               $documentoCtg->delete($codcat);
               $jsonData->set('listaCategorias', $documentoCtg->listarDocsCategorias());
               break;
            case 'guardar-doc':
               $action = $this->request->getPost('action');
               $archivo = $this->request->getFile('archivo');
               $subioArchivo = $this->request->getPost('subio_archivo') == 'S';
               if ($action == 'I' && empty($archivo)) {
                  throw new \Exception("Se ha producido un error al cargar el archivo");
               }
               if (!is_dir($this->pathDocumentos)) {
                  if (!mkdir($this->pathDocumentos, 0700)) {
                     throw new \Exception("Se ha producido un error al cargar el archivo");
                  }
               }
               $values = array(
                  'nombre' => $this->request->getPost('nombre'),
                  'codcat' => $this->request->getPost('codcat'),
                  'coddoc' => $this->request->getPost('coddoc'),
                  'extension' => ".{$archivo->guessExtension()}"
               );
               $coddoc = $this->documentoModel->guardarDocumento($values, $action);
               if ($subioArchivo) {
                  $nameArchivo = sprintf("D%s.%s", $coddoc, $archivo->guessExtension());
                  $archivo->move($this->pathDocumentos, $nameArchivo, true);
               }
               $jsonData->set('message', $coddoc);
               $jsonData->set('listaDocumentos', $this->documentoModel->listarDocumentosxCateg($values['codcat']));
               break;
            case 'eliminar-doc':
               $coddoc = $this->request->getPost('coddoc');
               $codcat = $this->request->getPost('codcat');
               $extension = $this->request->getPost('extension');
               $FULLPATH = $this->pathDocumentos . DIRECTORY_SEPARATOR . "D" . $coddoc . $extension;
               if (is_file($FULLPATH)) {
                  unlink($FULLPATH);
               }
               $this->documentoModel->delete($coddoc);
               $jsonData->set('listaDocumentos', $this->documentoModel->listarDocumentosxCateg($codcat));
               break;
         endswitch;
         return $this->response->setJSON($jsonData->get())->setStatusCode(200);
      } catch (\Exception $ex) {
         $jsonData->set('message', $ex->getMessage());
         return $this->response->setJSON($jsonData->get())->setStatusCode(401);
      }
   }

   private function eliminarArchivosxCategoria($codcat)
   {
      $listaDocumentosCod = $this->documentoModel->select('coddoc, extension')->where('codcat', $codcat)->findAll();
      foreach ($listaDocumentosCod as $value) {
         $extension = $value['extension'];
         $FULLPATH = $this->pathDocumentos . DIRECTORY_SEPARATOR . "D" . $value['coddoc'] . $extension;
         if (is_file($FULLPATH)) {
            unlink($FULLPATH);
         }
      }
   }
}
