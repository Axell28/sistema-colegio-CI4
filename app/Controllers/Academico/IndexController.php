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
      $alumnoModel = new Models\AlumnoModel();
      $empleadoModel = new Models\EmpleadoModel();
      $familiaModel = new Models\FamiliaModel();
      $salonModel = new Models\SalonModel();
      $totalAlumnos = count($alumnoModel->where('estado', 'A')->findAll());
      $totalEmpleados = count($empleadoModel->where('estado', 'A')->findAll());
      $totalFamilias = count($familiaModel->where('estado', 'A')->findAll());
      $totalSalones = count($salonModel->where('anio', ANIO)->findAll());
      $viewData->set('totalAlumnos', $totalAlumnos);
      $viewData->set('totalEmpleados', $totalEmpleados);
      $viewData->set('totalFamilias', $totalFamilias);
      $viewData->set('totalSalones', $totalSalones);
      return view('academico/home/index', $viewData->get());
   }
}
