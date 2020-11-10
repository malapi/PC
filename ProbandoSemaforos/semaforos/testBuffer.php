<?php
error_reporting(0);
error_reporting(E_ERROR |  E_PARSE);
include_once "HiloConsumidor.php";
include_once "HiloProductor.php";
include_once "Buffer.php";

$miBuffer = new Buffer();
$miBuffer->producir(1,22);

$miBuffer->consumir(2222);
$miBuffer->producir(1,22);
$miBuffer->consumir(33333);
$miBuffer->producir(1,22);
$miBuffer->consumir(4444);
//$miBuffer->producir(2,23);
//$miBuffer->consumir(3333);

// php /usr/src/myapp/semaforos/testBuffer.php
// php /usr/src/myapp/semaforos/TestProductorConsumidor.php
// php /usr/src/myapp/concurrencia

?>