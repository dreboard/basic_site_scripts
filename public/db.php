<?php
/**
 * Basic ini setting an app init
 * {@internal change localhost to localhost:8088 for docker
 * @todo refactor to init.php
 */
if (!defined('ENVIRONMENT') && PHP_SAPI !== 'cli') {
    if ('localhost' === $_SERVER['HTTP_HOST']) {
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', 'production');
    }
}
if(!defined('EXCEPTION_LOG')){
	define('EXCEPTION_LOG', __DIR__ . '/../logs/exceptions.log');
}
ini_set('error_reporting', 1);
xdebug_break();
ini_set('log_errors', 1);
//ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/php_log.log');
ini_set('error_log', __DIR__ . '/../logs/php_log.log');
error_log(__FILE__ . time());
error_reporting(E_ALL);
if (ENVIRONMENT === 'development') {
    if (extension_loaded('xdebug')) {

    	if (!is_dir(__DIR__ .'/../logs/xdebug')){
		    if ( ! mkdir( __DIR__ . '/../logs/xdebug', 0777 ) && ! is_dir( __DIR__ . '/../logs/xdebug' ) ) {
			    throw new \RuntimeException( sprintf( 'Directory "%s" was not created', __DIR__ . '/../logs/xdebug' ) );
		    }
		    if ( ! mkdir( __DIR__ . '/../logs/xdebug/trace', 0777 ) && ! is_dir( __DIR__ . '/../logs/xdebug/trace' ) ) {
			    throw new \RuntimeException( sprintf( 'Directory "%s" was not created', __DIR__ . '/../logs/xdebug/trace' ) );
		    }
		    if ( ! mkdir( __DIR__ . '/../logs/xdebug/profile', 0777 ) && ! is_dir( __DIR__ . '/../logs/xdebug/profile' ) ) {
			    throw new \RuntimeException( sprintf( 'Directory "%s" was not created', __DIR__ . '/../logs/xdebug/profile' ) );
		    }
	    }

    	define('XDEBUG_TRACE_DIR', __DIR__ . '/../logs/xdebug/trace');
        ini_set('xdebug.remote_log', __DIR__ . '/../logs/xdegub/xdegub.log');
        //ini_set('xdebug.remote_host', '192.168.41.204');

	    ini_set('xdebug.collect_vars', 'on');
	    ini_set('xdebug.collect_params', '4');
	    ini_set('xdebug.dump_globals', 'on');
	    ini_set('xdebug.dump.SERVER', 'REQUEST_URI');
	    ini_set('xdebug.show_local_vars', 'on');
	    //ini_set('xdebug.trace_output_dir', __DIR__ . '/../logs/xdebug/trace');

	    ini_set('xdebug.collect_assignments', true);
        ini_set('xdebug.scream', 1);
        ini_set('xdebug.show_error_trace', 1);
        //ini_set('xdebug.show_exception_trace', 1); // force php to output exceptions even in a try catch block
        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.profiler_output_dir', __DIR__ . '/../logs/xdegub/profiler');
    }
    ini_set('display_errors', 1);
    ini_set('html_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    ini_set('display_errors', 0);
}
ini_set('date.timezone', 'America/Chicago');
ini_set('session.cookie_httponly', 1);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);
session_set_cookie_params(2440, '/', '', false, true);

//error_log('INIT message',0,$_SERVER['DOCUMENT_ROOT'].'/php_log.log');

require_once __DIR__ . '/../vendor/autoload.php';

use App\Main\{
    Debug, PDOLogger
};
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

try {
    $debugbar = new DebugBar\StandardDebugBar();
    $debugbarRenderer = $debugbar->getJavascriptRenderer();

//$log = new Logger('main');
//$log->pushHandler(new StreamHandler('../public/php_errors.log', Logger::WARNING));
//$log->pushHandler(new PDOLogger($db));
//;port=32768

    if($_SERVER['REMOTE_ADDR'] === '172.17.0.1'){
	    $dsn = 'mysql:host=172.17.0.1:32768;dbname=server1';
	    $user = 'serve';
	    $password = 'serve';
    } else {
	    $dsn = 'mysql:host=localhost;dbname=server1';
	    $user = 'root';
	    $password = '';
    }

    //$db = new PDO($dsn, $user, $password);
    $db = new App\Main\Database();
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sess_handle = new App\Main\SaveSession($db, $debugbar);
    session_set_save_handler($sess_handle, true);
    session_start();

    $errorLog = new \App\Main\ErrorHandler($db, $debugbar);
    set_error_handler([$errorLog, 'saveLog']);
    set_exception_handler([$errorLog, 'exceptionHandler']);

} catch (PDOException | Throwable $e) {
    if ($e instanceof PDOException && ENVIRONMENT === 'development') {
        echo 'Connection failed: ' . $e->getMessage(), '<br>';
    } else {
        error_log($e->getMessage());
        echo 'Connection failed: <br>';
    }
}
