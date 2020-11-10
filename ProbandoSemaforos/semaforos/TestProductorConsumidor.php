<?php
error_reporting(0);
error_reporting(E_ERROR |  E_PARSE);
include_once "Consumidor.php";
include_once "Productor.php";
include_once "Buffer.php";

$miBuffer = new Buffer();
$objProductor = new Productor($miBuffer,1,1);
$objConsumidor = new Consumidor($miBuffer,1,2);

$objProductor->producir();
$objProductor->producir();

/*$objConsumidor->consumir();

$objProductor->producir();
$objConsumidor->consumir();

$objProductor->producir();
$objConsumidor->consumir();
*/?>