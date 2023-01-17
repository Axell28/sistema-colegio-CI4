<?php

namespace App\Helpers\Reportes;

use App\Models;

class ReporteDeUsuarios
{

   private $fpdf;
   public $wt = 276;

   public function __construct()
   {
      $this->fpdf = new \FPDF("L");
      $this->fpdf->SetTitle("Reporte de Usuarios", true);
   }

   public function execute(array $params = array())
   {
      $this->fpdf->AddPage();
      $this->fpdf->SetMargins(10, 10, 10);
      $this->fpdf->SetAutoPageBreak(10);
      $this->fpdf->SetFillColor(245, 245, 245);

      $pivotUsuarios = $this->pivotListadoUsuarios($params);

      $this->encabezado();
      $this->cuerpo($pivotUsuarios);
      $this->fpdf->Output('I', 'Reporte_de_usuarios.pdf', true);
      $this->fpdf->Output();
   }

   private function encabezado()
   {
      $this->fpdf->SetFont('Arial', 'B', 14);
      $this->fpdf->Image(base_url('uploads/local/escudo.png'), 10, 6, 0, 16);
      $this->fpdf->Cell(0, 7, "REPORTE DE USUARIOS DEL SISTEMA", 0, 1, "C");
      $this->fpdf->SetFont('Arial', '', 9);
      $this->fpdf->Ln(10);
   }

   private function cuerpo($pivotUsuarios)
   {
      $h = 7;
      $this->fpdf->SetFont('Arial', 'B', 7);
      $this->fpdf->Cell(34, $h, "USUARIO", 1, 0, 'C', true);
      $this->fpdf->Cell(74, $h, "NOMBRE", 1, 0, 'C', true);
      $this->fpdf->Cell(64, $h, "PERFIL", 1, 0, 'C', true);
      $this->fpdf->Cell(40, $h, "FEC. REGISTRO", 1, 0, 'C', true);
      $this->fpdf->Cell(40, $h, utf8_decode("ULTIMA CONEXIÓN"), 1, 0, 'C', true);
      $this->fpdf->Cell(24, $h, "ESTADO", 1, 1, 'C', true);
      $this->fpdf->SetFont('Arial', '', 7);

      foreach ($pivotUsuarios as $value) {
         $this->fpdf->Cell(34, $h, "  " . $value['usuario'], 1, 0, 'C');
         $this->fpdf->Cell(74, $h, "  " . $value['nombre'], 1, 0, 'L');
         $this->fpdf->Cell(64, $h, "  " . utf8_decode($value['perfil_nomb']), 1, 0, 'L');
         $this->fpdf->Cell(40, $h, "  " . $value['fecreg'], 1, 0, 'C');
         $this->fpdf->Cell(40, $h, "  " . $value['ultcon'], 1, 0, 'C');
         $this->fpdf->Cell(24, $h, "  " . $value['estado_des'], 1, 1, 'C');
      }
      $this->fpdf->Ln(5);
      $this->fpdf->SetFont('Arial', 'B', 8);
      $this->fpdf->Cell(0, $h, "Total de usuarios: " . count($pivotUsuarios), 0, 1, 'R');
   }

   private function pivotListadoUsuarios($params)
   {
      $usuarioModel = new Models\UsuarioModel();
      return $usuarioModel->listarUsuarios(array(
         'estado' => $params['estado'],
         'perfil' => $params['perfil']
      ));
   }
}
