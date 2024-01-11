<?php

require_once __DIR__ . '/../DB/config.php';

class CategoryController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getCategoryByRecipeId($recipeId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.*
                FROM Category c
                INNER JOIN Recipe_Category rc ON c.Category_ID = rc.Category_ID
                WHERE rc.Recipe_ID = :recipeId
            ");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    

    public function addHint($hint, $recipeId) {
        echo($recipeId);
        $stmt = $this->pdo->prepare("INSERT INTO Hint (Hint, Recipes_ID) VALUES (:hint, :recipeId)");
        $stmt->bindParam(':hint', $hint, PDO::PARAM_STR);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>