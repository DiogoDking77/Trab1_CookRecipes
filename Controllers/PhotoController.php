<?php

require_once __DIR__ . '/../PDO/PhotoPDO.php';

class PhotoController {
    private $photoPDO;

    public function __construct() {
        $this->photoPDO = new PhotoPDO();
    }

    public function getPhotosByRecipeId($recipeId) {
        return $this->photoPDO->getPhotosByRecipeId($recipeId);
    }

    public function addPhoto($photo, $recipeId) {
        $this->photoPDO->addPhoto($photo, $recipeId);
    }
}

?>
