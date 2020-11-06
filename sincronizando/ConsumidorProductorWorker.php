<?php

class Contenedor extends Volatile {
    public $cantidad = 0;
    public $maximo = 5;
    public $minimo = 0;
    public $acciones =[];
    public $hilos =[];
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


    public function __construct($index,$c)  {
        $this->id = $index;
       // $this->maximo =$max;
        //$this->minimo =$min;
        $this->info = $c;
        $this->parar = false;
        
     
   }
    public function run() {
            $this->synchronized(function(){
                echo "Producir ".$this->id. PHP_EOL;
                //$objProductor->maximo = 5;
                //print_r($objProductor);
                while($this->info->dentrodejornada()){
                    if ($this->info->hayespacio() ){
                        //$objProductor->accion = 'Producir';
                        $this->info->producir();
                        $this->info->hilos[] = $this->id;
                        echo "Recien Produci ".$this->id. PHP_EOL;
                        //print_r($hilo->info);
                        $this->notify();
                        //echo $objProductor->info->cantidadmaximadiaria. PHP_EOL;
                        
                    }
                    if ($this->info->estacompleto() && $this->info->dentrodejornada()){
                        echo "Producir Voy a esperar ".$this->id. PHP_EOL;
                        $this->wait();
                        
                    }
                }
               
                
            }, $this);
       
    }
    public function then()
    {
        return $this->synchronized(function () {
            echo "Consumir ".$this->id. PHP_EOL;
            //print_r($this->info);
            if($this->info->dentrodejornada()){
                if($this->info->hayparacomer()){
                    $this->info->consumir();
                    //print_r($this->info);
                    echo "Consumir Recien Consumi ".$this->id. PHP_EOL;
                    //print_r($objProductor);
                    $this->notify();
                }
                if ($this->info->estavacio() ){
                    echo "Consumir ".$this->id." Voy a esperar ". PHP_EOL;
                    $this->wait();
                }
            }
            
           },$this);
    }

    }
$miWorker = new Worker();
$miWorker->start();
$cant_hilos = 5;
$info = new Contenedor();
for ($i = 1; $i <=$cant_hilos; ++$i) {
    $my = new HiloProductorConsumidor($i,$info);
    $miWorker->stack($my);
    $my->then(function () {
        //echo $results;
     });
}

while ($miWorker->collect()){}

$miWorker->shutdown();
