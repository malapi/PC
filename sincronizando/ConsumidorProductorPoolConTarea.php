<?php

class Prodcutor extends Threaded {
      
    public function accion($hilo) {
        //print_r($hilo->info);
        while($hilo->info->dentrodejornada()){
            if ($hilo->info->hayespacio() ){
                $hilo->info->producir($hilo->id);
                echo "Recien Produci ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
                $hilo->notify();
            }
            if ($hilo->info->estacompleto() && $hilo->info->dentrodejornada()){
                echo "Producir Voy a esperar ".$hilo->id." Jornada: ".$hilo->info->cantidadmaximadiaria.PHP_EOL;
                $hilo->wait();
            }
            if(!$hilo->info->dentrodejornada()){
                echo "Se termino la Jornada - Productor: ".$hilo->id." Tengo ".$hilo->info->cantidad.PHP_EOL;
                $hilo->notify();
            }
        }
        if(!$hilo->info->dentrodejornada()){
            echo "Afuera : Se termino la Jornada - Productor: ".$hilo->id." Tengo ".$hilo->info->cantidad.PHP_EOL;
            $hilo->notify();
        }
    }
}

class Consumidor extends Threaded {
      
    public function accion($hilo) {
        while($hilo->info->dentrodejornada()){
            if($hilo->info->hayparacomer()){
                $hilo->info->consumir($hilo->id);
                //print_r($this->info);
                echo "Consumir Recien Consumi ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
                //print_r($objProductor);
                $hilo->notify();
            }
            if ($hilo->info->estavacio() && $hilo->info->dentrodejornada()){
                echo "Consumir ".$hilo->id." Voy a esperar ". PHP_EOL;
                //print_r($this->info);
                $hilo->wait();
            }

            if(!$hilo->info->dentrodejornada()){
                echo "Se termino la Jornada - Consumidor: ".$hilo->id." Tengo ".$hilo->info->cantidad.PHP_EOL;
                $hilo->notify();
            }
        }
        if(!$hilo->info->dentrodejornada()){
            echo "Afuera: Se termino la Jornada - Consumidor: ".$hilo->id." Tengo ".$hilo->info->cantidad.PHP_EOL;
            $hilo->notify();
        }
    }
}


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


    public function __construct($index,$c,$tarea)  {
        $this->id = $index;
        $this->info = $c;
        $this->tarea = $tarea;
     
   }
    public function run() {
            $this->synchronized(function(){
                echo "Producir ".$this->id." Jornada: ".$this->info->cantidadmaximadiaria.PHP_EOL;
                $this->tarea->accion($this);
               
               
                
            }, $this);
       
    }
    public function then()
    {
        return $this->synchronized(function () {
            echo "Consumir ".$this->id. PHP_EOL;
            $this->tarea->accion($this);
           
            
           },$this);
    }

    }

$miPool = new Pool(4);
$cant_hilos = 10;
$info = new Contenedor();
for ($i = 1; $i <=$cant_hilos; $i++) {
   
    //if(($i % 2)==0){
      //  $my = new HiloProductorConsumidor($i,$info,new Prodcutor());
    //} else {
        //$my = new HiloProductorConsumidor($i,$info,new Consumidor());
    //}
    $my = new HiloProductorConsumidor($i,$info,new Prodcutor());
    $miPool->submit($my);
    $my->then(function () {
       echo "en el THEN";
     });
     
}

echo $miPool->collect();
while ($miPool->collect()){

}

$miPool->shutdown();
