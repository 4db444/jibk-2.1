<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/TransactionController.php";

    if($_SERVER["REQUEST_METHOD"]){
        $type = $_POST["type"];
        $title = trim($_POST["title"]);
        $amount = $_POST["amount"];
        $description = trim($_POST["description"]);
        $card_id = $_POST["card_id"];
        $category_id = $_POST["category_id"] ?? null;
        $date = $_POST["date"] == "" ? null : $_POST["date"];
        $is_reccuring = ($_POST["is_reccuring"] ?? NULL) ? true : false;
    
        $result = TransactionController::CreateTransaction($type, $title, $amount, $description, $date, $card_id, $category_id, $_SESSION["user"]["id"], $is_reccuring);

        if(!$result["success"]){
            $_SESSION["error"] = $result["error"];
        }
    
        header("location: " . BASE_URL . "/views/transactions/transactions.php");
    }else {
        header("location: " . $_SERVER["HTTP_REFERER"]);
    }