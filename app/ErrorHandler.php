<?php
/**
 * die('Got here '.__METHOD__);
 */

namespace App\Main;


use PDO;
use Exception;
use PDOException;
use Throwable;
use DebugBar\StandardDebugBar;

class ErrorHandler
{

    protected $pdo;
    protected $debugBar;

    public function __construct(PDO $db, StandardDebugBar $debugBar)
    {
        $this->pdo = $db;
        $this->debugBar = $debugBar;
        //error_log(__METHOD__);
    }

    /**
     * Default error handler
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool true Don't execute PHP internal error handler
     *              false the standard PHP error handler
     */
    public function saveLog($errno, $errstr, $errfile, $errline): bool
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        $errtype = $this->getErrorConst($errno);
        $this->errorInsert($errno, $errtype, $errstr, $errfile, $errline);
        error_log($errtype.' ' . $errno . ' ' . $errstr . "   |  In:  " . $errfile . "  |  On:  " . $errline . "\n");
        Debug::debugArray($this->debugBar, error_get_last());
        return true;
    }

    /**
     * Insert Error into Database
     * @param $errno
     * @param $errtype
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return bool|null
     */
    public function errorInsert($errno, $errtype, $errstr, $errfile, $errline)
    {

        try {
            $sth = $this->pdo->prepare(
                'INSERT INTO error_log 
                (errno, errtype, errstr, errfile, errline, user_agent, ip_address, time) 
                  VALUES 
                (
                  :errno, :errtype, :errstr, :errfile, :errline, :user_agent, :ip_address, :time)
                ');
            $sth->bindParam(':errno', $errno, PDO::PARAM_INT);
            $sth->bindParam(':errtype', $errtype);
            $sth->bindParam(':errstr', $errstr, PDO::PARAM_STR);
            $sth->bindParam(':errfile', $errfile, PDO::PARAM_STR);
            $sth->bindParam(':errline', $errline, PDO::PARAM_INT);
            $sth->bindValue(':user_agent', $_SERVER['HTTP_USER_AGENT']);
            $sth->bindValue(':ip_address', $_SERVER['REMOTE_ADDR']);
            $sth->bindValue(':time', date('Y-m-d h:i:s'));
            if(!$sth->execute()){
                //die('got here '.__METHOD__);
                throw new LoggerException();
            }
            return true;
        } catch (LoggerException | PDOException | Throwable $e) {
            //trigger_error($e->getMessage(), E_USER_ERROR);
            echo $e->getMessage();
        }
    }

    /**
     * Insert Exception into Database
     * @param Exception $e
     * @return bool|null
     */
    public function exceptionInsert(Throwable $e)
    {
        $ex = get_class($e);

        try {
            $sth = $this->pdo->prepare(
                'INSERT INTO error_log 
                (errno, errtype, errstr, errfile, errline, user_agent, ip_address, time) 
                  VALUES 
                (
                  :errno, :errtype, :errstr, :errfile, :errline, :user_agent, :ip_address, :time)
                ');
            $sth->bindValue(':errno', '0', PDO::PARAM_INT);
            $sth->bindParam(':errtype', $ex);
            $sth->bindValue(':errstr', $e->getMessage(), PDO::PARAM_STR);
            $sth->bindValue(':errfile', $e->getFile(), PDO::PARAM_STR);
            $sth->bindValue(':errline', $e->getLine(), PDO::PARAM_INT);
            $sth->bindValue(':user_agent', $_SERVER['HTTP_USER_AGENT']);
            $sth->bindValue(':ip_address', $_SERVER['REMOTE_ADDR']);
            $sth->bindValue(':time', date('Y-m-d h:i:s'));
            if(!$sth->execute()){
                die('got here '.__METHOD__);
                throw new LoggerException();
            }
            return true;
        } catch (LoggerException | PDOException | Throwable $e) {
            //trigger_error($e->getMessage(), E_USER_ERROR);
            echo $e->getMessage();
        }
    }

    /**
     * Default Exception handler.
     *
     * Logs unhandled exceptions and redirects user
     * to a default error handling page instead
     * of a blank white screen or an error message.
     * @param string $exception
     */
    public function exceptionHandler(Throwable $exception):void
    {
        //die('got here '.__METHOD__);
        $log = 'Exception: '.get_class($exception).'|'.$exception->getMessage()."   |  In:  ".$exception->getFile()."  |  On:  ".$exception->getLine();
        error_log($log);
        $this->exceptionInsert($exception);
        //echo 'Caught: '.$exception->getMessage();
        if (\defined('ENVIRONMENT') && ENVIRONMENT === 'development'){
            $this->debugBar['exceptions']->addException($exception);
            $this->debugBar["messages"]->addMessage($exception->getMessage());
        }

        if (php_sapi_name() !== 'cli' && ($_SERVER['SERVER_NAME'] === DEVELOPMENT_SITE) && (SITE_ENV === 'development')) {
            //error_log($log);
            //$this->exceptionInsert($exception);
            //require_once "../public/error.php";
            //exit();
        }
    }

    /**
     * PHP Shutdown function
     */
    public function fatalErrorShutdownHandler(): void
    {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR || ($last_error['type'] === E_USER_ERROR)) {
            // fatal error
            errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    /**
     * Get PHP Error Constant
     * @param int $errno
     * @return string $e_type
     */
    public function getErrorConst(int $errno): string
    {
        switch ($errno) {
            case 1:     $e_type = 'E_ERROR'; break;
            case 2:     $e_type = 'E_WARNING'; break;
            case 4:     $e_type = 'E_PARSE'; break;
            case 8:     $e_type = 'E_NOTICE'; break;
            case 16:    $e_type = 'E_CORE_ERROR'; break;
            case 32:    $e_type = 'E_CORE_WARNING'; break;
            case 64:    $e_type = 'E_COMPILE_ERROR'; break;
            case 128:   $e_type = 'E_COMPILE_WARNING'; break;
            case 256:   $e_type = 'E_USER_ERROR'; break;
            case 512:   $e_type = 'E_USER_WARNING'; break;
            case 1024:  $e_type = 'E_USER_NOTICE'; break;
            case 2048:  $e_type = 'E_STRICT'; break;
            case 4096:  $e_type = 'E_RECOVERABLE_ERROR'; break;
            case 8192:  $e_type = 'E_DEPRECATED'; break;
            case 16384: $e_type = 'E_USER_DEPRECATED'; break;
            case 30719: $e_type = 'E_ALL'; break;
            default:    $e_type = 'E_UNKNOWN'; break;
        }
        return $e_type;
    }

}