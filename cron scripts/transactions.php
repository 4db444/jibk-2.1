<?php
    include __DIR__ . "/../controllers/Transaction.php";

    $transactoin_events = Transaction::GetEventTransactions();

    foreach($transactoin_events as $event){
        Transaction::CreateTransaction(
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