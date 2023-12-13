<?php

// database.php

class MysqlDatabase {
    private $cursor = null;
    private static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new MysqlDatabase();
        }
        return self::$instance;
    }

    private function __construct(){
        $host = '127.0.0.1';
        $db = 'realdb';
        $user = 'wt1_prakt';
        $pw = 'abcd';

        $dsn = "mysql:host=$host;port=3306;dbname=$db";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ];

        try {
            $this->cursor = new PDO($dsn, $user, $pw, $options);
        } catch(PDOException $e) {
            echo "Verbindungsaufbau gescheitert: " . $e->getMessage();
        }
    }

    public function __destruct(){
        $this->cursor = NULL;    
    }

    public function read_products() {
        $query = "SELECT product_id, product_name, unit_price FROM Product";
        $statement = $this->cursor->prepare($query);
        $statement->execute();

        $products = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($row['product_id'], $row['product_name'], $row['unit_price']);
        }

        return $products;
    }

    public function update_product($product_id, $new_price) {
        $query = "UPDATE Product SET unit_price = :new_price WHERE product_id = :product_id";
        $statement = $this->cursor->prepare($query);
        $statement->bindParam(':new_price', $new_price, PDO::PARAM_STR);
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
    }
}
?>
