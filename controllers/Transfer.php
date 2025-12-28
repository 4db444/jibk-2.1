<?php 

    require_once __DIR__ . "/Database.php";
    require_once __DIR__ . "/User.php";

    use Database\Database;

    class Transfer {
        private static PDO $connection;

        public static function Connect (){
            self::$connection = Database::instance();
        }

        public static function Create($receiver_email, $title, $amount, $description, $date, $sender_id){

            $errors = [];

            if(!strlen($receiver_email)) $errors["receiver_email"] = "not a valide email"; 
            if(!strlen($title)) $errors["title"] = "not a valide title"; 
            if($amount <= 0) $errors["amount"] = "not a valide amount"; 

            if ($errors) return [
                "success" => false,
                "errors" => $errors
            ];

            $sender = User::FindById($sender_id);
            $receiver = User::FindByEmail($receiver_email);

            $sender_main_card_id = $sender->get_active_card_id();
            $receiver_main_card_id = $receiver->get_active_card_id();

            if(!empty($date)){
                $create_transfer_statment = self::$connection->prepare("
                    insert into transfers (card_sender_id, card_receiver_id, title, description, amount, date)
                    values(:card_sender_id, :card_receiver_id, :title, :description, :amount, :date)
                ");

                $create_transfer_statment->execute([
                    ":card_sender_id" => $sender_main_card_id,
                    ":card_receiver_id" => $receiver_main_card_id,
                    ":title" => $title,
                    ":description" => $description,
                    ":amount" => $amount,
                    ":date" => $date
                ]);
            }else{
                $create_transfer_statment = self::$connection->prepare("
                    insert into transfers (card_sender_id, card_receiver_id, title, description, amount)
                    values(:card_sender_id, :card_receiver_id, :title, :description, :amount)
                ");

                $create_transfer_statment->execute([
                    ":card_sender_id" => $sender_main_card_id,
                    ":card_receiver_id" => $receiver_main_card_id,
                    ":title" => $title,
                    ":description" => $description,
                    ":amount" => $amount
                ]);
            }
            
            return [
                "success" => true
            ];
        }

        public static function GetTransfers($user_id){
            return self::$connection->query("
                (
                    select * from transfers tr
                    join cards c on c.id = tr.card_sender_id
                    join users u on u.id = c.user_id
                    where c.user_id = $user_id
                )
                union all 
                (
                    select * from transfers tr
                    join cards c on c.id = tr.card_receiver_id
                    join users u on u.id = c.user_id
                    where c.user_id = $user_id
                )
            ")->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    Transfer::Connect();