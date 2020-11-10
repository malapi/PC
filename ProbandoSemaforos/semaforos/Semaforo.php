<?php
class Semaforo {

  public $key = null;
  public $resurce = null;
  public $desc;
  public $cant_accesos;

  public function __construct($dees , $key,$cant_accesos_perm,$sys) {
    $this->key = crc32($dees);
    $this->desc = $dees;
    $this->cant_accesos = $cant_accesos_perm;
   
    $this->sys = $sys;
    $this->obtener();
 }
  public function obtener() {
    $this->resurce = sem_get($this->key, $this->cant_accesos, 0666, true);
    echo "sem_get = ".$this->desc. "[".$this->sys."]\n" ; 
  }
  public function acquire() {
    $resp=false;  
   
    // echo "----------------------------------------------------------en acquire \n"; 
    
    // print_r(get_resources('sysvsem'));
    // print_r($this);
    // echo "----------------------------------------------------------en acquire \n"; 
    if(sem_acquire($this->resurce)){
        $resp=true; 
        echo "acquire = ".$this->desc. "[".$this->sys."]"."\n" ; 
        
    }
    return $resp;

  }
  public function release() {
    //$this->obtener(); 
    $resp=false;   
    // echo "----------------------------------------------------------en release \n"; 
    // print_r(get_resources('sysvsem'));
    // print_r($this);
    // echo "----------------------------------------------------------en release \n"; 
    if(sem_release($this->resurce)){
        echo "release = ".$this->desc. "[".$this->sys."]"."\n" ; 
        $resp=true; 
    }
    return $resp;
  }

  public function getKey(){
      return  $this->key ;


  }
}
?>
  