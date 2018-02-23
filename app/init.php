<?php
/**
 * Created by PhpStorm.
 * User: andreboard
 * Date: 2/8/18
 * Time: 3:11 PM
 */

if(!defined('ENVIRONMENT')){
    if('localhost:8088' === $_SERVER['HTTP_HOST']){
        define('ENVIRONMENT', 'development');
    } else {
        define('ENVIRONMENT', 'production');
    }
}
ini_set('error_reporting', 1);

ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/php_log.log');
error_reporting(E_ALL);
if(ENVIRONMENT === 'development'){
    if(extension_loaded('xdebug')){
        ini_set('xdebug.remote_log', __DIR__.'/../logs/xdegub.log');
    }
    ini_set('display_errors' ,1);
    ini_set('html_errors', 1);
    ini_set('display_startup_errors',1);
} else {
    ini_set('display_errors' ,0);
}
ini_set('date.timezone', 'America/Chicago');
ini_set('session.cookie_httponly', 1);
