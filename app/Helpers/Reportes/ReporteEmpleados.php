<?php

namespace App\Helpers\Reportes;

use App\Helpers\Funciones;
use App\Models;

class ReporteEmpleados
{

   private $fpdf;
   public $params;
   public $wt = 276;

   public function __construct()
   {
      $this->fpdf = new \FPDF("L");
      $this->fpdf->SetTitle("Reporte de Personal", true);
   }

   public function execute(array $params = array())
   {
      $this->fpdf->AddPage();
      $this->fpdf->SetMargins(7, 10, 7);
      $this->fpdf->SetAutoPageBreak(true, 10);
      $this->fpdf->SetFillColor(245, 245, 245);
      $this->fpdf->SetDrawColor(100, 100, 100);
      $this->params = $params;

      $pivotEmpleados = $this->pivotListadoEmpleados();

      $this->encabezado();
      $this->cuerpo($pivotEmpleados);
      $this->fpdf->Output('Reporte_de_personal.pdf', 'I');
      $this->fpdf->Output();
   }

   private function encabezado()
   {
      $estado = !empty($this->params['estado']) ? ($this->params['estado'] == 'A' ? 'Activo' : 'Inactivo') : 'Todos';
      $this->fpdf->SetFont('Arial', 'B', 14);
      $this->fpdf->Image(base_url('uploads/local/escudo.png'), 10, 6, 0, 16);
      $this->fpdf->Cell(0, 7, "LISTADO DE PERSONAL", 0, 1, "C");
      $this->fpdf->SetFont('Arial', '', 9);
      $this->fpdf->Cell(0, 7, "Estado: " . $estado, 0, 1, "C");
      $this->fpdf->Ln(5);
   }

   private function cuerpo($pivotEmpleados)
   {
      $h = 7;
      $this->fpdf->SetFont('Arial', 'B', 7);
      $this->fpdf->Cell(21, $h, $this->convert_to_utf8('CÓDIGO'), 1, 0, 'C', true);
      $this->fpdf->Cell(60, $h, "APELLIDOS Y NOMBRES", 1, 0, 'C', true);
      $this->fpdf->Cell(25, $h, "DOC. IDENTIDAD", 1, 0, 'C', true);
      $this->fpdf->Cell(29, $h, "FEC. NACIMIENTO", 1, 0, 'C', true);
      $this->fpdf->Cell(26, $h, "CELULAR", 1, 0, 'C', true);
      $this->fpdf->Cell(37, $h, "EMAIL", 1, 0, 'C', true);
      $this->fpdf->Cell(35, $h, $this->convert_to_utf8("ÁREA LABORAL"), 1, 0, 'C', true);
      $this->fpdf->Cell(29, $h, "FEC. INGRESO", 1, 0, 'C', true);
      $this->fpdf->Cell(20, $h, "ESTADO", 1, 1, 'C', true);
      $this->fpdf->SetFont('Arial', '', 7);

      foreach ($pivotEmpleados as $value) {
         $this->fpdf->Cell(21, $h, $value['codemp'], 1, 0, 'C');
         $this->fpdf->Cell(60, $h, " " . $this->convert_to_utf8($value['nomcomp']), 1, 0, 'L');
         $this->fpdf->Cell(25, $h, $value['numdoc'], 1, 0, 'C');
         $this->fpdf->Cell(29, $h, date('d/m/Y', Funciones::obtenerTimeStamp($value['fecnac'])), 1, 0, 'C');
         $this->fpdf->Cell(26, $h, !empty($value['celular1']) ? $value['celular1'] : "", 1, 0, 'C');
         $this->fpdf->Cell(37, $h, " " . $value['email'], 1, 0, 'L');
         $this->fpdf->Cell(35, $h, $this->convert_to_utf8($value['area_des']), 1, 0, 'C');
         $this->fpdf->Cell(29, $h, date('d/m/Y', Funciones::obtenerTimeStamp($value['fecing'])), 1, 0, 'C');
         $this->fpdf->Cell(20, $h, $value['estado_des'], 1, 1, 'C');
      }

      $this->fpdf->Ln(5);
      $this->fpdf->SetFont('Arial', 'B', 9);
      $this->fpdf->Cell(0, $h, "Total de personal: " . count($pivotEmpleados), 0, 1, 'R');
   }

   private function pivotListadoEmpleados()
   {
      $empleadoModel = new Models\EmpleadoModel();
      return $empleadoModel->listarEmpleados(array(
         'area' => $this->params['area'],
         'estado' => $this->params['estado']
      ));
   }

   private function convert_to_utf8(string $cadena)
   {
      if (empty($cadena)) return "";
      return iconv('UTF-8', 'windows-1252', $cadena);
   }
}
