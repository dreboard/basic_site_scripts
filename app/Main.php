<?php
declare(strict_types=1);
namespace App\Main;


class Main
{
    protected $var = 'Some String';
    public function __construct()
    {
        echo $this->var;
    }

    /**
     * Dummy function
     */
    public static function runCode(int $int = null)
    {
        echo $int ?? '';
        //return (1/0);
        //fakeFunc();
        //trigger_error('User error', E_USER_ERROR);
        throw new \Exception('User Exception');
    }

}