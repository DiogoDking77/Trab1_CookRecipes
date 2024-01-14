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

            $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
            $recipe['categories']= $categories;

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
            $stmt->execute();
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
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'recipeId' => $recipeId]); // Adiciona o recipeId à resposta JSON
            exit;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    public function editRecipe($recipeId, $name, $description, $instructions, $ingredients, $hints, $notes, $userId) {
        try {
            // Atualiza os dados da receita
            $stmt = $this->pdo->prepare("UPDATE Recipe SET Recipe_Name = :name, Recipe_Description = :description, Recipe_Instructions = :instructions WHERE Recipe_ID = :recipeId AND User_ID = :userId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':instructions', $instructions, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Atualiza os ingredientes
            $this->ingredientController->deleteIngredients($recipeId); // Remove os ingredientes existentes
            foreach ($ingredients as $ingredient) {
                $this->ingredientController->addIngredient($ingredient['name'], $ingredient['quantity'], $recipeId);
            }
    
            // Atualiza as dicas
            $this->hintsController->deleteHints($recipeId); // Remove as dicas existentes
            foreach ($hints as $hint) {
                $this->hintsController->addHint($hint, $recipeId);
            }
    
            // Atualiza as notas
            $this->notesController->deleteNotes($recipeId); // Remove as notas existentes
            foreach ($notes as $note) {
                $this->notesController->addNotes($note, $recipeId);
            }
    
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'recipeId' => $recipeId]);
            exit;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    
    
}
$recipeController = new RecipeController();
$photoController = new PhotoController();
$categoriesController = new CategoryController();
// Verifica o método da requisição
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        error_log("GET action: " . $action);

        switch ($action) {
            case 'getRecipes':
                $recipes = $recipeController->getRecipes();
                header('Content-Type: application/json');
                echo json_encode(['recipes' => $recipes]);
                exit;
            case 'getRecipesById':
                error_log("Executing getRecipesById");
                if (isset($_GET['recipeId'], $_GET['userId'])) {
                    $recipeId = $_GET['recipeId'];
                    $userLoggedIn = $_GET['userId'];
                    $recipeDetails = $recipeController->getRecipesById($recipeId, $userLoggedIn);
                    header('Content-Type: application/json');
                    echo json_encode(['recipeDetails' => $recipeDetails]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetros insuficientes para getRecipesById']);
                    exit;
                }
            case 'getFavoriteRecipes':
                if (isset($_GET['userId'])) {
                    $userId = $_GET['userId'];
                    $recipeDetails = $recipeController->getFavoriteRecipesByUserId($userId);
                    header('Content-Type: application/json');
                    echo json_encode(['recipeDetails' => $recipeDetails]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetros insuficientes para getFavoriteRecipes']);
                    exit;
                }
            case 'getCategories':
                $categories = $categoriesController->getCategories();
                header('Content-Type: application/json');
                echo json_encode(['categories' => $categories]);
                exit;
            default:
                error_log("GET action not recognized: " . $action);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Ação GET inválida']);
                exit;
        }
    } else {
        error_log("GET action not set");
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ação GET não definida']);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        error_log("POST action: " . $action);

        
    switch ($action) {
        case 'addRecipe':

            // Lógica para adicionar uma receita
            if (isset($_POST['action'], $_POST['recipeName'], $_POST['description'], $_POST['instructions'], $_POST['ingredientsArray'], $_POST['hintsArray'], $_POST['notesArray'], $_POST['userId'])) {

                $result = $recipeController->addRecipe(
                    $_POST['recipeName'],
                    $_POST['description'],
                    $_POST['instructions'],
                    $_POST['ingredientsArray'],
                    $_POST['hintsArray'],
                    $_POST['notesArray'],
                    $_POST['userId'],
                );

                header('Content-Type: application/json');
                echo json_encode(['result' => $result]);
                exit;
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
        case 'addPhotos':
            if (isset($_POST['recipeId'], $_POST['imageIndex'], $_POST['imageData'])) {
                $result = $photoController->addPhoto($_POST['recipeId'],$_POST['imageData']);
        
                // Envie a resposta como JSON
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            } else {
                // Se faltar algum parâmetro, envie uma resposta de erro
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid request or missing parameters']);
                exit;
            }
            break;
        case 'setCategories':
            if (isset($_POST['categoryIds'], $_POST['recipeId'])) {
                $categoryIds = $_POST['categoryIds'];
                $successCount = 0;  // Contador para rastrear o número de vezes que o resultado é verdadeiro

                foreach ($categoryIds as $categoryId) {
                    $result = $categoriesController->addCategory($categoryId, $_POST['recipeId']);
                    if ($result) {
                        $successCount++;
                    }
                }
                if ($successCount === count($categoryIds)) {
                    handleResult($result);
                } else {
                    // Trata o caso em que nem todos os resultados foram bem-sucedidos
                    handleErrorResponse('Some categories were not added successfully');
                }
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;
        case 'editRecipe':

            // Lógica para adicionar uma receita
            if (isset($_POST['action'],$_POST['recipeId'], $_POST['recipeName'], $_POST['description'], $_POST['instructions'], $_POST['ingredientsArray'], $_POST['hintsArray'], $_POST['notesArray'], $_POST['userId'])) {
                
                $result = $recipeController->editRecipe(
                    $_POST['recipeId'],
                    $_POST['recipeName'],
                    $_POST['description'],
                    $_POST['instructions'],
                    $_POST['ingredientsArray'],
                    $_POST['hintsArray'],
                    $_POST['notesArray'],
                    $_POST['userId'],
                );

                header('Content-Type: application/json');
                echo json_encode(['result' => $result]);
                exit;
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;
        case 'deletePhotos':
            if (isset($_POST['recipeId'])) {
                $result = $photoController->deletePhotos($_POST['recipeId']);
        
                // Envie a resposta como JSON
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            } else {
                // Se faltar algum parâmetro, envie uma resposta de erro
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid request or missing parameters']);
                exit;
            }
            break;
        default:
            error_log("POST action not recognized: " . $action);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Ação POST inválida']);
            exit;
    }
    } else {
        error_log("POST action not set");
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ação POST não definida']);
        exit;
    }
} else {
    error_log("Unsupported method: " . $_SERVER['REQUEST_METHOD']);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Método não suportado']);
    exit;
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