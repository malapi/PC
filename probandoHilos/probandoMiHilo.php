<?php
ini_set("date.timezone", "America/Argentina/Buenos_Aires");
include_once("MiHilo.php");

echo " test.php>> La siguiente prueba se inicia: " . (new DateTime())->format('H:i:s:v');

// $n representa la cantidad de hilos a crear
$cant_hilos = 5;
for ($i = 1; $i <= $cant_hilos; $i++) {
    $t[$i] = new MiHilo($i);
    if($t[$i]->start()){
        printf(" test.php>> Se inicio correctamente el hilo ID:%s \n", $t[$i]->getId_hilo());        
    }
  }
printf(" test.php>> Finalizo la creacion de los hilos del script ppal:%s \n", (new DateTime())->format('H:i:s:v'));

for ($i = 1; $i <= $cant_hilos; $i++) {
    if (! ($t[$i]->isJoined())){       
        $t[$i]->join();
        printf(" test.php>> Se une a la ejecucion el hilo: %s \n", $t[$i]->getId_hilo());
    }
}

for ($i = 1; $i <= $cant_hilos; $i++) {
 
    echo "----------------------------------------------------------- \n";
    printf("test.php>> El hilo ID:%s se CREA : %s\n", $i,  $t[$i]->getCrea()->format('H:i:s:v'));
    printf("test.php>> El hilo ID:%s INICIO EJECUCION: %s \n",$t[$i]->getId_hilo(),  $t[$i]->getInicia_run()->format('H:i:s:v'));
    printf("test.php>> El hilo ID:%s FINALIZO EJECUCION: %s \n",$t[$i]->getId_hilo(),   $t[$i]->getFin_run()->format('H:i:s:v'));
    echo  "\n";
}
printf("test.php>> Finalizo la ejecución del script ppal:%s \n", (new DateTime())->format('H:i:s:v'));
?>