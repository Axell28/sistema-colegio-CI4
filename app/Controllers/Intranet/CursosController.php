<?php

namespace App\Controllers\Intranet;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class CursosController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      return view('intranet/cursos/index', $viewData->get());
   }
}
