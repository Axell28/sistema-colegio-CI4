<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class DocumentosController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $documentoModel = new Models\DocumentoModel();
      $viewData->set('listaDocumentos', $documentoModel->listarDocumentos());
      return view('intranet/documentos/index', $viewData->get());
   }
}
