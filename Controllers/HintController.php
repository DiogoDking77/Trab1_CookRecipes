<?php

require_once __DIR__ . '/../PDO/HintPDO.php';

class HintController {
    private $hintPDO;

    public function __construct() {
        $this->hintPDO = new HintPDO();
    }

    public function getHintsByRecipeId($recipeId) {
        return $this->hintPDO->getHintsByRecipeId($recipeId);
    }

    public function addHint($hint, $recipeId) {
        $this->hintPDO->addHint($hint, $recipeId);
    }
}

?>
