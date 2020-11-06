<?php
$array = [1,2,3];

$task = new class($array) extends Thread {
    private $data;

    public function __construct(array $array)
    {
        $this->data = $array;
    }

    public function run()
    {
        $this->data[3] = 4;
        $this->data[] = 5;

        print_r($this->data);
    }
};

$task->start() && $task->join();