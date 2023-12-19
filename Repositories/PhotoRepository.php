<?php

require_once __DIR__ . '/../Controllers/PhotoController.php';

class PhotoRepository {
    private $photoController;

    public function __construct() {
        $this->photoController = new PhotoController();
    }

    public function getPhotosByRecipeId($recipeId) {
        return $this->photoController->getPhotosByRecipeId($recipeId);
    }

    public function addPhoto($photo, $recipeId) {
        $this->photoController->addPhoto($photo, $recipeId);
    }
}

?>

