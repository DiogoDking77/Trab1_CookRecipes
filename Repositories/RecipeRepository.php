<?php

require_once __DIR__ . '/../Controllers/RecipeController.php';

class RecipeRepository {
    private $recipeController;

    public function __construct() {
        $this->recipeController = new RecipeController();
    }

    public function getRecipeById($recipeId) {
        return $this->recipeController->getRecipeById($recipeId);
    }

    public function getAllRecipes() {
        return $this->recipeController->getAllRecipes();
    }

    public function addRecipe($name, $description, $instructions, $userId) {
        $this->recipeController->addRecipe($name, $description, $instructions, $userId);
    }
}

?>
