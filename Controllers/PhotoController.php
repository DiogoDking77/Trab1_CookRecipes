<?php

require_once __DIR__ . '/../DB/config.php';

class PhotoController {
    private $pdo;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
    }

    public function getPhotosByRecipeId($recipeId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Photos WHERE Recipes_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function addPhoto($recipeId, $base64Image) {
        try {
            
            
    
            // Insere a imagem binária no banco de dados
            $stmt = $this->pdo->prepare("INSERT INTO Photos (Photo, Recipes_ID) VALUES (:photo, :recipeId)");
            $stmt->bindParam(':photo', $base64Image, PDO::PARAM_LOB);
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Verifica se a execução foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                // Retorna um array indicando o sucesso
                return ['success' => true];
            } else {
                // Retorna um array indicando a falha e uma mensagem de erro
                return ['success' => false, 'error' => 'Falha ao inserir a foto no banco de dados.'];
            }
        } catch (PDOException $e) {
            // Retorna um array indicando a falha e a mensagem de exceção
            return ['success' => false, 'error' => 'Erro de banco de dados: ' . $e->getMessage()];
        }
    }

    public function deletePhotos($recipeId) {
        try {
            // Exclui todas as fotos associadas ao Recipe_ID
            $stmt = $this->pdo->prepare("DELETE FROM Photos WHERE Recipes_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Verifica se a execução foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                // Retorna um array indicando o sucesso
                return ['success' => true];
            } else {
                // Retorna um array indicando a falha e uma mensagem de erro
                return ['success' => false, 'error' => 'Nenhuma foto encontrada para o Recipe_ID fornecido.'];
            }
        } catch (PDOException $e) {
            // Retorna um array indicando a falha e a mensagem de exceção
            return ['success' => false, 'error' => 'Erro de banco de dados: ' . $e->getMessage()];
        }
    }
    
    
}
?>