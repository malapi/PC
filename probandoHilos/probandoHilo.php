<?php
class MiHilo extends Thread{
 
    public function __construct()  {
        echo ' Hola mundo con hilos /n';

    }
     public function run(){
        echo ' Corriendo  hilos en PHP /n';

     }
}

$obj = new MiHilo();
$obj->start();
?>