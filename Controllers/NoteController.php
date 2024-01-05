<?php

require_once __DIR__ . '/../DB/config.php';

class NotesController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getNotesByRecipeId($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Notes WHERE Recipes_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addNotes($notes, $recipeId) {
        $stmt = $this->pdo->prepare("INSERT INTO Notes (Notes, Recipes_ID) VALUES (:notes, :recipeId)");
        $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>
