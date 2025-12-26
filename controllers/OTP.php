<?php
    require_once __DIR__ . "/Database.php";
    require_once __DIR__ . "/Mail.php";

    use Database\Database;

    class OTP {
        private static ?PDO $connection;

        public static function Connect (){
            self::$connection = Database::instance();
        }

        static function Send ($email, $user_id){
            $otp = random_int(100000, 999999);

            $otp_statment = self::$connection->prepare("insert into otp (otp, expire_at, user_id) values (:otp, :expire_at, :user_id)");

            $otp_statment->execute([
                ":otp" => $otp,
                ":expire_at" => (new DateTime())->modify("+10 minutes")->format("Y-m-d H:i:s"),
                ":user_id" => $user_id
            ]);

            Mail::send($email, $otp);
        }

        static function Verify ($user_id, $otp) : bool{
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

            return (bool) $otp_statment->fetch() ;
        }
    }

    OTP::Connect();