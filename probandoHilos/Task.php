<?php 
class Task extends Threaded // a Threaded class
{
    public function __construct()
    {
        $this->data = new Threaded();
        // $this->data is not overwritable, since it is a Threaded property of a Threaded class
    }
}

$task = new class(new Task()) extends Thread { // a Threaded class, since Thread extends Threaded
    public function __construct($tm)
    {
        $this->threadedMember = $tm;
        var_dump($this->threadedMember->data); // object(Threaded)#3 (0) {}
        $this->threadedMember = new StdClass(); // invalid, since the property is a Threaded member of a Threaded class
    }
};