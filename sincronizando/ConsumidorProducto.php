<?php

class Contenedor extends Volatile {
    public $cantidad = 0;public $maximo = 5;public $minimo = 0;
    public $acciones =[]; public $hilos =[];
    public $cantidadmaximadiaria =25;
    
    public function  producir(){
        $this->cantidad++;
        $this->acciones[] = "producir";
    }

    public function  consumir(){
        if($this->cantidad>0){
            $this->cantidad--;
            $this->acciones[] = "consumir";
        }
    }

    public function  hayparacomer(){
        return $this->cantidad > $this->minimo;
    }
    public function  estacompleto(){
        return $this->cantidad >= $this->maximo;
    }
    public function  hayespacio(){
        return $this->cantidad <= $this->maximo;
    }
    public function  estavacio(){
        return $this->cantidad <= $this->minimo;
    }
    public function dentrodejornada(){
        $this->cantidadmaximadiaria--;
        return $this->cantidadmaximadiaria >=0;
    }
}
class HiloProductorConsumidor extends Thread {
    public function __construct()  {
        $this->info = new Contenedor();
   }
    public function run() {
            $this->synchronized(function($hilo){
                echo "Producir ".$hilo->id. PHP_EOL;
                while ($hilo->info->hayespacio() && $hilo->info->dentrodejornada()){
                        $hilo->info->producir();
                        $hilo->info->hilos[] = $hilo->id;
                        echo "Recien Produci ".$hilo->id. PHP_EOL;
                        $hilo->notify();
                        if ($hilo->info->estacompleto() && $hilo->info->dentrodejornada()){
                            echo "Producir Voy a esperar ".$hilo->id. PHP_EOL;
                            $hilo->wait();
                        }
                    }
            }, $this);
       
    }
}
$my = new HiloProductorConsumidor();
$my->start();
$my->synchronized(function($objProductor){
     echo "Consumir 1". PHP_EOL;
     while($objProductor->info->hayparacomer()){
        $objProductor->info->consumir();
        echo "Consumir Recien Consumi 1". PHP_EOL;
        $objProductor->notify();
        if ($objProductor->info->estavacio() && $objProductor->info->dentrodejornada()){
            echo "Consumir 1 Voy a esperar ". PHP_EOL;
            $objProductor->wait();
        }
    }
}, $my);
var_dump($my->join());


