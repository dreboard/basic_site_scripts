<?php
// php phpDocumentor.phar -d ./app -t ./docs
// php phpDocumentor.phar -d ./www/inspi2 --ignore "./www/inspi2/include/classes/PHPExcel,./www/inspi2/include/classes/PHPExcel.php,tests/*" -t ./docs
namespace App\Main;

use DebugBar\StandardDebugBar;
use DebugBar\DataCollector\ConfigCollector;
use Exception;

abstract class Debug
{
    public function __construct()
    {
    }

    /**
     * Global Debug bar diplay function.
     *
     * Exceptions caught by this function will be displayed
     * in the Exceptions tab of debugbar.
     * @param StandardDebugBar $bar StandardDebugBar instance
     * @param mixed $msg Array or variable
     */
    public static function debugMsg(StandardDebugBar $bar, $msg)
    {
        try {
            switch (gettype($msg)) {
                case 'string' :
                case 'integer' :
                    $val = $msg;
                    break;
                case 'array' :
                case 'object' :
                default :
                    $val = 'Item must be a string not ' . gettype($msg) . '';
                    break;
            }
            $bar["messages"]->addMessage($val);
        } catch (Exception $e) {
            $bar['exceptions']->addException($e);
        }
    }

    /**
     * Global Debug bar config function.
     *
     * Exceptions caught by this function will be displayed
     * in the Exceptions tab of debugbar.
     * @param StandardDebugBar $bar StandardDebugBar instance
     * @param mixed $msg Array or variable
     * @return array|null
     */
    public static function debugArray(StandardDebugBar $bar, $array): ?array
    {
        try{
            if(is_array($array)){
                $bar->addCollector(new ConfigCollector($array));
                return $array;
            }
            return (array)['Error' => 'Config item must be an array'];

        } catch (Exception $e) {
            $bar['exceptions']->addException($e);
        }
    }

}