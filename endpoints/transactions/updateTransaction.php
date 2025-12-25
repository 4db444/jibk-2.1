<?php
    include "../../controllers/TransactionController.php";

    $id = $_POST["id"];
    $type = $_POST["type"];
    $title = $_POST["title"];
    $amount = $_POST["amount"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    $card_id = $_POST["card_id"];
    $category_id = $_POST["category_id"];

    TransactionController::updateTransaction($id, $type, $title, $amount, $description, $date, $card_id, $category_id);

    header("location: ../../views/transactions/transactions.php");

    exit;
