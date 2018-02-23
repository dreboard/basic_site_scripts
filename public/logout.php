<?php
ob_start();
require_once __DIR__ . '/db.php';
    try {
        //session_start();
        //$handler->destroy($_SESSION['id']);
        //var_dump($_SESSION);die;
        $handler->logout(session_id());

        session_destroy();
        $_SESSION = [];

        if (headers_sent()) {
            die("Redirect failed. Please click on this link: <a href='logout.php''>Main</a>");
        }
        header("Location: index.php");
        exit();
    } catch (Throwable $e) {
        echo $e->getMessage();
    }


ob_end_flush();
