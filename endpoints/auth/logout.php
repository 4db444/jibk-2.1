<?php 
    include __DIR__ . "/../../conf.php";
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION["user"])){
        session_destroy();
        header("location: " . BASE_URL . "/views/auth/login.php");
    }else{
        header("location: " . $_SERVER["HTTP_REFERER"]);
    }