<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class MenuModel extends Model
{
   protected $table      = 'menu';
   protected $primaryKey = 'codmenu';
   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;
   protected $allowedFields = ['codmod', 'codmenu', 'parent', 'nombre', 'icono', 'url', 'orden', 'estado'];

   public function guardarRolesPerfil(array $params)
   {
      try {
         $this->db->transBegin();
         $menuRolTable = $this->db->table('menu_rol');
         $menuRolTable->where(array('perfil' => $params['perfil'], 'codmod' => $params['modulo']))->delete();
         if (!empty($params['listaMenus'])) {
            foreach ($params['listaMenus'] as $item) :
               $menuRolTable->insert(array(
                  'perfil'  =>  $params['perfil'],
                  'codmod'  =>  $params['modulo'],
                  'codmenu' =>  $item['codmenu']
               ));
            endforeach;
         }
         $this->db->transCommit();
      } catch (\Exception $ex) {
         $this->db->transRollback();
         throw new \Exception($ex->getMessage());
      }
   }

   public function listarArbolMenu(array $params)
   {
      $query = $this->db->table('menu m')
         ->select(array(
            'm.codmenu',
            'm.codmod',
            'm.parent',
            'm.nombre',
            'm.url',
            'm.icono',
            'CONCAT(md.url, m.url) AS link',
            new RawSql("(SELECT COUNT(codmenu) FROM menu tmp WHERE tmp.parent = m.codmenu) AS nroHijos")
         ))->join('modulo md', 'md.codmod = m.codmod', 'INNER');
      $query->where('m.codmod', $params['modulo']);

      if (isset($params['perfil']) && !SUPER_ADMIN) {
         $query->join('menu_rol mr', new RawSql("mr.codmenu = m.codmenu AND mr.perfil = " . $params['perfil']), 'INNER');
      }

      if (isset($params['parent'])) {
         $query->where('m.parent', $params['parent']);
      } else {
         $query->where(new RawSql("COALESCE(m.parent, '') = ''"));
      }

      $query->where('m.estado', 'A');

      $query->orderBy('m.orden', 'ASC');

      $result = $query->get()->getResultArray();
      foreach ($result as &$menu) :
         $menu['submenu'] = array();
         $isParent = $menu['nroHijos'] > 0;
         if ($isParent) {
            $menu['submenu'] = $this->listarArbolMenu(array(
               'modulo' => $params['modulo'],
               'perfil' => $params['perfil'],
               'parent' => $menu['codmenu']
            ));
         }
      endforeach;
      return $result;
   }

   public function listarMenusAsigPerfil(array $params)
   {
      $query = $this->db->table('menu m')
         ->select(array(
            'm.codmenu',
            'm.codmod',
            'm.parent',
            'm.nombre',
            new RawSql("(SELECT COUNT(codmenu) FROM menu tmp WHERE tmp.parent = m.codmenu) AS nroHijos"),
            new RawSql("(SELECT 'S' FROM menu_rol tm WHERE tm.codmenu = m.codmenu AND tm.perfil = " . $params['perfil'] . ") AS selected")
         ))->join('modulo md', 'md.codmod = m.codmod', 'INNER');
      $query->where('m.codmod', $params['modulo']);

      if (isset($params['parent'])) {
         $query->where('m.parent', $params['parent']);
      } else {
         $query->where(new RawSql("COALESCE(m.parent, '') = ''"));
      }

      $query->where('m.estado', 'A');

      $query->orderBy('m.orden', 'ASC');

      $result = $query->get()->getResultArray();
      foreach ($result as &$menu) :
         $menu['submenu'] = array();
         $menu['checked'] = ($menu['selected'] == 'S');
         $menu['expanded'] = true;
         $isParent = $menu['nroHijos'] > 0;
         if ($isParent) {
            $menu['submenu'] = $this->listarMenusAsigPerfil(array(
               'modulo' => $params['modulo'],
               'perfil' => $params['perfil'],
               'parent' => $menu['codmenu']
            ));
         }
      endforeach;
      return $result;
   }
}
