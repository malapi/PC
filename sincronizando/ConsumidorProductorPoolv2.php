<?php

class Contenedor extends Volatile {
    public $cantidad = 0;
    public $maximo = 5;
    public $minimo = 0;
    public $acciones =[];
    //public $hilos =[];
    public $cantidadmaximadiaria =50;
    public $parar = false;
    

    public function  producir($id){
        $this->cantidad++;
        $this->descontardejornada();
        $this->acciones[] = $id." producir";
    }

    public function  consumir($id){
        if($this->cantidad>0){
            $this->cantidad--;
            $this->acciones[] = $id." consumir";
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
        return $this->cantidadmaximadiaria >=0;
    }
    public function descontardejornada(){
        $this->cantidadmaximadiaria--;
    }
}


class HiloProductorConsumidor extends Thread {


    public function __construct($index,$c)  {
        $this->id = $index;
        $this->info = $c;
        
     
   }
    public function run() {
            $this->synchronized(function(){
                echo "Producir ".$this->id." Jornada: ".$this->info->cantidadmaximadiaria.PHP_EOL;
                //$objProductor->maximo = 5;
                //print_r($objProductor);
                //while($this->info->dentrodejornada()){
                while($this->info->dentrodejornada() && $this->info->hayespacio() ){
                   // if ($this->info->hayespacio() ){
                        //$objProductor->accion = 'Producir';
                        $this->info->producir($this->id);

                        //$this->info->hilos[] = $this->id;
                        echo "Recien Produci ".$this->id." Tengo ".$this->info->cantidad. PHP_EOL;
                        //print_r($hilo->info);
                        $this->notify();
                        //echo $objProductor->info->cantidadmaximadiaria. PHP_EOL;
                        
                  //  }
                    if ($this->info->estacompleto() && $this->info->dentrodejornada()){
                        echo "Producir Voy a esperar ".$this->id." Jornada: ".$this->info->cantidadmaximadiaria.PHP_EOL;
                        //print_r($this->info);
                        $this->wait();
                    }

                 //   if(!$this->info->dentrodejornada()){
                  //      echo "Se termino la Jornada - Productor: ".$this->id." Tengo ".$this->info->cantidad.PHP_EOL;
                   //     $this->notify();
                    //}
                }
                //if(!$this->info->dentrodejornada()){
                //    echo "Afuera : Se termino la Jornada - Productor: ".$this->id." Tengo ".$this->info->cantidad.PHP_EOL;
                //    $this->notify();
                //}
               
                echo "Termine Producir-".$this->id;
            }, $this);
       
    }
    public function then()
    {
        return $this->synchronized(function () {
            echo "Consumir ".$this->id. PHP_EOL;
            //print_r($this->info);
            //while($this->info->dentrodejornada()){

            //while($this->info->dentrodejornada()){
            while($this->info->hayparacomer()&& $this->info->dentrodejornada() ){
                //if($this->info->dentrodejornada() ){
                    $this->info->consumir($this->id);
                    //print_r($this->info);
                    echo "Consumir Recien Consumi ".$this->id." Tengo ".$this->info->cantidad. PHP_EOL;
                    //print_r($objProductor);
                    $this->notify();
                //}
                if ($this->info->estavacio() && $this->info->dentrodejornada()){
                    echo "Consumir ".$this->id." Voy a esperar ". PHP_EOL;
                    //print_r($this->info);
                    $this->wait();
                }

                //if(!$this->info->dentrodejornada()){
                  //  echo "Se termino la Jornada - Consumidor: ".$this->id." Tengo ".$this->info->cantidad.PHP_EOL;
                    //$this->notify();
               // }
            }
          //  if(!$this->info->dentrodejornada()){
          //      echo "Afuera: Se termino la Jornada - Consumidor: ".$this->id." Tengo ".$this->info->cantidad.PHP_EOL;
           //     $this->notify();
           // }
           
           echo "Termine Consumir-".$this->id;
           },$this);
    }

    }

$miPool = new Pool(4);
$cant_hilos = 4;
$info = new Contenedor();
for ($i = 1; $i <=$cant_hilos; ++$i) {
   
    $my = new HiloProductorConsumidor($i,$info);
    
    $miPool->submit($my);
    $my->then(function () {
       echo "en el THEN";
     });
     
}

while ($miPool->collect()){}

$miPool->shutdown();
