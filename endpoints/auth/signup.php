<?php
    include "../../conf.php";
    if ($_SERVER["REQUEST_METHOD"] === "POST"){

        session_start();

        include BASE_PATH . "/controllers/UserController.php";
    
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $password_confirmation = $_POST["password_confirmation"];
        $bank = trim($_POST["bank"]);
        $initial_balance = (float) trim ($_POST["initial_balance"]);
        $type = $_POST["type"];
    
        $result = UserController::Signup($username, $email, $password, $password_confirmation, $bank, $initial_balance, $type);

        if($result["success"]){
            header("location: " . BASE_URL . "/views/auth/login.php");
        } else {

            $_SESSION["old_values"] = [
                "username" => $username,
                "email" => $email,
                "bank" => $bank,
                "initial_balance" => $initial_balance,
                "type" => $type
            ];

            $_SESSION["errors"] = $result["errors"];
            header("location: " . BASE_URL . "/views/auth/signup.php");
        };
    }else {
        header("location: " . BASE_URL . "/views/auth/signup.php");
    }