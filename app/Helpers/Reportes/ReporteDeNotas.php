<?php

namespace App\Helpers\Reportes;

use App\Models;

class ReporteDeNotas
{

     private $fpdf;
     public $params;
     public $wt = 193;
     public $wn = 20;
     public $wd1;

     public function __construct()
     {
          $this->fpdf = new \FPDF();
          $this->fpdf->SetTitle("Reporte de Notas", true);
     }

     public function execute(array $params = array())
     {
          $this->fpdf->AddPage();
          $this->fpdf->SetMargins(8, 10, 8);
          $this->fpdf->SetAutoPageBreak(true, 10);
          $this->fpdf->SetFillColor(245, 245, 245);
          $this->fpdf->SetDrawColor(100, 100, 100);
          $this->params = $params;
          $alumnoModel = new Models\AlumnoModel();
          $matriculaModel = new Models\MatriculaModel();
          $anioPeriodoModel = new Models\AnioPeriodoModel();
          $datosMatricula = $matriculaModel->obtenerDatosMatricula(array('codalu' => $params['codalu'], 'anio' => ANIO));
          $datosMatricula = $datosMatricula[0];
          $datosAlumno = $alumnoModel->obtenerDatosAlumno($params['codalu']);
          $listaPeriodos = $anioPeriodoModel->listarPeridosCombo(['anio' => ANIO]);
          $listadoCursos = $this->obtenerCurriculaNG(array('nivel' => 'S', 'grado' => '1'));
          $listadoNotas = $this->obtenerNotas(array('codalu' => $params['codalu'], 'salon' => $datosMatricula['salon']));

          foreach ($listadoCursos as &$value) {
               $curso = $value['curso'];
               $value['notas'] = isset($listadoNotas[$curso]) ? $listadoNotas[$curso] : array();
          }

          $this->params['periodos'] = $listaPeriodos;
          $this->wd1 = $this->wt - ($this->wn * count($listaPeriodos)) - $this->wn;
          $this->encabezado(array('salondes' => $datosMatricula['salonnom'], 'alunom' => $datosAlumno['nomcomp']));
          $this->cuerpo($listadoCursos);
          $this->fpdf->Output('Reporte_de_notas.pdf', 'I');
          $this->fpdf->Output();
     }

     public function encabezado(array $params)
     {
          $this->fpdf->SetFont('Arial', 'B', 16);
          $this->fpdf->Image(base_url('uploads/local/escudo.png'), 10, 6, 0, 16);
          $this->fpdf->Cell(0, 9, "REPORTE DE NOTAS", 0, 1, "C");
          $this->fpdf->Ln(6);
          $this->fpdf->SetFont('Arial', '', 9);
          $this->fpdf->Cell(16, 7, "ALUMNO:", 0, 0, "L");
          $this->fpdf->Cell(65, 7, utf8_decode($params['alunom']), 0, 0, "L");
          $this->fpdf->Cell(16, 7, utf8_decode("SALÓN:"), 0, 0, "L");
          $this->fpdf->Cell(40, 7, utf8_decode($params['salondes']), 0, 1, "L");
          $this->fpdf->Ln(4);
     }

     public function encabezadoCurso()
     {
          $w1 = $this->wd1;
          $y1 = $this->fpdf->GetY();
          $this->fpdf->SetFont('Arial', 'B', 9);
          $this->fpdf->Cell($w1, 12, "CURSO", 1, 0, "C", true);
          $x1 = $this->fpdf->GetX();
          $this->fpdf->Cell($this->wn * 4, 6, "PERIODOS", 1, 0, "C", true);
          $this->fpdf->SetXY($x1, $y1 + 6);
          foreach ($this->params['periodos'] as $value) {
               $perdes = $value['periododes'];
               $this->fpdf->Cell($this->wn, 6, $value['periodo'] . " " . substr($perdes, 0, 4), 1, 0, "C", true);
          }
          $x1 = $this->fpdf->GetX();
          $this->fpdf->SetXY($x1, $y1);
          $this->fpdf->Cell($this->wn, 12, "PF", 1, 1, "C", true);
     }

     public function cuerpo($listadoCursos)
     {
          $this->encabezadoCurso();
          $this->fpdf->SetFont('Arial', '', 9);
          foreach ($listadoCursos as $val) {
               $this->fpdf->Cell($this->wd1, 10, " " . $this->convert_to_utf8($val['curnom']), 1, 0, "L");
               foreach ($this->params['periodos'] as $pe) {
                    $periodo = $pe['periodo'];
                    $notas = isset($val['notas'][$periodo]) ? $val['notas'][$periodo] : array();
                    $notaPP = isset($notas['nota_pp']) ? $notas['nota_pp'] : "";
                    if($notaPP > 11) {
                         $this->fpdf->SetTextColor(255, 0, 0);
                    } else if($notaPP <= 10) {
                         $this->fpdf->SetTextColor(0, 0, 255);
                    } 
                    $this->fpdf->Cell($this->wn, 10, $notaPP, 1, 0, "C");
                    $this->fpdf->SetTextColor(0, 0, 0);
               }
               $this->fpdf->Cell($this->wn, 10, "", 1, 1, "C", true);
          }
     }

     public function obtenerCurriculaNG($params)
     {
          $curriculoModel = new Models\CurriculoModel();
          $datosCurricula = $curriculoModel->listarCurriculaNG(array(
               'anio' => ANIO,
               'nivel' => $params['nivel'],
               'grado' => $params['grado'],
               'sin_area' => true
          ));
          return $datosCurricula;
     }

     public function obtenerNotas($params)
     {
          $notasModel = new Models\NotasModel();
          $datosCalificaciones = $notasModel->listarCalificacionesAlumno(array(
               'salon'  => $params['salon'],
               'codalu' => $params['codalu']
          ));
          return $datosCalificaciones;
     }

     private function convert_to_utf8(string $cadena)
     {
          if (empty($cadena)) return "";
          return iconv('UTF-8', 'windows-1252', $cadena);
     }
}
