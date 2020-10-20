<?php
include_once("Tarea.php");
include_once("miHiloPorParametro.php");
$obj_tarea = new Tarea();
$cant_hilos = 5;
for ($i = 1; $i <= $cant_hilos; $i++) {
    $t[$i] = new miHiloPorParametro($obj_tarea,$i);
    if($t[$i]->start() ){
        printf(" test.php>> Se inicio correctamente el hilo ID:%s \n", $t[$i]->getId_hilo());        
    }
 }
 for ($i = 1; $i <= $cant_hilos; $i++) {
    if (! ($t[$i]->isJoined())){       
        $t[$i]->join();
        printf(" test.php>> Se une a la ejecucion el hilo ID: %s \n", $t[$i]->getId_hilo());
    }

}


?>