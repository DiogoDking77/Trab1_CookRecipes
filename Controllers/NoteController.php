<?php

require_once __DIR__ . '/../PDO/NotePDO.php';

class NotesController {
    private $notesPDO;

    public function __construct() {
        $this->notesPDO = new NotesPDO();
    }

    public function getNotesByRecipeId($recipeId) {
        return $this->notesPDO->getNotesByRecipeId($recipeId);
    }

    public function addNotes($notes, $recipeId) {
        $this->notesPDO->addNotes($notes, $recipeId);
    }
}

?>
