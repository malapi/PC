<?php
class Productor extends Threaded {
      
    public function accion($hilo) {
        echo "Recien entro al Productor ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
        for($i=0; $i <= $hilo->info->proddiaria;$i++){
                $hilo->info->producir($hilo->id);
                echo "Recien Produci ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
        }
        echo "Termine me voy Productor ".$hilo->id. PHP_EOL;
    }
}
class Consumidor extends Threaded {
    public function accion($hilo) {
        echo "Recien entro a Consumir ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
        for($i=0; $i <= $hilo->info->consdiaria;$i++){
                $hilo->info->consumir($hilo->id);
                echo "Recien Consumi ".$hilo->id." Tengo ".$hilo->info->cantidad. PHP_EOL;
        }
        echo "Termine me voy Consumir ".$hilo->id. PHP_EOL;
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
            $this->cantidad--;
            $this->acciones[] = $id." consumir";
      
        
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
class HiloFabrica extends Thread {
    public function __construct($index,$c)  {
        $this->id = $index;
        $this->info = $c;
        if(( $this->id % 2)==0){
            $this->tarea = new Productor(); $this->accion = "Es productor ".$index;
        } else {
            $this->tarea = new Consumidor(); $this->accion = "Es Consumidor ".$index;
        } 
        echo "Creamos el Hilo ".$index." es ".$this->accion.PHP_EOL;
   }
    public function run() {
                $this->tarea->accion($this);
    }
    }
$miPool = new Pool(4);
$cant_hilos = 4;
$info = new Contenedor();
for ($i = 1; $i <=$cant_hilos; $i++) {
    $my = new HiloFabrica($i,$info);
    $miPool->submit($my);
    }
while ($miPool->collect()){}
$miPool->shutdown();
