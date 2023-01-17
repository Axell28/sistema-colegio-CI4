<?php

namespace App\Helpers;

class JsonData
{

   private $data;

   public function __construct()
   {
      $this->data = array();
   }

   public function set(string $key, $valor)
   {
      $this->data[$key] = $valor;
   }

   public function get()
   {
      return $this->data;
   }
}
