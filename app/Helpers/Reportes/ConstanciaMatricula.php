<?php

namespace App\Helpers\Reportes;

use App\Models;

class ConstanciaMatricula
{


   private $fpdf;
   public $params;
   public $wt = 276;

   public function __construct()
   {
      $this->fpdf = new \FPDF();
      $this->fpdf->SetTitle("Constancia de Matricula", true);
   }

   public function execute($params)
   {
      $this->fpdf->AddPage();
      $this->fpdf->SetMargins(15, 25, 15);
      $this->fpdf->SetAutoPageBreak(true, 10);
      $this->fpdf->SetFillColor(245, 245, 245);
      $this->params = $params;

      $institucionModel = new Models\InstitucionModel();
      $dataInsitucion = $institucionModel->obtenerNombre();

      $this->params['nombre_local'] = $dataInsitucion;

      $this->encabezado();
      $this->cuerpo();
      $this->fpdf->Output('Constancia_matricula.pdf', 'I');
      $this->fpdf->Output();
   }

   private function encabezado()
   {
      $estado = !empty($this->params['estado']) ? ($this->params['estado'] == 'A' ? 'Activo' : 'Inactivo') : 'Todos';
      $this->fpdf->SetFont('Arial', 'B', 16);
      $this->fpdf->Image(base_url('uploads/local/escudo.png'), 93, 6, 0, 40);
      $this->fpdf->Ln(45);
      $this->fpdf->Cell(0, 7, "CONSTANCIA DE MATRICULA", 0, 1, "C");
      $this->fpdf->SetFont('Arial', '', 9);
      $this->fpdf->Ln(12);
   }

   private function cuerpo()
   {
      $this->fpdf->SetFont('Arial', '', 13);
      $this->fpdf->MultiCell(0, 7, utf8_decode("La dirección de la institución educativa " . $this->params['nombre_local'] . " , por medio de la presente:"), 0, 'C');
      $this->fpdf->Ln(7);
      $this->fpdf->SetFont('Arial', 'B', 14);
      $this->fpdf->Cell(0, 7, "HACE CONSTAR", 0, 1, "C");
      $this->fpdf->SetFont('Arial', '', 13);
      $this->fpdf->Ln(7);
      $this->fpdf->MultiCell(0, 7, utf8_decode("Que, el alumno(a) VALLE SALCEDO, Demo Prueba, identificado con número de identidad 73203078, se encuentra matriculado en este Centro Educativo en el grado 5TO GRADO A."), 0, 'C');
      $this->fpdf->Ln(14);
      $this->fpdf->MultiCell(0, 7, utf8_decode("Se expide la presente a solicitud de la parte interesada, para los fines que estime conveniente."), 0, 'C');
   }
}
