<?php

require_once __DIR__ . '/../Controllers/NoteController.php';

class NotesRepository {
    private $notesController;

    public function __construct() {
        $this->notesController = new NotesController();
    }

    public function getNotesByRecipeId($recipeId) {
        return $this->notesController->getNotesByRecipeId($recipeId);
    }

    public function addNotes($notes, $recipeId) {
        $this->notesController->addNotes($notes, $recipeId);
    }
}

?>
