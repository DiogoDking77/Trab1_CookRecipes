<?php

require_once __DIR__ . '/../DB/config.php';

class UserController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT User_ID, User_Name, User_Email FROM Users WHERE User_ID = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT User_ID, User_Name, User_Email FROM Users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getUsers($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT User_ID, User_Name, User_Email FROM Users WHERE User_ID != :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    
    

    public function addUser($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO Users (User_Name, User_Email, User_Password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getUserByEmailAndPassword($email,$password) {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE User_Email = :email AND User_Password = :password");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
              
        return $user;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE User_Email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
          
        return $user;
    }
    
}

?>
