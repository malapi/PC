<?php
include_once("Tarea.php");
class MiPrimerHilo extends Thread{

   
   public function __construct(Threaded $unObj_tarea) {
      $this->tarea = $unObj_tarea;
     
  }

  public function run()  {
      $this->tarea->escribeNumerosHasta(20,1);
  }

}

$obj_tarea = new Tarea();
$obj = new MiPrimerHilo($obj_tarea);
$obj->start() && $obj->join();
?>