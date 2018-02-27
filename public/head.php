<?php
require_once __DIR__.'/db.php';
use App\Main\Debug;

try{
    //error_log(__FILE__.time());
    //trigger_error(date('Y-m-d').' 13 error', E_USER_ERROR);
    //throw new RangeException('Thrown RangeException');
} catch (\Throwable $e){
    $errorLog->exceptionInsert($e);
    error_log($e->getMessage());
}

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
	    <meta http-equiv="cache-control" content="max-age=0"/>
	    <meta http-equiv="cache-control" content="no-cache"/>
	    <meta http-equiv="expires" content="0"/>
	    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>
	    <meta http-equiv="pragma" content="no-cache"/>
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