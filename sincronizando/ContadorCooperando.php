<?php
class ContadorCooperando extends Thread{

     public $cond  = 1;
     public function __construct()  {
      
    }
     public function run(){
      $this->synchronized(function () {
         for ($i = 1; $i < 10; ++$i) {
            echo $i." ";
             $this->notify();

             if ($this->cond  === 1) {
                 $this->cond  = 2;
                 $this->wait();
             }
         }
     });
     }
}
$obj = new ContadorCooperando();
$obj->start();

$obj->synchronized(function ($obj2) {
   if ($obj2->cond !== 2) {
      $obj2->wait(); // wait for the other to start first
  }

  for ($i = 10; $i < 20; ++$i) {
      echo $i." ";
      $obj2->notify();

      if ($obj2->cond === 2) {
          $obj2->cond = 1;
          $obj2->wait();
      }
  }
}, $obj);

$obj->join();



?>