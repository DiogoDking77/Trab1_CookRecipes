<?php

require_once __DIR__ . '/../DB/config.php';

class NotesController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getNotesByRecipeId($recipeId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Notes WHERE Recipes_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function addNotes($notes, $recipeId) {
        $stmt = $this->pdo->prepare("INSERT INTO Notes (Notes, Recipes_ID) VALUES (:notes, :recipeId)");
        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteNotes($recipeId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Notes WHERE Recipes_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
}

?>
