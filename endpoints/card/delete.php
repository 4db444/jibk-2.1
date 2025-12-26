<?php
    session_start();
    include "../../conf.php";
    include  BASE_PATH . "/controllers/Card.php";
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $id = $_POST["id"];
        $result = Card::destroy($id);
        if (!$result["success"]){
            $_SESSION["errors"] = $result["errors"];
        }
        header("location: " . BASE_URL . "/views/card/");
    }else {
        header("location: " . $_SERVER["HTTP_REFERER"]);
    }