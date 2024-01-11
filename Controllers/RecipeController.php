<?php

require_once __DIR__ . '/../DB/config.php';
require_once 'IngredientController.php';
require_once 'HintController.php';
require_once 'NoteController.php';
require_once 'PhotoController.php';
require_once 'CategoryController.php';

class RecipeController {
    private $pdo;
    private $ingredientController;
    private $hintsController;
    private $notesController;
    private $photosController;
    private $categoryController;

    public function __construct() {
        $this->pdo = pdo_connection_mysql();
        $this->ingredientController = new IngredientController();
        $this->hintsController = new HintController();
        $this->notesController = new NotesController();
        $this->photosController = new PhotoController();
        $this->categoryController = new CategoryController();
    }

    public function getRecipeById($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE Recipe_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecipesById($recipeId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE Recipe_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
            $recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            $photos = $this->photosController->getPhotosByRecipeId($recipeId);
            $recipe['photos'] = $photos;

            $hints = $this->hintsController->getHintsByRecipeId($recipeId);
            $recipe['hints'] = $hints;

            $notes = $this->notesController->getNotesByRecipeId($recipeId);
            $recipe['notes'] = $notes;

            // Retorne a resposta JSON
            header('Content-Type: application/json');
            echo json_encode(['recipe' => $recipe]);
            exit;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    

    // Modifique o método getRecipes na sua classe RecipeController
    // Adapte o método getRecipes para incluir as fotos usando o PhotoController
    public function getRecipes() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Recipe");
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($recipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;

                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories']= $categories;
            }

            return $recipes;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }



    public function getRecipesByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE User_ID = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function addRecipe($name, $description, $instructions, $ingredients, $hints, $notes, $userId) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Recipe (Recipe_Name, Recipe_Description, Recipe_Instructions, User_ID) VALUES (:name, :description, :instructions, :userId)");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':instructions', $instructions, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        
            // Executa a instrução SQL
            $stmt->execute();
        
            // Obtém o ID da última inserção
            $recipeId = $this->pdo->lastInsertId();
        
            foreach ($ingredients as $ingredient) {
                $this->ingredientController->addIngredient($ingredient['name'], $ingredient['quantity'], $recipeId);
            }
        
            foreach ($hints as $hint) {
                $this->hintsController->addHint($hint, $recipeId);
            }
        
            foreach ($notes as $note) {
                $this->notesController->addNotes($note, $recipeId);
            }
        
            // Retorna o ID da receita criada
            return $recipeId;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function processFormData($recipeName, $recipeDescription, $recipeInstructions, $ingredients, $hints, $notes) {
        // Adiciona a receita
        $userId = 1; // Substitua pelo ID do usuário real
        $recipeId = $this->addRecipe($recipeName, $recipeDescription, $recipeInstructions, $ingredients, $hints, $notes, $userId);
    
        // Retorna o ID da receita criada
        header('Content-Type: application/json');
        echo json_encode(['recipeId' => $recipeId]);
        exit;
    }
    
}

// Verifica se os parâmetros esperados estão presentes
$recipeController = new RecipeController(); // Mova a criação da instância para cá
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Execute a lógica correspondente à ação
    switch ($action) {
        case 'getRecipes':
            try {
                // Obtenha todas as receitas com fotos
                $recipes = $recipeController->getRecipes();
        
                // Envie a resposta como JSON
                header('Content-Type: application/json');
                echo json_encode(['recipes' => $recipes]);
                exit;
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Erro ao obter receitas: ' . $e->getMessage(), 'trace' => $e->getTrace()]);
                exit;
            }
        case 'getRecipesById':
            try {
                // Verifique se o parâmetro recipeId está definido na solicitação
                if (isset($_GET['recipeId'])) {
                    // Acesso seguro ao parâmetro 'recipeId'
                    $recipeId = $_GET['recipeId'];
                    
                    // Obtenha detalhes da receita pelo ID
                    $recipeDetails = $recipeController->getRecipesById($recipeId);
        
                    // Envie a resposta como JSON
                    header('Content-Type: application/json');
                    echo json_encode(['recipeDetails' => $recipeDetails]);
                    exit;
                } else {
                    // Se o parâmetro recipeId não estiver definido, envie uma resposta de erro
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetro recipeId não definido']);
                    exit;
                }
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Erro ao obter detalhes da receita: ' . $e->getMessage(), 'trace' => $e->getTrace()]);
                exit;
            }

        // Adicione mais casos conforme necessário

        default:
            // Se a ação não for reconhecida, envie uma resposta de erro
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Ação inválida']);
            exit;
    }
}


// Verifica se os parâmetros esperados estão presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    if (isset($data['recipeName'], $data['recipeDescription'], $data['recipeInstructions'], $data['ingredients'], $data['hints'], $data['notes'])) {
        // $recipeController = new RecipeController(); // Remova essa linha, pois já instanciamos no início
        $recipeId = $recipeController->processFormData(
            $data['recipeName'],
            $data['recipeDescription'],
            $data['recipeInstructions'],
            $data['ingredients'],
            $data['hints'],
            $data['notes']
        );

        if ($recipeId) {
            header('Content-Type: application/json');
            echo json_encode(['recipeId' => $recipeId, 'redirectUrl' => 'dashboard.php']);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to create recipe']);
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request or missing parameters']);
        exit;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}



?>
