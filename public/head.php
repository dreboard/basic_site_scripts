<?php
//phpinfo();die;

//header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);die;

require_once __DIR__.'/db.php';
use App\Main\Debug;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

try{
    error_log(__FILE__.time());
    trigger_error(date('Y-m-d').' 13 error', E_USER_ERROR);
    throw new RangeException('Thrown RangeException');
    //throw new \RuntimeException('Uncaught RuntimeException');
    //throw new \DomainException('Uncaught DomainException');
    //func();
} catch (\Throwable $e){
    $errorLog->exceptionInsert($e);
    error_log($e->getMessage());
}


//header('HTTP/1.0 404 Not Found');

//$status = session_status();
//session_start();

if(!isset($_SESSION['user']['id'])) {
    header('Location: /logout.php');
}

$data = array('foo' => ['bar', 'baz']);
Debug::debugArray($debugbar, error_get_last());
Debug::debugMsg($debugbar, error_get_last()['message'] ?? 'No Errors');
//$debugbar["messages"]->addMessage('<pre>' . $data . '</pre>');
error_log('message');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Test App</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <link rel="stylesheet" type="text/css" href="css/vendor/font-awesome/css/font-awesome.min.css">

    <?php if (ENVIRONMENT === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="css/vendor/highlightjs/styles/github.css">
    <link rel="stylesheet" type="text/css" href="css/debugbar.css">
    <link rel="stylesheet" type="text/css" href="css/widgets.css">
    <link rel="stylesheet" type="text/css" href="css/openhandler.css">
    <?php endif; ?>

    <style>
        body {
            padding-top: 5rem;
        }
        .starter-template {
            padding: 3rem 1.5rem;
            text-align: center;
        }
    </style>

</head>