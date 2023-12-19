<?php

require_once __DIR__ . '/../PDO/IngredientPDO.php';

class IngredientController {
    private $ingredientPDO;

    public function __construct() {
        $this->ingredientPDO = new IngredientPDO();
    }

    public function getIngredientsByRecipeId($recipeId) {
        return $this->ingredientPDO->getIngredientsByRecipeId($recipeId);
    }

    public function addIngredient($name, $quantity, $recipeId) {
        $this->ingredientPDO->addIngredient($name, $quantity, $recipeId);
    }
}

?>
