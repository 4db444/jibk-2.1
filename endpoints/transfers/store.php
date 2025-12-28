<?php
    session_start();
    require_once __DIR__ . "/../../conf.php";
    require_once BASE_PATH . "/controllers/Transfer.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $receiver_email = trim($_POST["receiver_email"]);
        $title = trim($_POST["title"]);
        $amount = $_POST["amount"];
        $description = $_POST["description"];
        $date = $_POST["date"];

        $result = Transfer::Create($receiver_email, $title, $amount, $description, $date, $_SESSION["user_id"]);

        if(!$result["success"]){
            $_SESSION["errors"] = $result["errors"];
        }

        header("location: " . BASE_URL . "/views/transfers/");
    }else {
        header ("location: " . BASE_URL . $_SERVER["HTTP_REFERER"]);
    }