<?php

class MidtermDao {

    private $conn;

     public function __construct(){
        try {
            $servername = "containers-us-west-124.railway.app";
            $username = "root";
            $password = "0Um2qsa4IhDti9jMPoIX";
            $dbname = "prep2";
            $port = 8021;

            //midterm database thing
            $options = array(
                PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1g3sZDXiWK8HcPuRhS0nNeoUlOVSWdMAg/view?usp=share_link',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            );

            $dsn = "mysql:host=$servername;port=$port;dbname=$dbname";

            $this->conn = new PDO($dsn, $username, $password, $options);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }



 
    public function cap_table() {
    $query = "SELECT 
    cap.share_class_id, 
    sc.description as class_description, 
    cap.share_class_category_id, 
    scc.description as category_description,
    cap.investor_id,
    CONCAT(i.first_name, ' ', i.last_name) as investor_name,
    cap.diluted_shares
    FROM cap_table cap
    JOIN share_classes sc ON cap.share_class_id = sc.id
    JOIN share_class_categories scc ON cap.share_class_category_id = scc.id
    JOIN investors i ON cap.investor_id = i.id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($data as $row) {
      $share_class_id = $row['share_class_id'];
      $category_id = $row['share_class_category_id'];
      $investor_id = $row['investor_id'];

      $result[$share_class_id]['class'] = $row['class_description'];
      $result[$share_class_id]['categories'][$category_id]['category'] = $row['category_description'];
      $result[$share_class_id]['categories'][$category_id]['investors'][] = [
        'investor' => $row['investor_name'],
        'diluted_shares' => $row['diluted_shares']
      ];
    }

    $result = array_values($result);

    return $result;
    }



    public function summary(){
        $sql = "SELECT COUNT(DISTINCT investor_id) as total_investors, SUM(diluted_shares) as total_shares FROM cap_table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function investors(){
        $sql = "SELECT i.first_name, i.last_name, i.company, SUM(c.diluted_shares) as total_shares 
                FROM investors i JOIN cap_table c ON i.id = c.investor_id 
                GROUP BY c.investor_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
