<?php

namespace App\Helpers;

class Funciones
{

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