<?php
class ContadorIngenuo extends Thread{
     public $contador = 0;
     public function __construct()  {}
     public function run(){
      for ($i = 0; $i < 10; ++$i) {
         echo "run ".$this->contador.PHP_EOL;
         $this->contador++;
      } }
}
$obj = new ContadorIngenuo();
$obj->start();
for ($i = 0; $i < 10; ++$i) {
   echo "afuera ".$obj->contador.PHP_EOL;
   $obj->contador++;
}
$obj->join();
echo "FINAL ".$obj->contador.PHP_EOL;
?>