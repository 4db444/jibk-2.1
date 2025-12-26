<?php 

include("../../controllers/Transaction.php");

if(!empty($_POST["id"])){

    Transaction::DeleteTransaction($_POST['table'], $_POST["id"]);

    header("location: ../../views/transactions/transactions.php");
}