<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class DocumentoModel extends Model
{
   protected $table      = 'documento';
   protected $primaryKey = 'coddoc';

   protected $useAutoIncrement = true;
   protected $returnType     = 'array';
   protected $useSoftDeletes = false;

   protected $allowedFields = ['codcat', 'nombre', 'descripcion', 'extension', 'usureg', 'fecreg', 'usumod', 'fecmod', 'estado'];
   protected $useTimestamps = true;

   protected $dateFormat    = 'datetime';
   protected $createdField  = 'fecreg';
   protected $updatedField  = 'fecmod';

   public function listarDocumentos()
   {
      $query = $this->db->table('documento d')->select(array(
         'd.codcat',
         'dc.nombre AS catgnomb',
         'd.coddoc',
         'd.nombre AS docunomb',
         'd.extension'
      ))->join('documento_ctg dc', 'dc.codcat = d.codcat', 'INNER');
      $query->orderBy("dc.nombre, d.nombre");
      $result = $query->get()->getResultArray();
      $pivot = array();
      foreach ($result as $value) {
         $pivot[$value['codcat']]['codcat'] = $value['codcat'];
         $pivot[$value['codcat']]['nombre'] = $value['catgnomb'];
         $pivot[$value['codcat']]['documentos'][] = $value;
      }
      return $pivot;
   }

   public function listarDocumentosxCateg($codcat)
   {
      $query = $this->select(array(
         'coddoc',
         'codcat',
         'nombre',
         'descripcion',
         'extension',
         'estado',
         'usureg',
         "DATE_FORMAT(fecreg, '%d/%m/%Y %h:%i %p') AS fecreg"
      ))->where('codcat', $codcat)->orderBy('nombre');
      return $query->findAll();
   }

   public function guardarDocumento(array $values, $action = 'I')
   {
      if ($action == 'I') {
         $coddoc = $this->insert(array(
            'codcat' => $values['codcat'],
            'nombre' => $values['nombre'],
            'extension' => $values['extension'],
            'usureg' => USUARIO
         ), true);
         return str_pad($coddoc, 3, '0', STR_PAD_LEFT);
      }
      $this->set(array(
         'codcat' => $values['codcat'],
         'nombre' => $values['nombre'],
         'usumod' => USUARIO,
      ))->update($values['coddoc']);
      return $values['coddoc'];
   }
}
