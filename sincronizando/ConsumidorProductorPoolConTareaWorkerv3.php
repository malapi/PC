<?php
class Accion extends Thread{
     private $id;
    public function __construct($c)  {
        $this->info = $c;
    }
    public function producir($id){
        $this->id = $id;
        $this->synchronized(function(){
            echo "Recien entro al Productor ".$this->id." Tengo ".$this->info->cantidad. PHP_EOL;
            for($i=0; $i < $this->info->proddiaria;$i++){
               // if ($hilo->info->hayespacio()){
                    $this->info->producir($this->id);
                    echo "Recien Produci ".$this->id." Tengo ".$this->info->cantidad. PHP_EOL;
                   
                   // sleep(1);
               // }
               $this->notify();
            }
            //$this->notify();
        }); echo "Termine me voy Productor ".$this->id. PHP_EOL;
        }
    public function consumir($id){
        $this->id = $id;
     $this->synchronized(function(){
        echo "Recien entro a Consumir ".$this->id."  Tengo ".$this->info->cantidad. PHP_EOL;
        for($i=0; $i < $this->info->consdiaria;$i++){
            if($this->info->hayparacomer()){
                $this->info->consumir($this->id);
                echo "Recien Consumi ".$this->id." Tengo ".$this->info->cantidad. PHP_EOL;
            } else {
                echo "Consumir ".$this->id."  me faltan ". ($this->info->consdiaria - $i)." Voy a esperar ". PHP_EOL;
                //print_r($this->info);
                $this->wait();
            }
        }
        echo "Termine me voy Consumir ". PHP_EOL;
});
}
}

class Productor extends Thread {
    private $accion;
    public function __construct($id,$accion){
        $this->accion = $accion;
        $this->id = $id;
    }
    public function run() {
        $this->accion->producir($this->id);
    }
}
class Consumidor extends Thread {
    private $accion;
    public function __construct($id,$accion){
        $this->accion = $accion;
        $this->id = $id;
    }
    public function run() {
        $this->accion->consumir($this->id);
    }
}
class Contenedor extends Volatile {
    public $cantidad = 0;
    public $maximo = 5;
    public $minimo = 0;
    public $acciones =[];
    public $consdiaria =5;
    public $proddiaria =5;
    public $parar = false;
   public function  producir($id){
        $this->cantidad++;
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
}
$miWorker = new Worker();
$miWorker->start();
$cant_hilos = 5;
$info = new Contenedor();
$acciones = new Accion($info);
for ($i = 0; $i <=$cant_hilos; $i++) {
    if(( $i  % 2) ==0){
        echo "Creamos un Productor ".$i.PHP_EOL;
        $ta = new Productor($i,$acciones);
        $miWorker->stack($ta);
    } else {
        echo "Creamos un Consumidor ".$i.PHP_EOL;
        $tb = new Consumidor($i,$acciones);
        $miWorker->stack($tb);
    }
}
while ($miWorker->collect()){}
$miWorker->shutdown();