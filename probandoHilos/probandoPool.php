<?php
ini_set("date.timezone", "America/Argentina/Buenos_Aires");
include_once("MiHilo.php");

echo " probandoPool.php>> La siguiente prueba se inicia: " . (new DateTime())->format('H:i:s:v');

// el factor de concurrencia (el número de subprocesos en los que se ejecuta el grupo) se especifica al crear el grupo.
$miPool = new Pool(5);
$cant_hilos = 5;
for ($i = 0; $i < $cant_hilos; ++$i) {
    $miPool->submit(new MiHilo($i));
}

while ($miPool->collect());

$miPool->shutdown();

printf("probandoPool.php>> Finalizo la ejecución del script ppal:%s \n", (new DateTime())->format('H:i:s:v'));

?>