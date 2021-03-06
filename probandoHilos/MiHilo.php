<?php

class MiHilo extends Thread{
    private $id_hilo;
    private $inicia_run;
    private $crea;
    private $fin_run;
 
    public function __construct($id)  {
        $this->id_hilo = $id;
        $this->crea =  new DateTime();
        printf(" \n MiHilo.php>>>  Se creo el hilo ID:%s en %s \n", $this->id_hilo,$this->crea->format('H:i:s:v')); 
    }

    public function run(){
       
        //printf(" \n<MiHilo> Comienza su ejecución el hilo ID:%s en MiHilo \n", $this->id_hilo);
        $this->inicia_run =  new DateTime();
        printf(" \n        <MiHilo> INICIA el hilo ID:%s \n", $this->id_hilo);
        printf("MiHilo.php>> El hilo ID:%s INICIO EJECUCION: %s \n",$this->id_hilo,  $this->inicia_run->format('H:i:s:v'));
        

        for ($i = 1; $i <= 20; $i++) {
            //printf(" \n        <MiHilo> Se ejecuta el hilo ID:%s, iteración sub i= %s \n", $this->id_hilo,$i);
        }
        usleep(250000);
        $this->fin_run =  new DateTime();
        //printf(" \n        <MiHilo> Termino el hilo ID:%s \n", $this->id_hilo,$this->fin_run);
        printf("MiHilo.php>> El hilo ID:%s FINALIZO EJECUCION: %s \n", $this->id_hilo,$this->fin_run->format('H:i:s:v'));
       
    }

    public function getId_hilo(){
        return $this->id_hilo;
    }

    public function getInicia_run(){
        return $this->inicia_run;
    }
    public function getCrea(){
        return $this->crea;
    }
    public function getFin_run(){
        return $this->fin_run;
    }
}
?>