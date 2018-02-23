<?php
require_once __DIR__.'/db.php';
$register = new App\Main\Register($db);

try {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $register->registerUser($name, $password);
    }
} catch (Throwable $e) {
    $_SERVER['location: /index.php'];
}




