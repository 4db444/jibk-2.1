<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/Card.php";
    include BASE_PATH . "/controllers/Transaction.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $bank = trim($_POST["bank"]);
        $type = $_POST["type"];
        $initial_balance = $_POST["initial_balance"];

        $result = Card::Create($bank, $type, 0, $_SESSION["user_id"]);
        if($initial_balance) Transaction::CreateTransaction ("incomes", "initial balance", $initial_balance, "the initial balance when you created your acount", null, Card::$connection->lastInsertId(), null, $_SESSION["user_id"], 0);
        if(!$result["success"]){
            $_SESSION["errors"] = $result["errors"];
        }
        header("location: " . BASE_URL . "/views/card/");
    }else{
        header("location: " . $_SERVER["HTTP_REFERER"]);
    }