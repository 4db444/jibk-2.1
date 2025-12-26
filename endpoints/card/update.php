<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/Card.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        Card::SetDefault($_POST["id"], $_SESSION["user_id"]);
        header("location: " . BASE_URL . "/views/card");
    }
