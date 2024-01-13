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

    public function getRecipesById($recipeId,$userLoggedIn) {
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

            $ingredients = $this->ingredientController->getIngredientsByRecipeId($recipeId);
            $recipe['ingredients'] = $ingredients;

            $stmt = $this->pdo->prepare("SELECT * FROM Favorites WHERE User_ID = :userLoggedIn AND Recipe_ID = :recipe_id");
            $stmt->bindParam(':userLoggedIn', $userLoggedIn, PDO::PARAM_STR);
            $stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_STR);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $isFavorited = ($rowCount > 0);
            $recipe['isFavorited'] = $isFavorited;

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

    public function getFavoriteRecipesByUserId($user_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT Recipe.* FROM Recipe
                JOIN Favorites ON Recipe.Recipe_ID = Favorites.Recipe_ID
                WHERE Favorites.User_ID = :user_id");
    
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->execute();
    
            $favoriteRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($favoriteRecipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;
    
                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
            }
    
            return $favoriteRecipes;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    
    public function SetFavorites($recipe_id, $user_id){
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Favorites (User_ID, Recipe_ID) VALUES (:user_id, :recipe_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_STR);

            $stmt->execute();

            // Retorna o ID da receita criada
            return true;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function UndoFavorites($recipe_id, $user_id){
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Favorites WHERE User_ID = :user_id AND Recipe_ID = :recipe_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_STR);
    
            $stmt->execute();
    
            // Retorna true para indicar sucesso
            return true;
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
                if (isset($_GET['recipeId'], $_GET['userId'])) {
                    // Acesso seguro ao parâmetro 'recipeId'
                    $recipeId = $_GET['recipeId'];
                    $userLoggedIn = $_GET['userId'];

                    
                    // Obtenha detalhes da receita pelo ID
                    $recipeDetails = $recipeController->getRecipesById($recipeId,$userLoggedIn);
        
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
            case 'getFavoriteRecipes':
                try {
                    // Verifique se o parâmetro recipeId está definido na solicitação
                    if (isset($_GET['userId'])) {
                        // Acesso seguro ao parâmetro 'recipeId'
                        $userId = $_GET['userId'];
                        
                        // Obtenha detalhes da receita pelo ID
                        $recipeDetails = $recipeController->getFavoriteRecipesByUserId($userId);
            
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
$recipeController = new RecipeController(); // Mova a criação da instância para cá

// Verifica se os parâmetros esperados estão presentes

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'addRecipe':
            // Lógica para adicionar uma receita
            if (isset($data['recipeName'], $data['recipeDescription'], $data['recipeInstructions'], $data['ingredients'], $data['hints'], $data['notes'])) {
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
                    handleErrorResponse('Failed to create recipe');
                }
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;

        case 'SetFavorites':
            if (isset($_POST['recipeId'], $_POST['userId'])) {
                $result = $recipeController->SetFavorites($_POST['recipeId'], $_POST['userId']);
                handleResult($result);
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;

        case 'UndoFavorites':
            if (isset($_POST['recipeId'], $_POST['userId'])) {
                $result = $recipeController->UndoFavorites($_POST['recipeId'], $_POST['userId']);
                handleResult($result);
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;

        // Adicione mais casos conforme necessário

        default:
            handleErrorResponse('Ação inválida');
    }
} else {
    handleErrorResponse('Ação não definida');
}


// Função para lidar com sucesso
function handleResult($result) {
    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        handleErrorResponse('Operation failed');
    }
    exit;
}

// Função para lidar com erros
function handleErrorResponse($errorMessage) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $errorMessage]);
    exit;
}





?>
