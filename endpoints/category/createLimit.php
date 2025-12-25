<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/TransactionController.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $expense_category_id = $_POST["expense_category_id"];
        $limit = $_POST["limit"];
        $user_id = $_SESSION["user"]["id"];

        TransactionController::SetLimit($expense_category_id, $user_id, $limit);
    }
    header("location: " . BASE_URL . "/views/category/");