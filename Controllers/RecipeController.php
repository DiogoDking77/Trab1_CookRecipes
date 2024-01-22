<?php


require_once __DIR__ . '/../DB/config.php';
require_once 'UserController.php';
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
        $this->userController = new UserController();
        $this->ingredientController = new IngredientController();
        $this->hintsController = new HintController();
        $this->notesController = new NotesController();
        $this->photosController = new PhotoController();
        $this->categoryController = new CategoryController();
    }
    public function getRecipeById($recipeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE TRIM(Recipe_Name) <> '' AND Recipe_ID = :recipeId");
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getRecipesById($recipeId, $userLoggedIn) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE TRIM(Recipe_Name) <> '' AND Recipe_ID = :recipeId");
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
            $recipe['categories'] = $categories;
    
            // Adicionar contagem de favoritos
            $stmtFavorites = $this->pdo->prepare("SELECT COUNT(*) as FavoriteCount FROM Favorites WHERE Recipe_ID = :recipe_id");
            $stmtFavorites->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
            $stmtFavorites->execute();
            $favoriteCount = $stmtFavorites->fetch(PDO::FETCH_ASSOC)['FavoriteCount'];
            $recipe['favoriteCount'] = $favoriteCount;
    
            // Adicionar contagem de compartilhamentos
            $stmtShares = $this->pdo->prepare("SELECT COUNT(*) as ShareCount FROM Shared_Recipes WHERE Recipe_ID = :recipe_id");
            $stmtShares->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
            $stmtShares->execute();
            $shareCount = $stmtShares->fetch(PDO::FETCH_ASSOC)['ShareCount'];
            $recipe['shareCount'] = $shareCount;
    
            $stmtFavoriteStatus = $this->pdo->prepare("SELECT * FROM Favorites WHERE User_ID = :userLoggedIn AND Recipe_ID = :recipe_id");
            $stmtFavoriteStatus->bindParam(':userLoggedIn', $userLoggedIn, PDO::PARAM_STR);
            $stmtFavoriteStatus->bindParam(':recipe_id', $recipeId, PDO::PARAM_STR);
            $stmtFavoriteStatus->execute();
            $rowCount = $stmtFavoriteStatus->rowCount();
            $isFavorited = ($rowCount > 0);
            $recipe['isFavorited'] = $isFavorited;
    
            $creator = $this->userController->getUserById($recipe[0]['User_ID']);
            $recipe['creator'] = $creator;
    
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
    public function getRecipes() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Recipe WHERE TRIM(Recipe_Name) <> '' ORDER BY Creation_Date DESC LIMIT 15");
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($recipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;
        
                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
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
            $stmt = $this->pdo->prepare("SELECT * FROM Recipe WHERE TRIM(Recipe_Name) <> '' AND User_ID = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($recipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;
    
                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
            }
    
            return $recipes;
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
                WHERE Favorites.User_ID = :user_id AND TRIM(Recipe_Name) <> ''");
    
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

            $stmtFavorites = $this->pdo->prepare("SELECT COUNT(*) as FavoriteCount FROM Favorites WHERE Recipe_ID = :recipe_id");
            $stmtFavorites->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
            $stmtFavorites->execute();
            $favoriteCount = $stmtFavorites->fetch(PDO::FETCH_ASSOC)['FavoriteCount'];
            $recipe['favoriteCount'] = $favoriteCount;

            // Retorna o ID da receita criada
            return $recipe;
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

            $stmtFavorites = $this->pdo->prepare("SELECT COUNT(*) as FavoriteCount FROM Favorites WHERE Recipe_ID = :recipe_id");
            $stmtFavorites->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
            $stmtFavorites->execute();
            $favoriteCount = $stmtFavorites->fetch(PDO::FETCH_ASSOC)['FavoriteCount'];
            $recipe['favoriteCount'] = $favoriteCount;
    
            // Retorna true para indicar sucesso
            return $recipe;
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
    public function ShareRecipe($userId, $friendId, $recipeId) {
        try {
            // Verificar se o registro já existe
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM Shared_Recipes WHERE Sharing_User_ID = :sharing AND Receiving_User_ID = :receiving AND Recipe_ID = :recipeId");
            $stmtCheck->bindParam(':sharing', $userId, PDO::PARAM_STR);
            $stmtCheck->bindParam(':receiving', $friendId, PDO::PARAM_STR);
            $stmtCheck->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmtCheck->execute();
    
            $count = $stmtCheck->fetchColumn();
    
            if ($count > 0) {
                // O registro já existe, não é necessário inserir novamente
                return true;
            }
    
            // Se o registro não existe, faça a inserção
            $stmtInsert = $this->pdo->prepare("INSERT INTO Shared_Recipes (Sharing_User_ID, Receiving_User_ID, Recipe_ID) VALUES (:sharing, :receiving, :recipeId)");
            $stmtInsert->bindParam(':sharing', $userId, PDO::PARAM_STR);
            $stmtInsert->bindParam(':receiving', $friendId, PDO::PARAM_STR);
            $stmtInsert->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmtInsert->execute();
    
            return true;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    
    public function getSharedRecipes($userId){
        try {
            $stmt = $this->pdo->prepare("SELECT Recipe.*, Users.User_Email AS User_Email
                FROM Recipe
                JOIN Shared_Recipes ON Recipe.Recipe_ID = Shared_Recipes.Recipe_ID
                JOIN Users ON Shared_Recipes.Sharing_User_ID = Users.User_ID
                WHERE Shared_Recipes.Receiving_User_ID = :user_id AND TRIM(Recipe.Recipe_Name) <> ''");
    
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
            $stmt->execute();
    
            $sharedRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($sharedRecipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;
    
                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
    
                
            }
    
            return $sharedRecipes;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }
    
    
    
    public function deleteRecipe($recipeId) {
        try {
            // Atualiza o nome da receita para null
            $stmt = $this->pdo->prepare("UPDATE Recipe SET Recipe_Name = NULL WHERE Recipe_ID = :recipeId");
            $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
            $stmt->execute();
    
            // Retorna true para indicar sucesso
            return true;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function getTopSharedRecipes() {
        try {
            $stmt = $this->pdo->query("SELECT Recipe.*, COUNT(Shared_Recipes.Recipe_ID) AS ShareCount
                FROM Recipe
                LEFT JOIN Shared_Recipes ON Recipe.Recipe_ID = Shared_Recipes.Recipe_ID
                WHERE TRIM(Recipe.Recipe_Name) <> ''
                GROUP BY Recipe.Recipe_ID
                ORDER BY ShareCount DESC
                LIMIT 15");

            $topSharedRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($topSharedRecipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;

                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
            }

            return $topSharedRecipes;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function getTopFavoriteRecipes() {
        try {
            $stmt = $this->pdo->query("SELECT Recipe.*, COUNT(Favorites.Recipe_ID) AS FavoriteCount
                FROM Recipe
                LEFT JOIN Favorites ON Recipe.Recipe_ID = Favorites.Recipe_ID
                WHERE TRIM(Recipe.Recipe_Name) <> ''
                GROUP BY Recipe.Recipe_ID
                ORDER BY FavoriteCount DESC
                LIMIT 15");

            $topFavoriteRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($topFavoriteRecipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;

                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
            }

            return $topFavoriteRecipes;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function searchRecipes($searchParam) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM Recipe
                WHERE TRIM(Recipe_Name) <> '' AND (
                    Recipe_Name LIKE :searchParamStart
                    OR Recipe_Name LIKE :searchParamMiddle
                    OR Recipe_Description LIKE :searchParam
                )
                ORDER BY
                    CASE
                        WHEN Recipe_Name LIKE :searchParamStart THEN 0
                        WHEN Recipe_Name LIKE :searchParamMiddle THEN 1
                        ELSE 2
                    END,
                    Recipe_Name ASC
            ");
    
            $searchParamStart = $searchParam . '%';
            $searchParamMiddle = '%' . $searchParam . '%';
            $stmt->bindParam(':searchParamStart', $searchParamStart, PDO::PARAM_STR);
            $stmt->bindParam(':searchParamMiddle', $searchParamMiddle, PDO::PARAM_STR);
            $stmt->bindParam(':searchParam', $searchParamMiddle, PDO::PARAM_STR);
            $stmt->execute();
    
            $searchedRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($searchedRecipes as &$recipe) {
                $recipeId = $recipe['Recipe_ID'];
                $photos = $this->photosController->getPhotosByRecipeId($recipeId);
                $recipe['photos'] = $photos;
    
                $categories = $this->categoryController->getCategoryByRecipeId($recipeId);
                $recipe['categories'] = $categories;
            }
    
            return $searchedRecipes;
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
$usersController = new UserController();
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
            case 'getYourRecipes':
                if (isset($_GET['userId'])) {
                    $userId = $_GET['userId'];
                    $recipeDetails = $recipeController->getRecipesByUserId($userId);
                    header('Content-Type: application/json');
                    echo json_encode(['recipes' => $recipeDetails]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetros insuficientes para getFavoriteRecipes']);
                    exit;
                }
            case 'getSharedRecipes':
                if (isset($_GET['userId'])) {
                    $userId = $_GET['userId'];
                    $recipeDetails = $recipeController->getSharedRecipes($userId);
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
            case 'GetUsers':
                if (isset($_GET['userId'])) {
                    $users = $usersController->getUsers($_GET['userId']);
                    header('Content-Type: application/json');
                    echo json_encode(['users' => $users]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetros insuficientes para getFavoriteRecipes']);
                    exit;
                }
            case 'getTopSharedRecipes':
                $topSharedRecipes = $recipeController->getTopSharedRecipes();
                header('Content-Type: application/json');
                echo json_encode(['recipeDetails' => $topSharedRecipes]);
                exit;

            case 'getTopFavoriteRecipes':
                $topFavoriteRecipes = $recipeController->getTopFavoriteRecipes();
                header('Content-Type: application/json');
                echo json_encode(['recipeDetails' => $topFavoriteRecipes]);
                exit;

            case 'searchRecipes':
                if (isset($_GET['searchParam'])) {
                    $searchParam = $_GET['searchParam'];
                    $searchedRecipes = $recipeController->searchRecipes($searchParam);
                    header('Content-Type: application/json');
                    echo json_encode(['recipeDetails' => $searchedRecipes]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Parâmetros insuficientes para searchRecipes']);
                    exit;
                }
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
                header('Content-Type: application/json');
                echo json_encode(['result' => $result]);
            } else {
                handleErrorResponse('Invalid request or missing parameters');
            }
            break;

        case 'UndoFavorites':
            if (isset($_POST['recipeId'], $_POST['userId'])) {
                $result = $recipeController->UndoFavorites($_POST['recipeId'], $_POST['userId']);
                header('Content-Type: application/json');
                echo json_encode(['result' => $result]);
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
            case 'DeleteRecipe':
                if (isset($_POST['recipeId'], $_POST['userPassword'], $_POST['userId'])) {
                    // Verificar a senha
                    $isPassword = $usersController->verifyPassword($_POST['userPassword'], $_POST['userId']);
            
                    if ($isPassword) {
                        // Executar a função que define o nome da receita para null
                        $result = $recipeController->deleteRecipe($_POST['recipeId']);
            
                        if ($result) {
                            // Sucesso, incluir a URL de redirecionamento
                            $response = [
                                'success' => true,
                                'redirect' => 'dashboard.php'
                            ];
            
                            // Envie a resposta como JSON
                            header('Content-Type: application/json');
                            echo json_encode($response);
                            exit;
                        } else {
                            // Ocorreu um erro na exclusão
                            header('Content-Type: application/json');
                            echo json_encode(['error' => 'Error deleting recipe']);
                            exit;
                        }
                    } else {
                        // Senha incorreta, envie uma resposta de erro
                        header('Content-Type: application/json');
                        echo json_encode(['error' => 'Incorrect password']);
                        exit;
                    }
                } else {
                    // Se faltar algum parâmetro, envie uma resposta de erro
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Invalid request or missing parameters']);
                    exit;
                }
                break;            
        case 'ShareRecipe':
            if (isset($_POST['userId'],$_POST['friendId'],$_POST['recipeId'])) {
                $result = $recipeController->ShareRecipe($_POST['userId'],$_POST['friendId'],$_POST['recipeId']);
        
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