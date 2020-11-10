<?php
/**
 * El Consumidor, estándo hambriento,
 *  consume todos los enteros de Dato Compartido (exactamenten el mismo objeto en que el productor puso los enteros 
 * en primer lugar) tan rápidamente como estén disponibles.
 */
class Productor  {
    private  $elRecurso;
    private  $cant_aProducir;
    private  $id_Prod;

    public function __construct($buffer_c ,  $number,$elId) {
        $this->elRecurso = $buffer_c;
        $this->cant_aProducir = $number;
        $this->id_Prod = $elId;
      
    }
    public function producir(){
    
        echo "el productor va a producir ";
        print_r($this->cant_aProducir);
        for ($i = 1; $i <= $this->cant_aProducir; $i++) {
            echo "el productor va a producir ";
            $this->elRecurso->producir( $this->id_Prod , $i*100);
        }
    }

}

