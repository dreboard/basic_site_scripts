<?php

class testClass{

    public function __construct()
    {
        $this->testInt();
    }
    public function testInt(){
        $this->testNum = 1;
        return $this;
    }
}
function showone(){
    return 1;
}

function showstring(){
    return 'string';
}

$a = new testClass();
var_dump($a);
