<?php
    session_start();
    include "../../conf.php";
    include BASE_PATH . "/controllers/CardController.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        CardController::SetDefault($_POST["id"], $_SESSION["user"]["id"]);
        header("location: " . BASE_URL . "/views/card");
    }
