<?php

namespace App\Helpers;

class Funciones
{

   public static function numeroTipoPeriodo($periodo, $upper = false)
   {
      $valor = "";
      switch ($periodo) {
         case '1':
            $valor = "1er";
            break;
         case '2':
            $valor = "2do";
            break;
         case '3':
            $valor = "3er";
            break;
         case '4':
            $valor = "4to";
      }
      return $upper ? strtoupper($valor) : $valor;
   }

   public static function eliminarDirectorio($pathDir)
   {
      if (!$dir = @opendir($pathDir)) return;
      while (false !== ($item = readdir($dir))) {
         if ($item != '.' && $item != '..') {
            if (!@unlink($pathDir . DIRECTORY_SEPARATOR . $item)) {
               self::eliminarDirectorio($pathDir . DIRECTORY_SEPARATOR . $item);
            }
         }
      }
      closedir($dir);
      @rmdir($pathDir);
   }

   public static function eliminarArchivosxItemAuv($pathGrupo, $item)
   {
      $archivos = glob($pathGrupo . DIRECTORY_SEPARATOR . "F_" . $item . "_*");
      foreach ($archivos as $value) {
         if(is_file($value)) {
            unlink($value);
         }
      }
   }

   public static function obtenerTamanioArchivo($filePath = null, $fileBytes = 0)
   {
      $bytes = $fileBytes;
      if (!empty($filePath)) {
         $bytes = filesize($filePath);
      }
      $label = array('B', 'KB', 'MB', 'GB');
      for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
      return (round($bytes, 2) . ' ' . $label[$i]);
   }

   public static function minificarHtml(string $html)
   {
      $search = array(
         '/(\n|^)(\x20+|\t)/',
         '/(\n|^)\/\/(.*?)(\n|$)/',
         '/\n/',
         '/\<\!--.*?-->/',
         '/(\x20+|\t)/',
         '/\>\s+\</',
         '/(\"|\')\s+\>/',
         '/=\s+(\"|\')/'
      );
      $replace = array("\n", "\n", " ", "", " ", "><", "$1>", "=$1");
      $htmlmin = preg_replace($search, $replace, $html);
      return $htmlmin;
   }

   public static function obtenerTimeStamp($date = 'now')
   {
      $datetime = new \DateTime($date);
      return $datetime->getTimestamp();
   }

   public static function limpiarString($cadena)
   {
      $cadena = str_replace(
         array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
         array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
         $cadena
      );

      $cadena = str_replace(
         array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
         array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
         $cadena
      );

      $cadena = str_replace(
         array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
         array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
         $cadena
      );

      $cadena = str_replace(
         array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
         array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
         $cadena
      );

      $cadena = str_replace(
         array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
         array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
         $cadena
      );

      $cadena = str_replace(
         array('ñ', 'Ñ', 'ç', 'Ç'),
         array('n', 'N', 'c', 'C'),
         $cadena
      );
      return $cadena;
   }
   
   public static function crearArbolMenuHtml(array $arrayMenuHtml, $activeMenu = null)
   {
      $pivotMenu = self::verificarMenuActivo($arrayMenuHtml, $activeMenu);
      $html = '';
      foreach ($pivotMenu as $value) {
         $icono = !empty($value['icono']) ? $value['icono'] : 'fas fa-server';
         $parent = count($value['submenu']) > 0;
         $menuActivo = isset($value['active']) ? 'active' : null;
         $link = $parent ? '#' : $value['link'];
         $html .= '<li class="' . $menuActivo . '"><a href="' . $link . '"><i class="' . $icono . '"></i><span class="links_name">' . $value['nombre'] . '</span>';
         $html .= $parent ? '<span class="far fa-chevron-right arrow"></span>' : '';
         $html .= '</a>';
         if ($parent) {
            $displayMenu = isset($value['active']) ? 'display: block;' : null;
            $html .= '<ul class="sub" style="' . $displayMenu . '">';
            foreach ($value['submenu'] as $submenu) {
               $subMenuActivo = isset($submenu['active']) ? 'active' : null;
               $icono = !empty($submenu['icono']) ? $submenu['icono'] : 'fas fa-server';
               $html .= '<li class="' . $subMenuActivo . '">';
               $html .= '<a href="' . $submenu['link'] . '"><i class="' . $icono . '"></i><span class="links_name">' . $submenu['nombre'] . '</span></a>';
               $html .= '</li>';
            }
            $html .= '</ul>';
         }
         $html .= '</li>';
      }
      return $html;
   }

   public static function formatCodUbigeo($dept = '', $prov = '', $dist = '')
   {
      $coord = null;
      if (!empty($dept)) {
         $coord = $dept;
      }
      if (!empty($prov)) {
         $coord = $prov;
      }
      if (!empty($dist)) {
         $coord = $dist;
      }
      return $coord;
   }

   public static function generarBgRandom()
   {
      $colores = array(
         'F9F54B',
         '8BF5FA',
         'FCE22A',
         'F94A29',
         'AAE3E2',
         'FFD4B2',
         'FFF6BD',
         'CEEDC7',
         '86C8BC',
         'E8F3D6',
         'FFDCA9',
         'FCF9BE',
         'C4DFAA',
         'F5F0BB'
      );
      $max = count($colores) - 1;
      return "#" . $colores[rand(0, $max)];
   }

   private static function verificarMenuActivo(array $pivotMenu, $activeMenu)
   {
      foreach ($pivotMenu as &$value) :
         if (count($value['submenu']) > 0) {
            foreach ($value['submenu'] as &$submenu) {
               if ($submenu['url'] == $activeMenu) {
                  $value['active']   = TRUE;
                  $submenu['active'] = TRUE;
                  break;
               }
            }
         } else if (!empty($value['url'])) {
            if ($value['url'] == $activeMenu) {
               $value['active'] = TRUE;
               break;
            }
         }
      endforeach;
      return $pivotMenu;
   }
}
