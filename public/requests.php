<?php
//var_dump($_POST);die;
if(isset($_POST['test'])){
    header('Content-Type: application/json');
    $str = json_encode([1 => 'One', 2 => 'Two', 3 => 'Three']);
    $posts = json_encode([$_POST['test'], $_POST['to']]);
    echo $_POST['test'], ' ', $_POST['to'];
    //echo $posts;
    die;
}
if(isset($_POST['to'])){
    echo $_POST['to'];
    die;
}
if(isset($_GET['str'])){
    echo $_GET['str'];
    die;
}