<?php

require_once __DIR__ . '/../Controllers/HintController.php';

class HintRepository {
    private $hintController;

    public function __construct() {
        $this->hintController = new HintController();
    }

    public function getHintsByRecipeId($recipeId) {
        return $this->hintController->getHintsByRecipeId($recipeId);
    }

    public function addHint($hint, $recipeId) {
        $this->hintController->addHint($hint, $recipeId);
    }
}

?>
