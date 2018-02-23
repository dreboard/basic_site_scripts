<?php
/**
 * Basic functions
 */

function say(){
    echo __FILE__;
}
define('EMERGENCY','Emergency');
define('ALERT', 'Alert');
define('CRITICAL','Critical');
define('ERROR', 'Error');
define('WARNING', 'Warning');
define('NOTICE', 'Notice');
define('INFO', 'Info');
define('DEBUG', 'Debug');

//ini_set("error_log", '/php_errors.log');

if(!defined('DEVELOPMENT_SITE')){
    define('DEVELOPMENT_SITE', 'http://localhost:8088/');
}
if(!defined('SITE_ENV')){
    define('SITE_ENV', 'development');
}
if (!function_exists('exceptionHandler')) {
    /**
     * Default Inspi Exception handler
     * @param string $exception
     */
    function exceptionHandler($exception)
    {
        if ('development' === SITE_ENV) {

            echo '<div class="alert alert-danger">';
            echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
            echo $exception->getMessage() . '<br>';
            echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
            echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
            echo '</div>';die;
        } else {
            //echo displayUserMsg();
        }
        $log = 'Uncaught exception '.$exception->getMessage()."   |  In:  ".$exception->getFile()."  |  On:  ".$exception->getLine()." ".$exception->getTraceAsString()."\n";
        error_log($log);
    }
    //set_exception_handler('exceptionHandler');
}

function logSiteError(string $str)
{
    $lastError = error_get_last();
    if (!is_null($lastError) && $lastError['type'] === E_ERROR) {
        header('HTTP/1.1 302 Moved Temporarily');
        header('Status: 302 Moved Temporarily');
        header('Location: error.php');
        exit();
    }
    return true;
}


if (!function_exists('errorHandler')) {
    /**
     * Default error handler
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @return bool true Don't execute PHP internal error handler
     */
    function errorHandler($code, $message, $file, $line)
    {
        if (!(error_reporting() & $code)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
        $level = '';
        switch ($code) {
            case E_USER_ERROR:
                $level = 'E_USER_ERROR';
                break;
            case E_USER_WARNING:
                $level = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE:
                $level = 'E_USER_NOTICE';
                break;
            default:
                $level = "Unknown error type";
                break;
        }
        error_log($level.' ' . $code . ' ' . $message . "   |  In:  " . $file . "  |  On:  " . $line . "\n");
        return true;
    }

    //set_error_handler("errorHandler");
}

if (!function_exists('displayUserMsg')) {
    /**
     * Display the default user message
     * @return string
     */
    function displayUserMsg(){
        $error = '<div class="alert alert-danger">';
        $error .= '<h4>Inspi has encountered an unknown error</h4>';
        $error .= '<a href="page2.php">Try request again</a>';
        $error .= '</div>';
        return $error;
    }
}


register_shutdown_function(
    /**
     * Shut down function for other core
     * parse or compile errors
     */
    function(){
    ob_start();
    $lastError = error_get_last();
    switch($lastError['type']){
        case E_ERROR:
        case E_PARSE:
        case E_CORE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_ERROR:
        case E_COMPILE_WARNING:
        error_log($lastError['message']);
            break;
    }
        echo $lastError['message'];
});



