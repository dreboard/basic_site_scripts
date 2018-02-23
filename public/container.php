<?php


class ContainerException extends Exception {}

class Container {
    protected $services = [];
    public function __construct(){}

    public function listServices(){
        return array_keys($this->services);
    }

    public function register($service, callable $callable)
    {
        if(array_key_exists($service, $this->services)){
            throw new Exception('Service already registered');
        }
        $this->services[$service] = $callable;
    }

    public function getService($service)
    {
        if(!array_key_exists($service, $this->services)){
            throw new Exception('Service not registered');
        }
        return $this->services[$service]();
    }
}

class Database {
    public $name = 'database';
    public $user;
    public function __construct($config){
        $this->user = $config['db']['user'];
    }

}
class Class1 {
    public $name = 'class1';
    public function __construct(){}

}
try{
    $config = [
        'db' => [
            'user' => 'name',
            'password' => 'pass'
        ]
    ];
    $di = new Container();
    $di->register('class1', function(){
        return new Class1();
    });

    $di->register('database', function() use ($config){
        return new Database($config);
    });

    $db = $di->getService('database');
    var_dump($di->listServices());
    echo $db->user;
} catch (Throwable $e){
    echo $e->getMessage();
}
