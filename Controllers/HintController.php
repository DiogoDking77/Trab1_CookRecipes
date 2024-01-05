<?php

require_once __DIR__ . '/../DB/config.php';

class HintController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getHintsByRecipeId($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Hint WHERE Recipes_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addHint($hint, $recipeId) {
        $stmt = $this->pdo->prepare("INSERT INTO Hint (Hint, Recipes_ID) VALUES (:hint, :recipeId)");
        $stmt->bindParam(':hint', $hint, PDO::PARAM_STR);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
