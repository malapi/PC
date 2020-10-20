<?php
class Tarea extends Threaded {
  

    
    public function escribeNumerosHasta($algunParam,$elid) {
         for ($i = 1; $i <= $algunParam; $i++) {
            echo " <iteracion sub:> ". $i." el hilo ".$elid." \n";
        }
    }
}

?>