<?php
class ContadorIngenio extends Thread{
     public $i = 0;
     public function __construct()  {
      
    }
     public function run(){
      for ($i = 0; $i < 10; ++$i) {
         ++$this->i;
     }
     }
}
$obj = new ContadorIngenio();
$obj->start();
for ($i = 0; $i < 10; ++$i) {
   ++$obj->i;
}

$obj->join();
var_dump($obj->i);
?>