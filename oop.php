<?php
require_once 'db.php';
class users{
    private $email;
    private $password;

    public function __set($name, $value)
    {
        if($name == 'email') {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->email = $value;

                global $conn;
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $value);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    throw new Exception("Email already exists");
                }
            } else {
                throw new Exception("Invalid email format");
            }

        } elseif ($name == 'password') {
            if (strlen($value) >= 8) {
                $this->password = $value;
                $hashedPassword = password_hash($value, PASSWORD_DEFAULT);
            } else {
                throw new Exception("Password must be at least 8 characters long");
            }
        } else {
            throw new Exception("Invalid property: " . $name);
        } 
    }

    public function __get($name)
    {
        if ($name == 'email') {
            return $this->email;
        } elseif ($name == 'password') {
            return $this->password;
        } else {
            throw new Exception("Invalid property: " . $name);
        }
    }

    


}


?>