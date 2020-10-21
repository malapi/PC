<?php
ini_set("date.timezone", "America/Argentina/Buenos_Aires");
include_once("MiHilo.php");

echo " probandoWorker.php>> La siguiente prueba se inicia: " . (new DateTime())->format('H:i:s:v');

$miWorker = new Worker();
$miWorker->start();
$cant_hilos = 5;
for ($i = 1; $i <=$cant_hilos; ++$i) {
    $miWorker->stack(new MiHilo($i));
}

while ($miWorker->collect()){

}

$miWorker->shutdown();

printf("probandoWorker.php>> Finalizo la ejecución del script ppal:%s \n", (new DateTime())->format('H:i:s:v'));

?>