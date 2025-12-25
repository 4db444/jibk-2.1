<?php

    include __DIR__ . "/CardController.php";
    include __DIR__ . "/TransactionController.php";
    include __DIR__ . "/MailController.php";

    class UserController {
        private static $connection;

        static function Connect (){
            try {
                self::$connection = new PDO("mysql:host=localhost;dbname=jibk2.0", "root", "Brahim@444");
            }catch (PDOException $e){
                echo "error: " . $e->getMessage();
            }
        }

        static function Find (string $id) {
            $user_statment = self::$connection->prepare("
                SELECT *
                FROM users
                WHERE id = :id
            ");

            $user_statment->execute([
                ":id" => $id
            ]);

            return $user_statment->fetch(PDO::FETCH_ASSOC);
        }

        static function Create (string $username, string $email, string $password) {
            $insert_user_statment = self::$connection->prepare("
                INSERT INTO users (username, email, password)
                VALUES (:username, :email, :password)
            ");

            $insert_user_statment->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }

        static function SignUp (string $username, string $email, string $password, string $password_confirmation, string $bank, int $initial_balance, string $type) {
            $errors = [];

            if (strlen($username) < 6) $errors["username"] = "User name must be at least 6 characters.";
            if (!preg_match('/^[a-z0-9]+@[a-z0-9]+\.[a-z]+$/i', $email)) $errors ["email"] = "Invalide email";
            if (strlen($password) < 8) $errors["password"] = "Password must be at least 8 characters.";
            if ($password !== $password_confirmation) $errors["password_confirmation"] = "Wrong password confirmation.";
            if (!$bank) $errors["bank"] = "Invalide bank name.";
            if ($initial_balance < 0) $errors["initial_balance"] = "Initial balance can't be negative.";

            if (!empty($errors)){
                return ["success" => false, "errors" => $errors];
            }

            $check_email_statment = self::$connection->prepare("SELECT 1 FROM users WHERE email = :email");

            $check_email_statment->execute([
                ":email" => $email
            ]);

            if ($check_email_statment->fetch()) return [
                "success" => false,
                "errors" => ["email" => "This email already exists"]
            ];

            // creating the user record
            self::Create($username, $email, $password);

            $user_statment = self::$connection->prepare("
                SELECT `id`
                FROM `users`
                WHERE `email` = :email
            ");
            
            $user_statment->execute([
                ":email" => $email
            ]);
            
            $user_id = $user_statment->fetch(PDO::FETCH_ASSOC)["id"];

            // creating the card record.
            CardController::create($bank, $type, 0, $user_id);

            $card_id_statment = self::$connection->prepare("
                SELECT cards.id
                FROM `cards`
                join users on cards.user_id = users.id
                where `email` = :email
            ");
            
            $card_id_statment->execute([
                ":email" => $email
            ]);

            $card_id = $card_id_statment->fetch(PDO::FETCH_ASSOC)["id"];

            // inserting the initial balance.
            TransactionController::CreateTransaction ("incomes", "Initial Balance", $initial_balance, "the initial balance when you created your acount", null, $card_id, null, false);
            
            return ["success" => true];
        }

        static function login (string $email, string $password){
            $errors = [];

            $user_statment = self::$connection->prepare("
                SELECT * 
                FROM users
                WHERE email = :email
            ");

            $user_statment->execute([
                ":email" => $email
            ]);

            $user = $user_statment->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])){
                $otp_int = random_int(100000, 999999);
                $otp_statment = self::$connection->prepare("insert into otp (otp, expire_at, user_id) values (:otp, :expire_at, :user_id)");

                $otp_statment->execute([
                    ":otp" => $otp_int,
                    ":expire_at" => (new DateTime())->modify("+10 minutes")->format("Y-m-d H:i:s"),
                    ":user_id" => $user["id"]
                ]);

                MailController::Send($email, $otp_int);

                return [
                    "success" => true,
                    "user_id" => $user["id"]
                ];
            }

            return [
                "success" => false,
                "error" => "Wrong credentials"
            ];
        }

        static function Otp_check (string $user_id, string $otp) {
            $otp_statment = self::$connection->prepare("
                SELECT *
                FROM otp
                WHERE user_id = :user_id AND otp = :otp AND expire_at >= :current_date
            ");

            $otp_statment->execute([
                ":user_id" => $user_id,
                ":otp" => $otp,
                ":current_date" => (new DateTime())->format("Y-m-d H:i:s")
            ]);

            if ($otp_statment->fetch()) return true;
            return false;
        }
    }

    UserController::connect();