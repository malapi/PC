<?php
/*date_default_timezone_set("America/Argentina/Buenos_Aires");
echo  DateTime::format("h:i:s:v",microtime(true));*/

$date = new DateTime();
echo $date->format('Y-m-d H:i:s:v');
echo $date->format('Y-m-d H:i:s:v');
?>