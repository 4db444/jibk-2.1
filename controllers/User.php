<?php

    require_once __DIR__ . "/Card.php";
    require_once __DIR__ . "/Transaction.php";
    require_once __DIR__ . "/Database.php";
    require_once __DIR__ . "/OTP.php";

    use Database\Database;

    class User {
        private ?string $username = null;
        private ?string $email = null;
        private ?string $password = null;

        private static PDO $connection;

        public function __construct (string $email, string $password, string $username = null){
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
        }

        public static function Store () {
            $insert_user_statment = self::$connection->prepare("
                INSERT INTO users (username, email, password)
                VALUES (:username, :email, :password)
            ");

            $insert_user_statment->execute([
                ":username" => $this->username,
                ":email" => $this->email,
                ":password" => password_hash($this->password, PASSWORD_DEFAULT)
            ]);
        }

        public static function Connect (){
            self::$connection = Database::instance();
        }

        public static function Find (string $id) {
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

        public static function SignUp (string $username, string $email, string $password, string $password_confirmation, string $bank, int $initial_balance, string $type) {
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
            self::Store($username, $email, $password);
            
            $user_id = self::$connection->lastInsertId();

            // creating the card record.
            Card::create($bank, $type, 0, $user_id);

            $card_id = self::$connection->lastInsertId();

            // inserting the initial balance.
            Transaction::CreateTransaction ("incomes", "Initial Balance", $initial_balance, "the initial balance when you created your acount", null, $card_id, null, false);
            
            return ["success" => true];
        }

        public static function Login ($email, $password){
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

                OTP::Send($email, $user["id"]);

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

        public function logout (){
            session_destroy();

            return [
                "success" => false,
            ];
        }

        public static function Otp_check (string $user_id, string $otp) {
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

    User::Connect();