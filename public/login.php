<?php
require_once __DIR__.'/db.php';
if(isset($_POST['name222'])) {
    $password = $_POST['password'];
    $sql2 = $db->prepare("INSERT INTO users 
    (name, password) 
    VALUES 
    (':name, :pass')"
    );
}

$login = new App\Main\Login($db);

if (isset($_POST['name'])) {
    try{
        $login->loginUser($_POST['name'], $_POST['password']);
    }catch (Throwable $e){
        echo $e->getMessage();
    }
} else {
    //var_dump(session_id());die;
	$sess_handle->logout(session_id());

    //session_destroy();
    $_SESSION = [];

    if (headers_sent()) {
        die("Redirect failed. Please click on this link: <a href='logout.php''>Main</a>");
    }
    header("Location: index.php");
    exit();


    $login->logout();
    header("Location: index.php");
    exit();
}