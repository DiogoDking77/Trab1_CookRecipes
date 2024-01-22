<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Se 'user_id' não estiver definido na sessão, redirecione para a página de login ou faça outra manipulação
    header('Location: login.php');
    exit(); // Certifique-se de encerrar o script após redirecionar
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../CSS/SharedRecipes.css">
    <script>
        var userIdFromPHP = <?php echo json_encode($user_id); ?>;
        console.log("User ID from PHP: " + userIdFromPHP);
    </script>
    <script src="../../JavaScript/SharedRecipes.js"> </script>   
    <title>Gestão de Receitas Culinárias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English&family=Pixelify+Sans&family=Raleway:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>
        .nav-item:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(103deg, rgba(91, 91, 91, 1) 0%, rgba(59, 59, 59, 1) 98%); border-bottom: 5px solid transparent; border-image-slice: 1; border-image-source: linear-gradient(90deg, rgba(156, 105, 14, 1) 0%, rgba(180, 124, 20, 1) 93%); border-image-width: 1 1 5px 1;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <!-- Place your logo here -->
                <img src="../../Images/logo2.png" alt="Logo" height="50" style="max-height: 60px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Recipes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="yourRecipes.php">Your Recipes</a></li>
                            <li><a class="dropdown-item" href="FavoritedRecipes.php">Favorite Recipes</a></li>
                            <li><a class="dropdown-item" href="SharedRecipes.php">Shared Recipes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="createRecipe.php">Create Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login.php">Logout</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <input class="form-control me-2" type="search" id="searchInput" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" onclick="redirectSearch()" type="submit">Search</button>
                </div>
            </div>
        </div>
    </nav>

    <main>
    
        
        <!-- Adicione uma lista com um ID específico para exibir as receitas -->
        <h2 class="divider line double-razor">Shared with you</h2>
        <div class="container-fluid mt-2 recipes-container">
            <div class="row justify-content-center" id="sharedRecipesList">
                <!-- Cards serão adicionadas aqui -->
            </div>
        </div>

        






        <!-- Seu restante de conteúdo existente... -->

        <!-- Adicione um script para lidar com a solicitação de receitas ao clicar no link -->
        
        
    </main>

    <footer class="text-white" style="background: linear-gradient(103deg, rgba(91, 91, 91, 1) 0%, rgba(59, 59, 59, 1) 98%); border-top: 5px solid transparent; border-image-slice: 1; border-image-source: linear-gradient(90deg, rgba(156, 105, 14, 1) 0%, rgba(180, 124, 20, 1) 93%); border-image-width: 1 1 0 1;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-center mt-2">
                    <p>Follow me on Social Networks:</p>
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="#" target="_blank"><i class="fab fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="#" target="_blank"><i class="fab fa-github"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-6 text-center mt-2">
                    <p>Get in touch:</p>
                    <p>Email: <a href="mailto:diogo.reis@ipvc.pt" class="email">diogo.reis@ipvc.pt</a></p>
                    <p>Telephone: (123) 456-7890</p>
                </div>
                <div class="col-md-12 text-center mt-3">
                    <p>&copy; 2023 Diogo Reis. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

