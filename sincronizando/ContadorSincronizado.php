<?php
class ContadorSincronizado extends Thread{
     public $i = 0;
     public function __construct()  {}
     public function run(){
      $this->synchronized(function () {
         for ($i = 0; $i < 10; ++$i) {
             ++$this->i;
         }
     });
     }
}
$obj = new ContadorSincronizado();
$obj->start();
$obj->synchronized(function ($obj) {
   for ($i = 0; $i < 10; ++$i) {
      ++$obj->i;
   }
}, $obj);
$obj->join();
var_dump($obj->i);

?>