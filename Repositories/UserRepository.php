<?php

require_once __DIR__ . '/../Controllers/UserController.php';


class UserRepository {
    private $userController;

    public function __construct() {
        $this->userController = new UserController();
    }

    public function getUserById($userId) {
        return $this->userController->getUserById($userId);
    }

    public function getAllUsers() {
        return $this->userController->getAllUsers();
    }

    public function addUser($username, $email, $password) {
        $this->userController->addUser($username, $email, $password);
    }

    public function getUserByEmailAndPassword($email, $password) {
        return $this->userController->getUserByEmailAndPassword($email, $password);
    }

    public function getUserByEmail($email) {
        return $this->userController->getUserByEmailAndPassword($email, $password);
    }
}

?>
