<?php

require_once __DIR__ . '/../DB/config.php';

class PhotoController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getPhotosByRecipeId($recipeId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Photos WHERE Recipes_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function addPhoto($photo, $recipeId) {
        $stmt = $this->pdo->prepare("INSERT INTO Photos (Photo, Recipes_ID) VALUES (:photo, :recipeId)");
        $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
