<?php
 class miHiloPorParametro  extends Thread {

    private $id_hilo;

    public function __construct(Threaded $unObj_tarea, $id) {
        $this->tarea = $unObj_tarea;
        $this->id_hilo = $id;
    }

    public function run()  {

        $this->tarea->escribeNumerosHasta(20,$this->getId_hilo());
    }

    public function getId_hilo(){
        return $this->id_hilo;
    }
};

?>