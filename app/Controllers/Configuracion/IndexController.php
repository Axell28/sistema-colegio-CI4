<?php

namespace App\Controllers\Configuracion;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class IndexController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      return view('configuracion/home/index', $viewData->get());
   }
}
