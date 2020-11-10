<?php
class ContadorDePares extends Thread{
     private $contador  = 0;
     private $cuantos;
     public function __construct($Cuantos)  {
         $this->cuantos = $Cuantos;}
     public function run(){
      $this->synchronized(function () {
        for ($i=0;$i < $this->cuantos;$i++) {
             if (($this->contador  % 2) == 0) {
                echo $this->contador." es par ";
                $this->contador++;
                $this->notify();
        }
        if ($this->contador < $this->cuantos && ($this->contador  % 2) != 0) {
                 $this->wait();
             }
         }
     });
     }
}
$yoPares = new ContadorDePares(13);
$yoPares->start();
$yoPares->synchronized(function ($yoImpares) {
    for ($i=0;$i < $yoImpares->cuantos;$i++) {
        if (($yoImpares->contador  % 2) != 0) {
           echo $yoImpares->contador." es impar ";
           $yoImpares->contador++;
           $yoImpares->notify();
        }
        if ($yoImpares->contador < $yoImpares->cuantos && ($yoImpares->contador  % 2) == 0) {
            $yoImpares->wait();
        }
    }
},$yoPares);

$yoPares->join();
?>