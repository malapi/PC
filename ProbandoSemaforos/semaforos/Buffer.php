<?php
include_once "Semaforo.php";
/**
 * Buffer en el que se guarda los productos producidos y consumidos.
 */
class Buffer {
 
    // un array para guardar los elementos 
    private  $contenido;
    
    //semáforo binario, proporciona exclusión mutua para el acceso al buffer de productos. 
    //se inicializa a 1.
    public $sem_mutex; 

    //lleno: semáforo contador para controlar el número de huecos llenos del buffer.
    // - se inicializa a 0
    private $sem_hayDatos;

    // semáforo contador para controlar los huecos vacíos del   buffer. 
    //- se inicializa a n, tamaño del buffer
    public $sem_HayEspacio;

    public $vas;


    public function __construct() {
        $this->contenido = array();
        $this->sem_mutex = new Semaforo('mutex',1,1,8); 
        $this->sem_hayDatos = new Semaforo('hayDato',2,1,9);
        $this->sem_HayEspacio = new Semaforo('hayDespacio',3,1,10);
        
        
    }
    
    
    public function producir($elProd, $value) {
        // adquirimos  los semáforos
        echo "<En buffer producir>$elProd \n\n";    
       // print_r($this);
        echo "<PROD : ".$elProd."> Antes de Producir".$value." Cantidad buffer >>".count($this->contenido)."...\n";
        
     
        ($this->sem_HayEspacio)->acquire();
        ($this->sem_mutex)->acquire();
                
        
        // Inserta uno o más elementos al final de un array
         array_push($this->contenido,$value); 
        //echo " contenido actual luego push .........\n".$value;
        //print_r($this->contenido);
        
        echo "<PROD : ".$elProd."> en zona critica .... Cantidad buffer >> ".count($this->contenido)."...\n";
        // liberamos  los semáforos
        
        ($this->sem_mutex)->release();
        ($this->sem_hayDatos)->release();
        
        echo "<PROD : ".$elProd."> saliendo de producir Cantidad buffer >> ".count($this->contenido)."...\n\n";
       
    }
    public  function consumir($elConsumidor) {
        echo "<En buffer consumir>\n\n";    
       // print_r($this);
        echo "<<CONSUMIDOR : ".$elConsumidor.">> Antes de Consumir, cantidad buffer >>".count($this->contenido)."...\n";
       // adquirimos  los semáforos



        ($this->sem_hayDatos)->acquire();
        ($this->sem_mutex)->acquire();
         
        $prod = null;
      
        //Quita un elemento del principio del array
        $prod = array_shift($this->contenido); 
        echo "<<CONSUMIDOR : ".$elConsumidor.">> en zona critica luego de consumir,  cantidad buffer >>".count($this->contenido)."...\n";
 
        // liberamos  los semáforos
        ($this->sem_mutex)->release();
        ($this->sem_HayEspacio)->release();
            
        echo "<<CONSUMIDOR : ".$elConsumidor.">> saliendo de producir ,  cantidad buffer >> ".count($this->contenido)."...\n\n";
        return $prod;
    }
        
}
?>