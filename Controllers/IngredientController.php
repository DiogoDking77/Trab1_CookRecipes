<?php

require_once __DIR__ . '/../DB/config.php';

class IngredientController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getIngredientsByRecipeId($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Ingredients WHERE Recipes_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addIngredient($name, $quantity, $recipeId) {
        $stmt = $this->pdo->prepare("INSERT INTO Ingredients (Ingredients_Name, Ingredients_Quantity, Recipes_ID) VALUES (:name, :quantity, :recipeId)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
