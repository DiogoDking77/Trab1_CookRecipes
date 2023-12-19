<?php

require_once __DIR__ . '/../PDO/UserPDO.php';


class UserController {
    private $userPDO;

    public function __construct() {
        $this->userPDO = new UserPDO();
    }

    public function getUserById($userId) {
        return $this->userPDO->getUserById($userId);
    }

    public function getAllUsers() {
        return $this->userPDO->getAllUsers();
    }

    public function addUser($username, $email, $password) {
        $this->userPDO->addUser($username, $email, $password);
    }

    // UserRepository.php
    public function getUserByEmailAndPassword($email, $password) {
        
        $user = $this->userPDO->getUserByEmailAndPassword($email,$password);
        return $user;
    }

    public function getUserByEmail($email) {
        $user = $this->userPDO->getUserByEmail($email);
        return $user;
    }


    
}

?>
