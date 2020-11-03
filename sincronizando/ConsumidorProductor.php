<?php

class Contenedor {
    public $cantidad = 2;
    public $maximo = 5;
    public $minimo = 0;

    public function  incrementa(){
        $this->cantidad++;
    }
}


class Productor extends Thread {


    public function __construct($min,$max)  {
        $this->stock = 0;
        $this->maximo =$max;
        $this->minimo =$min;
        //$this->info = $i;
     
   }
    public function run() {
        $this->synchronized(function($objProductor){
            $objProductor->accion = 'Producir';
            $objProductor->stock++;
            //$objProductor->maximo = 5;
            print_r($objProductor);
            if ($objProductor->stock > $objProductor->maximo)
                $objProductor->wait();
            $objProductor->notify();

            
        }, $this);
    }
}
//$info = new Contenedor();
$my = new Productor(0,5);
$my->start();

$my->synchronized(function($objProductor){
    $objProductor->accion = 'consumir';

    if ($objProductor->stock > $objProductor->minimo){
        $objProductor->stock--;
        print_r($objProductor);
        $objProductor->notify();
    }
        
    if ($objProductor->stock < $objProductor->minimo)
        $objProductor->wait();
   
}, $my);

var_dump($my->join());
