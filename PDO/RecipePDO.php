<?php

require_once __DIR__ . '/../DB/config.php';

class RecipePDO {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getRecipeById($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE Recipe_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllRecipes() {
        $stmt = $this->pdo->query("SELECT * FROM Recipe");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRecipe($name, $description, $instructions, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO Recipe (Recipe_Name, Recipe_Description, Recipe_Instructions, User_ID) VALUES (:name, :description, :instructions, :userId)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':instructions', $instructions, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
