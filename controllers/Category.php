<?php

    require_once __DIR__ . "/Database.php";

    use Database\Database;

    class Category {
        private static PDO $connection;

        public static function Connect (){
            self::$connection = Database::instance();
        }

        static function GetCategories (string $table){
            return self::$connection->query("
                SELECT *
                FROM {$table}_categories
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
    }

    Category::Connect();