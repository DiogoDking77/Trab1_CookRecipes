<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/Landing_Page.css">
    <script src="JavaScript/Landing_Page.js"></script>   
    <title>Gestão de Receitas Culinárias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English&family=Pixelify+Sans&family=Raleway:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(103deg, rgba(91, 91, 91, 1) 0%, rgba(59, 59, 59, 1) 98%); border-bottom: 5px solid transparent; border-image-slice: 1; border-image-source: linear-gradient(90deg, rgba(156, 105, 14, 1) 0%, rgba(180, 124, 20, 1) 93%); border-image-width: 1 1 5px 1;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <!-- Place your logo here -->
                <img src="Images/logo2.png" alt="Logo" height="50" style="max-height: 60px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="PHP/Pages/dashboard.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Recipes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="PHP/Pages/yourRecipes.php">Your Recipes</a></li>
                            <li><a class="dropdown-item" href="PHP/Pages/FavoritedRecipes.php">Favorite Recipes</a></li>
                            <li><a class="dropdown-item" href="PHP/Pages/SharedRecipes.php">Shared Recipes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="PHP/Pages/createRecipe.php">Create Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Categories</a>
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
        <div class="principal">
            <!-- Conteúdo da página vai aqui -->
            <div class="card1">
                <div class="card-content">
                    <div class="logo-container">
                        <img src="Images/Logo.png" alt="Logo da empresa" class="logo2">
                    </div>
                    <h3><div class="slogan">For flavors that nook and crook!</div></h3>
                    <p>Love cooking but hate the organization part? <br> Let Him Cook is the recipe management tool that will transform your kitchen into a space of inspiration and fun.</p>
                    <a href="#" class="btn">See Recipes</a>
                </div>
            </div>
        </div>

        <h2 class="divider line double-razor">Login and Register</h2>
        <div class="login-register-card-total container-fluid">
            <div class="row">
                <div class="col-12 login-register-card-text">
                    Log in or register on our website and discover the world of cuisine
                </div> 
                <div class="col-6">
                    <button class="login-register-btn" ><a class="nav-link text-white" href="PHP/Pages/login.php">Login</a></button>
                </div>
                <div class="col-6">
                    <button class="login-register-btn"><a class="nav-link text-white" href="PHP/Pages/register.php">Register</a></button>
                </div>
            </div>
        </div>


        
        <h2 class="divider line double-razor">Learn</h2>
        <div class="learn-total">
            <div class="learn-context">Explore delicious recipes on our website! Whether you are a beginner or an experienced chef, we offer step-by-step tutorials to improve your cooking skills. Discover unique dishes, master ingredients and transform your kitchen experience. Join us on this delicious learning journey!</div>

            <div class="container">
                <div class="row">
                    <div class="col-md-12 recipe-card-principal">
                        <div class="card-total">
                            <div class="col-md-6 image-section">
                                <img src="Images/Chocolate_IceCream.jpg" alt="Chocolate Ice Cream">
                            </div>
                            <div class="col-md-6 content-section">
                                <h3>Chocolate Ice Cream</h3>
                                <p>A delicious and creamy chocolate ice cream that you can easily make at home. Perfect for satisfying your sweet tooth!</p>
                                <h4>Ingredients:</h4>
                                <ul>
                                    <li>2 cups heavy cream</li>
                                    <li>1 cup whole milk</li>
                                    <li>3/4 cup granulated sugar</li>
                                    <li>1/2 cup unsweetened cocoa powder</li>
                                    <li>1 teaspoon vanilla extract</li>
                                    <li>1/2 cup chocolate chips (optional)</li>
                                </ul>
                            <div class="col-lg-12">
                                <h4>Instructions:</h4>
                                <ol>
                                    <li>In a mixing bowl, whisk together the heavy cream, whole milk, sugar, and cocoa powder until well combined.</li>
                                    <li>Add the vanilla extract and continue to whisk until the mixture is smooth.</li>
                                    <li>If desired, fold in chocolate chips for extra richness.</li>
                                    <li>Pour the mixture into an ice cream maker and churn according to the manufacturer's instructions.</li>
                                    <li>Once churned, transfer the ice cream to a lidded container and freeze for a few hours to firm up.</li>
                                    <li>Scoop and enjoy your homemade chocolate ice cream!</li>
                                </ol>
                            </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="divider line double-razor">Create and Share</h2>

        <div class="container">
            <div class="row create-share-total">
                <div class="col-md-6 create-share-image">
                    <img src="Images/Cria_Partilha.png" alt="Cria_Partilha">
                </div>
                <div class="col-md-6 create-share-context">
                    Explore the flavor of sharing! Our recipe management platform makes it easy to create and share your culinary delights. Save your favorite recipes, discover new creations, and connect with friends through the flavors you love. Join us and turn cooking into a social experience. Create, share, enjoy - it's that simple!
                </div>
            </div>
        </div>
        
        <h2 class="divider line double-razor">Discover</h2>
        
        <div class="discover-context">
            Explore a variety of flavors in our community! Browse amazing recipes shared by enthusiasts like you. Find inspiration, experiment and discover new culinary favorites. Join us on this delightful journey!
        </div>
        
        <div class="horizontal-list">
            <ul id="my-list">
                <li>
                    <div class="recipe-card">
                        <img src="Images/Francesinha.jpg" alt="Recipe Image 1">
                        <h3>Francesinha</h3>
                        <p>Francesinha is a Portuguese sandwich that originated in Porto. It typically consists of cured ham, linguiça (smoke-cured sausage), and steak sandwiched between layers of bread. The sandwich is then covered with melted cheese and a spicy tomato and beer sauce.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Macarrao_Tomate_Manjericao.jpg" alt="Recipe Image 1">
                        <h3>Pasta with tomato and basil sauce</h3>
                        <p>Pasta with tomato and basil is a classic Italian dish known for its simplicity. It typically features pasta tossed with a sauce made from fresh tomatoes, garlic, olive oil, and basil.</p>
                    </div>
                </li>
                <li>
                    <div class="recipe-card">
                        <img src="Images/Cheesecake.jpg" alt="Recipe Image 1">
                        <h3>Cheesecake</h3>
                        <p>Cheesecake is a rich and creamy dessert made with a crust, usually consisting of crushed cookies or graham crackers, and a filling primarily composed of cream cheese, sugar, and eggs. It can be baked or refrigerated, and various toppings like fruit compote or chocolate are often added.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Chicken-Sandwich.jpg" alt="Recipe Image 1">
                        <h3>Healthy Chicken Sandwich</h3>
                        <p>A healthy chicken sandwich is a nutritious option made with grilled or roasted chicken breast, whole-grain bread, and a variety of fresh vegetables. It's a lighter alternative to traditional fried chicken sandwiches.</p>
                    </div>
                </li>
                <li>
                    <div class="recipe-card">
                        <img src="Images/Pumpkin_Carrot_Soup.jpg" alt="Recipe Image 1">
                        <h3>Carrot and Pumpkin Soup</h3>
                        <p>Carrot and pumpkin soup is a comforting and nutritious soup made with pureed carrots, pumpkin, broth, and seasonings. It often includes ingredients like onions, garlic, and ginger for added flavor.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Chocolate_IceCream.jpg" alt="Recipe Image 1">
                        <h3>Chocolate Ice Cream</h3>
                        <p>A delicious and creamy chocolate ice cream that you can easily make at home. Perfect for satisfying your sweet tooth!</p>
                    </div>
                </li>
                <li>
                    <div class="recipe-card">
                        <img src="Images/Panqueca.jpg" alt="Recipe Image 1">
                        <h3>Banana and Oat Pancake</h3>
                        <p>This pancake is a great option for a healthy and nutritious breakfast or snack. It is made with banana, oats, eggs and milk, and is a great source of fiber, protein and vitamins.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Egg_Fried_Rice.jpg" alt="Recipe Image 1">
                        <h3>Egg Fried Rice</h3>
                        <p>Egg fried rice is a popular Asian dish made by stir-frying pre-cooked rice with scrambled eggs, vegetables (such as peas, carrots, and corn), and soy sauce. It's a flavorful and satisfying dish commonly found in Chinese cuisine.</p>
                    </div>
                </li>
                <li>
                    <div class="recipe-card">
                        <img src="Images/Fish_n_Chips.jpg" alt="Recipe Image 1">
                        <h3>Fish 'n' Chips</h3>
                        <p>Fish 'n' chips is a British dish featuring battered and deep-fried fish (usually cod or haddock) served with thick-cut, fried potatoes. It's often accompanied by tartar sauce and malt vinegar.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Mexican_Taco.jpg" alt="Recipe Image 1">
                        <h3>Mexican Taco</h3>
                        <p>A Mexican taco is a traditional dish made with a folded or rolled corn or wheat tortilla filled with various ingredients. Common taco fillings include seasoned meats (such as beef or pork), salsa, guacamole, lettuce, and cheese.</p>
                    </div>
                </li>
                <li>
                    <div class="recipe-card">
                        <img src="Images/apple_pie.jpg" alt="Recipe Image 1">
                        <h3>Apple Pie</h3>
                        <p>Apple pie is a classic American dessert made with a buttery pie crust filled with sliced or diced apples, sugar, and cinnamon. The pie is often baked until golden brown and served warm, sometimes with a scoop of vanilla ice cream.</p>
                    </div>
                    <div class="recipe-card">
                        <img src="Images/Mac_n_Cheese.jpg" alt="Recipe Image 1">
                        <h3>Mac and Cheese</h3>
                        <p>Mac and cheese is a comfort food made with cooked macaroni pasta and a creamy cheese sauce. The cheese sauce is typically made with cheddar cheese and may include ingredients like butter, milk, and flour.</p>
                    </div>
                </li>
            </ul>
          </div>
        
        <h2 class="divider line double-razor">Celebrate</h2>

        <div class="container">
            <div class="row celebrate-total">
                <div class="col-md-6 celebrate-context">
                    Celebrate every occasion with extraordinary flavors! Discover the joy of amazing holidays with recipes from our community, carefully organized into different categories. Be a master in the art of entertaining, choosing from festive dishes, savory appetizers, and irresistible desserts. From the most intimate celebrations to large events, we have the perfect touch for every party. Make your next occasion a memorable event with recipes that delight and surprise. Explore, cook, and celebrate in style!
                </div>
                <div class="col-md-6 celebrate-image">
                    <img src="Images/celebrate.png" alt="Cria_Partilha">
                </div>
            </div>
        </div>
        
        
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

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
