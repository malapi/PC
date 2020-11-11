<?php
class Accion extends Thread{
    public function __construct($c)  {
        $this->info = $c;
    }
    public function producir($id){
        $this->synchronized(function($id){
            echo "Recien entro al Productor ".$id." Tengo ".$this->info->cantidad. PHP_EOL;
            for($i=0; $i < $this->info->proddiaria;$i++){
                    $this->info->producir($id);
                    echo "Recien Produci ".$id." Tengo ".$this->info->cantidad. PHP_EOL;
               $this->notify();
            }
        },$id); echo "Termine me voy Productor ".$id. PHP_EOL;
        }
    public function consumir($id){
     $this->synchronized(function($id){
        echo "Recien entro a Consumir ".$id."  Tengo ".$this->info->cantidad. PHP_EOL;
        for($i=0; $i < $this->info->consdiaria;$i++){
            if($this->info->hayparacomer()){
                $this->info->consumir($id);
                echo "Recien Consumi ".$id." Tengo ".$this->info->cantidad. PHP_EOL;
            } else {
                echo "Consumir ".$id."  me faltan ". ($this->info->consdiaria - $i)." Voy a esperar ". PHP_EOL;
                $this->wait();
            }
        }
        echo "Termine me voy Consumir ".$id."  ". PHP_EOL;
},$id);}}
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
    public $cantidad = 0;    public $maximo = 5;     public $minimo = 0;
    public $acciones =[];    public $consdiaria =5;     public $proddiaria =5; 
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
$miPool = new Pool(5);
$cant_hilos = 5;
$info = new Contenedor();
$acciones = new Accion($info);
for ($i = 0; $i <=$cant_hilos; $i++) {
    if(( $i > 2)){
        echo "Creamos un Productor ".$i.PHP_EOL;
        $ta = new Productor($i,$acciones);
        $miPool->submit($ta);
    } else {
        echo "Creamos un Consumidor ".$i.PHP_EOL;
        $tb = new Consumidor($i,$acciones);
        $miPool->submit($tb);
    }
}
while ($miPool->collect()){}
$miPool->shutdown();