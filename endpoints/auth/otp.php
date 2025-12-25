<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once "../../conf.php";
        require_once BASE_PATH . "/controllers/UserController.php";
        $otp = trim($_POST["otp"]);
        $user_id = $_POST["user_id"];

        if(UserController::Otp_check($user_id, $otp)){
            $_SESSION["user"] = UserController::Find($user_id);
            header("location: " . BASE_URL . "/views/transactions/transactions.php");
        }else{
            $_SESSION["error"] = "Invalide OTP";
            header("location: " . BASE_URL . "/views/auth/login.php");
        }
    }