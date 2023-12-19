<?php

require_once __DIR__ . '/../PDO/RecipePDO.php';

class RecipeController {
    private $recipePDO;

    public function __construct() {
        $this->recipePDO = new RecipePDO();
    }

    public function getRecipeById($recipeId) {
        return $this->recipePDO->getRecipeById($recipeId);
    }

    public function getAllRecipes() {
        return $this->recipePDO->getAllRecipes();
    }

    public function addRecipe($name, $description, $instructions, $userId) {
        $this->recipePDO->addRecipe($name, $description, $instructions, $userId);
    }
}

?>
