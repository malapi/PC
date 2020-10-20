<?php
ini_set("date.timezone", "America/Argentina/Buenos_Aires");
include_once("MiHilo.php");

$miWorker = new Worker();
$miWorker->start();

for ($i = 0; $i < 15; ++$i) {
    $miWorker->stack(new MiHilo($i));
}

while ($miWorker->collect());

$miWorker->shutdown();
?>