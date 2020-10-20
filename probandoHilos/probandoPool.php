<?php
ini_set("date.timezone", "America/Argentina/Buenos_Aires");
include_once("MiHilo.php");

// el factor de concurrencia (el número de subprocesos en los que se ejecuta el grupo) se especifica al crear el grupo.
$miPool = new Pool(15);

for ($i = 0; $i < 15; ++$i) {
    $miPool->submit(new MiHilo($i));
}

while ($miPool->collect());

$miPool->shutdown();
?>