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

    public function getCategories() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Category");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    

    public function addCategory($categoryId, $recipeId) {
        try{
            $stmt = $this->pdo->prepare("INSERT INTO Recipe_Category (Category_ID, Recipe_ID) VALUES (:categoryId, :recipeId)");
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_STR);
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
        
    }
}

?>