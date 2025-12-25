<?php
    class TransactionController {

        private static $connection;

        static function Connect () {
            try {
                self::$connection = new PDO("mysql:host=localhost;dbname=jibk2.0", "root", "Brahim@444");
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }
        }

        static function CreateTransaction (string $type, string $title, float $amount, string $description, $date, int $card_id,$category_id, $user_id, bool $is_reccuring){

            if(!empty($category_id) && $type === "expenses"){
                $category_limit = self::$connection->query("
                    select `limit`
                    from expense_category_limit
                    where category_id = $category_id and user_id = $user_id
                ")->fetch()["limit"];

                if($category_limit){
                    $current_month_category_expenses = self::$connection->query("
                        select sum(amount) as total
                        from expenses ex
                        join cards c on c.id = ex.card_id
                        where user_id = $user_id
                    ")->fetch()["total"] ?? 0;

                    if ($current_month_category_expenses + $amount > $category_limit){
                        return [
                            "success" => false,
                            "error" => "can not add expense, category limit error !"
                        ];
                    }
                }
            }

            if (empty($date)){

                $create_transaction_statment = self::$connection->prepare("
                    insert into $type (title, amount, description, card_id, category_id)
                    values (:title, :amount, :description, :card_id, :category_id)
                ");
    
                $create_transaction_statment->execute([
                    ":title" => $title,
                    ":amount" => $amount,
                    ":description" => $description,
                    ":card_id" => $card_id,
                    ":category_id" => $category_id
                ]);
            }else{
                $create_transaction_statment = self::$connection->prepare("
                    insert into $type (title, amount, description, date, card_id, category_id)
                    values (:title, :amount, :description, :date, :card_id, :category_id)
                ");
    
                $create_transaction_statment->execute([
                    ":title" => $title,
                    ":amount" => $amount,
                    ":description" => $description,
                    ":date" => $date,
                    ":card_id" => $card_id,
                    ":category_id" => $category_id
                ]);
            }

            if($is_reccuring){
                self::$connection->query("
                    insert into `{$type}_events`
                    values (null, " . self::$connection->lastInsertId() . ")
                ");
            }

            return [
                "success" => true
            ];
        }

        static function ShowAllTransactions (){
            return self::$connection->query("
                (
                    select incomes.id, title, amount, description, date, bank, type, 'incomes' as 'table' from incomes 
                    join cards on cards.id = incomes.card_id
                    where cards.user_id = {$_SESSION["user"]["id"]}
                )
                union all
                (
                    select expenses.id, title, amount, description, date, bank, type, 'expenses' as 'table' from expenses
                    join cards on expenses.card_id = cards.id
                    where cards.user_id = {$_SESSION["user"]["id"]}
                ) 
                ORDER BY date desc, id desc
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        static function DeleteTransaction(string $table, int $id){
            self::$connection->query("delete from $table where id = $id");
        }

        static function ShowTransaction(string $table, int $id){
            return self::$connection->query("select * from $table where id = $id");
        }

        static function UpdateTransaction (int $id, string $type, string $title, float $amount, string $description, $date, int $card_id,$category_id){
            if (empty($date)){
                $create_transaction_statment = self::$connection->prepare("
                    update $type
                    set title = :title, amount = :amount, description = :description, card_id = :card_id, category_id = :category_id
                    where id = :id
                ");
    
                $create_transaction_statment->execute([
                    ":id" => $id,
                    ":title" => $title,
                    ":amount" => $amount,
                    ":description" => $description,
                    ":card_id" => $card_id,
                    ":category_id" => $category_id
                ]);
            }else{
                $create_transaction_statment = self::$connection->prepare("
                    update $type
                    set title = :title, amount = :amount, description = :description, date = :date, card_id = :card_id, category_id = :category_id
                    where id = :id
                ");
    
                $create_transaction_statment->execute([
                    ":id" => $id,
                    ":title" => $title,
                    ":amount" => $amount,
                    ":description" => $description,
                    ":date" => $date,
                    ":card_id" => $card_id,
                    ":category_id" => $category_id
                ]);
            }
        }

        static function GetTotoalTransactions (string $table, int $user_id){
            $sql = "
                select sum(amount) as sum 
                from $table 
                join cards on cards.id = $table.card_id 
                where user_id = $user_id";

            return self::$connection->query($sql)->fetch(PDO::FETCH_ASSOC)["sum"]; 
        }

        static function GetCurrentMonthTransactions (string $table, int $user_id){
            $sql = "
                select sum(amount) as total
                from $table
                join cards on cards.id = $table.card_id
                where month(date) = month(CURRENT_TIME) and user_id = $user_id;
            ";

            return self::$connection->query($sql)->fetch(PDO::FETCH_ASSOC)["total"];
        }

        static function GetTotoalTransactionsPerMonth (string $table, int $user_id){
            $sql = "
                select MONTHNAME(date) as month, sum(amount) as total
                from $table
                join cards on cards.id = $table.card_id
                where user_id = $user_id
                GROUP BY month
                limit 12;
            ";

            return self::$connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        static function GetCategegories (string $table) {
            return self::$connection->query("
                SELECT *
                FROM {$table}_categories
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        static function GetExpensesCategories(){
            return self::$connection->query("
                SELECT * from expenses_categories
            ")->fetchAll(PDO::FETCH_ASSOC);
        }

        static function GetExpenseCategorieLimit(int $category_id, int $user_id){
            return self::$connection->query("
                SELECT * from expense_category_limit 
                where user_id = $user_id and category_id = $category_id
            ")->fetch(PDO::FETCH_ASSOC)["limit"] ?? NULL;
        }

        static function SetLimit ($category_id, $user_id, $limit){
            self::$connection->query("delete from expense_category_limit where category_id = $category_id and user_id = $user_id");
            if (!empty($limit)){
                self::$connection->query("
                    insert into expense_category_limit (category_id, user_id, `limit`)
                    values('$category_id', '$user_id', '$limit')
                ");
            }
        }

        static function GetEventTransactions(){
            $sql = "
                (
                    select 'expenses' as type, title, amount, description, date, card_id, user_id, category_id
                    from expenses_events
                    left join expenses on expenses.id = expenses_events.expense_id
                    left join cards on expenses.card_id = cards.id
                )

                union ALL

                (
                    select 'incomes' as type, title, amount, description, date, card_id, user_id, category_id
                    from incomes_events
                    left join incomes on incomes.id = incomes_events.income_id
                    left join cards on incomes.card_id = cards.id
                );
            ";

            return self::$connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    TransactionController::Connect();