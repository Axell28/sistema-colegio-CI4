<?php

namespace App\Controllers\Academico;

use App\Models;
use App\Helpers\ViewData;
use App\Helpers\JsonData;
use App\Controllers\BaseController;

class IndexController extends BaseController
{
   public function index()
   {
      $viewData = new ViewData();
      return view('academico/home/index', $viewData->get());
   }
}
