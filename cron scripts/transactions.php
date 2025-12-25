<?php
    include __DIR__ . "/../controllers/TransactionController.php";

    $transactoin_events = TransactionController::GetEventTransactions();

    foreach($transactoin_events as $event){
        TransactionController::CreateTransaction(
            $event["type"],
            $event["title"],
            $event["amount"],
            $event["description"],
            $event["date"],
            $event["card_id"],
            $event["category_id"],
            $event["user_id"],
            0
        );
    }