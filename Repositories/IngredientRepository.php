<?php

require_once __DIR__ . '/../Controllers/IngredientController.php';

class IngredientRepository {
    private $ingredientController;

    public function __construct() {
        $this->ingredientController = new IngredientController();
    }

    public function getIngredientsByRecipeId($recipeId) {
        return $this->ingredientController->getIngredientsByRecipeId($recipeId);
    }

    public function addIngredient($name, $quantity, $recipeId) {
        $this->ingredientController->addIngredient($name, $quantity, $recipeId);
    }
}

?>
