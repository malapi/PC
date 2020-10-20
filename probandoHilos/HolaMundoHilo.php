<?php
class MiPrimerHilo extends Thread{
     public function __construct()  {
      
    }
     public function run(){
        $content = file_get_contents("http://google.com");
        preg_match("~<title>(.+)</title>~", $content, $matches);
        $this->response = $matches[1];
     }
}
$obj = new MiPrimerHilo();
$obj->start() && $obj->join();
var_dump($obj->response); 
?>