<?php 

include("../../controllers/TransactionController.php");

if(!empty($_POST["id"])){

    TransactionController::DeleteTransaction($_POST['table'], $_POST["id"]);

    header("location: ../../views/transactions/transactions.php");
}