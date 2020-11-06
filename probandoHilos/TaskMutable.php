<?php
class TaskMutable extends Volatile
{
    public function __construct()
    {
        $this->data = new Threaded();
        $this->data = new StdClass(); // valid, since we are in a volatile class
    }
}

$task = new class(new TaskMutable()) extends Thread {
    public function __construct($vm)
    {
        $this->volatileMember = $vm;

        var_dump($this->volatileMember->data); // object(stdClass)#4 (0) {}

        // still invalid, since Volatile extends Threaded, so the property is still a Threaded member of a Threaded class
        $this->volatileMember = new StdClass();
    }
};