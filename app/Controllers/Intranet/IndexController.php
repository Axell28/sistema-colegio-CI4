<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class IndexController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      $publicacionModel = new Models\PublicacionModel();
      $viewData->set('listaPublicaciones', $publicacionModel->listarPublicacionesIndex());
      return view('intranet/home/index', $viewData->get());
   }
}
