<?php
class My extends Thread
{
    public
        $array = ['default val 1', 'default val 2'],
        $msg = 'default',
        $stop = false;

    public function run()
    {
        while(true)
        {
            echo $this->msg . PHP_EOL;
            if(count($this->array) > 0){
               // echo " En el hilo voy a ir al for ".count($this->array);
                foreach($this->array as $val){
                   // echo " En el hilo \n"; var_dump($val);
                }
                $this->array = [2];
            }
            /** cause this thread to wait **/
            $this->synchronized(
                function($thread){
                    if(count($this->array) < 1){
                        echo "En el hilo se quedo vacio... espero";
                        $thread->wait();
                    }
                },
                $this
            );
            echo PHP_EOL;
            if($this->stop){
                break;
            }
        } // while
    }
}
$my = new My();
$my->start();

sleep(1); // wait a bit

// test 1 - $thread->array[] = 1;
$my->synchronized(
    function($thread){
        $thread->msg = 'test 1';
        $thread->array[] = 1;
        $thread->notify();
    },
    $my
);

sleep(1); // wait a bit

// test 2 - array_push($thread->array, 2);
$my->synchronized(
    function($thread){
        $thread->msg = 'test 2';
        $thread->array[] = 2;
        $thread->notify();
    },
    $my
);

sleep(1); // wait a bit

// test 3 - array_push($thread->array, 2);
$my->synchronized(
    function($thread){
        $thread->msg = 'test 3';
        $new = [3];
        $thread->array[] = $new;
        $thread->notify();
    },
    $my
);

sleep(1); // wait a bit

$my->stop = true;
?>