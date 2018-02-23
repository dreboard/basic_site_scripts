<?php
/**
 * Created by PhpStorm.
 * User: andreboard
 * Date: 11/27/17
 * Time: 3:49 PM
 */

namespace App\Main;


class Autoloader

{
    public static function load($class)

    {
        if (class_exists($class, FALSE)) {
            return;
        }

        $file = $class . '.php';

        if (!file_exists($file)) {
            //eval('class' . $class . '{}');
            throw new FileNotFoundException('File ' . $file . ' not found . ');
        }

        require_once($file);
        unset($file);

        if (!class_exists($class, FALSE)) {
            //eval('class' . $class . '{}');
            throw new ClassNotFoundException('Class ' . $class . ' not found . ');
        }
    }
}
