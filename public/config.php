<?php

//echo $_SERVER['REMOTE_ADDR'];
//xdebug_break();
error_reporting(E_ALL);
ini_set('display_errors' ,'1');
ini_set('html_errors', '1');
ini_set('date.timezone', 'America/Chicago');


spl_autoload_register(NULL, FALSE);
spl_autoload_extensions(' . php');
spl_autoload_register(array('Autoloader', 'load'));

