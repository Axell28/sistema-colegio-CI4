<?php

namespace App\Helpers\Reportes;

use App\Models;

class ReporteAlumnos
{
   private $fpdf;
   public $params;
   public $wt = 276;

   public function __construct()
   {
      $this->fpdf = new \FPDF('L');
      $this->fpdf->SetTitle("Reporte de Alumnos", true);
   }

   public function execute(array $params = array())
   {
      $this->fpdf->AddPage();
      $this->fpdf->SetMargins(7, 10, 7);
      $this->fpdf->SetAutoPageBreak(true, 10);
      $this->fpdf->SetFillColor(245, 245, 245);
      $this->fpdf->SetDrawColor(100, 100, 100);
      $this->params = $params;
      $pivotAlumnos = $this->pivotListadoAlumnos();
      $this->encabezado();
      $this->cuerpo($pivotAlumnos);
      $this->fpdf->Output('Reporte_de_alumnos.pdf', 'I');
      $this->fpdf->Output();
   }

   private function encabezado()
   {
      $estado = !empty($this->params['estado']) ? ($this->params['estado'] == 'A' ? 'Activo' : 'Inactivo') : 'Todos';
      $this->fpdf->SetFont('Arial', 'B', 14);
      $this->fpdf->Image(base_url('uploads/local/escudo.png'), 10, 6, 0, 16);
      $this->fpdf->Cell(0, 7, "REPORTE GENERAL DE ALUMNOS", 0, 1, "C");
      $this->fpdf->SetFont('Arial', '', 9);
      $this->fpdf->SetX(($this->wt / 2) - 29);
      $this->fpdf->Cell(26, 7, "Nivel: " . (!empty($this->params['nivel']) ? $this->params['nivel'] : 'Todos'), 0, 0, "C");
      $this->fpdf->Cell(26, 7, "Sexo: " . (!empty($this->params['sexo']) ? $this->params['sexo'] : 'Todos'), 0, 0, "C");
      $this->fpdf->Cell(26, 7, "Estado: " . (!empty($this->params['estado']) ? $this->params['estado'] : 'Todos'), 0, 1, "C");
      $this->fpdf->Ln(4);
   }

   private function cuerpo($pivotAlumnos)
   {
      $h = 7;
      $this->fpdf->SetFont('Arial', 'B', 7);
      $this->fpdf->Cell(21, $h, utf8_decode("CÓDIGO"), 1, 0, 'C', true);
      $this->fpdf->Cell(63, $h, "APELLIDOS Y NOMBRES", 1, 0, 'C', true);
      $this->fpdf->Cell(25, $h, "DOC. IDENTIDAD", 1, 0, 'C', true);
      $this->fpdf->Cell(30, $h, "FEC. NACIMIENTO", 1, 0, 'C', true);
      $this->fpdf->Cell(15, $h, "SEXO", 1, 0, 'C', true);
      $this->fpdf->Cell(30, $h, utf8_decode("AÑO DE INGRESO"), 1, 0, 'C', true);
      $this->fpdf->Cell(25, $h, "MATRICULADO", 1, 0, 'C', true);
      $this->fpdf->Cell(25, $h, "NIVEL", 1, 0, 'C', true);
      $this->fpdf->Cell(30, $h, "GRADO", 1, 0, 'C', true);
      $this->fpdf->Cell(18, $h, "ESTADO", 1, 1, 'C', true);
      $this->fpdf->SetFont('Arial', '', 7);

      foreach ($pivotAlumnos as $value) {
         $this->fpdf->Cell(21, $h, $value['codalu'], 1, 0, 'C');
         $this->fpdf->Cell(63, $h, " " . utf8_decode($value['nomcomp']), 1, 0, 'L');
         $this->fpdf->Cell(25, $h, $value['numdoc'], 1, 0, 'C');
         $this->fpdf->Cell(30, $h, date('d/m/Y', strtotime($value['fecnac'])), 1, 0, 'C');
         $this->fpdf->Cell(15, $h, $value['sexo'], 1, 0, 'C');
         $this->fpdf->Cell(30, $h, $value['anioing'], 1, 0, 'C');
         $this->fpdf->Cell(25, $h, ($value['matricula'] == 'S' ? 'SI' : 'NO'), 1, 0, 'C');
         $this->fpdf->Cell(25, $h, $value['nivel_des'], 1, 0, 'C');
         $this->fpdf->Cell(30, $h, utf8_decode($value['grado_des']), 1, 0, 'C');
         $this->fpdf->Cell(18, $h, $value['estado_des'], 1, 1, 'C');
      }

      $this->fpdf->Ln(5);
      $this->fpdf->SetFont('Arial', '', 9);
      $this->fpdf->Cell(0, $h, "Total de Alumnos: " . count($pivotAlumnos), 0, 1, 'R');
   }

   private function pivotListadoAlumnos()
   {
      $alumnoModel = new Models\AlumnoModel();
      return $alumnoModel->listarAlumnos(array(
         'sexo'  => $this->params['sexo'],
         'nivel' => $this->params['nivel'],
         'estado' => $this->params['estado']
      ));
   }
}
