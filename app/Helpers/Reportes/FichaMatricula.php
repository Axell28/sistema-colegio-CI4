<?php

namespace App\Helpers\Reportes;

use App\Models;

class FichaMatricula
{
     private $fpdf;
     public $params;
     public $wt = 276;

     public function __construct()
     {
          $this->fpdf = new \FPDF();
          $this->fpdf->SetTitle("Ficha de Matrícula", true);
     }

     public function execute($params)
     {
          $this->params = $params;
          $this->fpdf->AddPage();
          $this->fpdf->SetMargins(20, 30, 20);
          $this->fpdf->SetAutoPageBreak(true, 10);
          $this->fpdf->SetFillColor(245, 245, 245);
          $this->encabezado();
          $this->cuerpo();
          $this->fpdf->Output('Constancia_matricula.pdf', 'I');
          $this->fpdf->Output();
     }

     public function cuerpo() {
          $datosMatricula = $this->obtenerDatosMatricula();
          $datosAlumno = $this->obtenerDatosAlumno($datosMatricula['codalu']);
          $datosFamiliaRep = $this->obtenerFamiliarRep($datosMatricula['codalu']);
          $this->fpdf->SetY(30);
          $this->fpdf->SetDrawColor(100, 100, 100);
          $this->fpdf->SetFont('Arial', 'B', 13);
          $this->fpdf->Cell(0, 8, utf8_decode("Datos de Matrícula"), 0, 1, "L");
          $this->fpdf->Ln(3);
          $this->fpdf->SetFont('Arial', '', 10);
          $this->fpdf->Cell(50, 9, utf8_decode(" Año académico"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosMatricula['anio'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Fecha de Matrícula"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosMatricula['fecmat'], 1, 1, "L");
          
          $this->fpdf->Cell(50, 9, utf8_decode(" Grado"), 1, 0, "L");
          $this->fpdf->Cell(60, 9, "  " . $datosMatricula['salonnom'], 1, 0, "L");
          $this->fpdf->Cell(27, 9, utf8_decode(" Sección"), 1, 0, "L");
          $this->fpdf->Cell(30, 9, "  " . $datosMatricula['secciondes'], 1, 1, "L");

          $this->fpdf->Cell(50, 9, utf8_decode(" Código de Matrícula"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosMatricula['codmat'], 1, 1, "L");

          $this->fpdf->SetFont('Arial', 'B', 13);
          $this->fpdf->Ln(8);
          $this->fpdf->Cell(0, 8, utf8_decode("Datos del Alumno(a)"), 0, 1, "L");
          $this->fpdf->Ln(3);
          $this->fpdf->SetFont('Arial', '', 10);
          $this->fpdf->Cell(50, 9, utf8_decode(" Código"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosMatricula['codalu'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Apellidos y Nombres"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosAlumno['nomcomp'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Doc. Identidad"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosAlumno['numdoc'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Fec. Nacimiento"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosAlumno['fecnac'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Dirección"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosAlumno['direccion'], 1, 1, "L");


          $this->fpdf->SetFont('Arial', 'B', 13);
          $this->fpdf->Ln(8);
          $this->fpdf->Cell(0, 8, utf8_decode("Datos del Familiar o Apoderado (responsable)"), 0, 1, "L");
          $this->fpdf->Ln(3);
          $this->fpdf->SetFont('Arial', '', 10);
          $this->fpdf->Cell(50, 9, utf8_decode(" Apellidos y Nombres"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosFamiliaRep['nomcomp'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Parentesco"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosFamiliaRep['parentdes'], 1, 1, "L");
          $this->fpdf->Cell(50, 9, utf8_decode(" Doc. Identidad"), 1, 0, "L");
          $this->fpdf->Cell(117, 9, "  " . $datosFamiliaRep['numdoc'], 1, 1, "L");
     }

     public function encabezado()
     {
          $this->fpdf->SetFont('Arial', 'B', 17);
          $this->fpdf->Image(base_url('uploads/local/escudo.png'), 10, 8, 0, 16);
          $this->fpdf->Cell(0, 7, utf8_decode("FICHA DE MATRÍCULA - 2023"), 0, 1, "C");
          $this->fpdf->SetFont('Arial', '', 9);
     }

     private function obtenerFamiliarRep($codalu) {
          $alumnoModel = new Models\AlumnoModel();
          $datosRepresentante = $alumnoModel->listarFamResponsable(array('codalu' => $codalu), true);
          return $datosRepresentante;
     }

     private function obtenerDatosAlumno($codalu) {
          $alumnoModel = new Models\AlumnoModel();
          $datosAlumno = $alumnoModel->obtenerDatosAlumno($codalu);
          return $datosAlumno;
     }

     private function obtenerDatosMatricula()
     {
          $matriculaModel = new Models\MatriculaModel();
          $datosMatricula = $matriculaModel->obtenerDatosMatricula(array('codmat' => $this->params['codmat']));
          return $datosMatricula;
     }
}
