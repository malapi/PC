<?php
/**
 * El Consumidor, estándo hambriento,
 *  consume todos los enteros de Dato Compartido (exactamenten el mismo objeto en que el productor puso los enteros 
 * en primer lugar) tan rápidamente como estén disponibles.
 */
class Consumidor  {
    private  $elRecurso;
    private  $cant_aConsumir;
    private  $id_Consumidor;

    public function __construct($buffer_c ,  $number,$elId) {
        $this->elRecurso = $buffer_c;
        $this->cant_aConsumir = $number;
        $this->id_Consumidor = $elId;
      
    }
    public function consumir(){
     
        $prod = $this->elRecurso->consumir( $this->id_Consumidor);
        return $prod;
    }

}

