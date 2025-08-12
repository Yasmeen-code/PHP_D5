<?php
class Db
{
    private $host = 'localhost';
    private $dbname = 'php_d3';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function get_all_users()
    {
        $stmt = $this->conn->prepare("SELECT id, first_name, last_name, address, photo FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_user_by_username($table, $username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function delete_by_id($table, $id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function get_data_by_id($table, $id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_user($id, $data)
    {
        $sql = "UPDATE users 
            SET first_name = :first_name, 
                last_name = :last_name, 
                address = :address, 
                country = :country, 
                gender = :gender, 
                skills = :skills, 
                username = :username, 
                password = :password, 
                department = :department, 
                photo = :photo,
                sh68sa = :sh68sa
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    public function insert_user($data)
    {
        $sql = "INSERT INTO users 
            (first_name, last_name, address, country, gender, skills, username, password, department, sh68sa, photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['address'],
            $data['country'],
            $data['gender'],
            $data['skills'],
            $data['username'],
            $data['password'],
            $data['department'],
            $data['sh68sa'],
            $data['photo']
        ]);
    }
}
