<?php

function pdo_connection_mysql() {
    $DATABASE_HOST = "localhost:3308";
    $DATABASE_USER = "root";
    $DATABASE_PASS = "";
    $DATABASE_NAME = "lethimcook";

    try {
        $pdo = new PDO('mysql:host=' . $DATABASE_HOST . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);

        // Configuração para lançar exceções em caso de erro
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica se o banco de dados existe
        $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$DATABASE_NAME'");
        $databaseExists = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o banco de dados não existir, cria-o
        if (!$databaseExists) {
            $pdo->exec("CREATE DATABASE $DATABASE_NAME");
            $pdo->exec("USE $DATABASE_NAME");

            // Criação das tabelas se não existirem
            $pdo->exec("
            CREATE TABLE IF NOT EXISTS Users (
                User_ID INT NOT NULL AUTO_INCREMENT,
                User_Name VARCHAR(50) NOT NULL,
                User_Email VARCHAR(100) NOT NULL,
                User_Password VARCHAR (50) NOT NULL,
                PRIMARY KEY (User_ID)
            );
    
            CREATE TABLE IF NOT EXISTS Recipe (
                Recipe_ID INT NOT NULL AUTO_INCREMENT,
                Recipe_Name VARCHAR(100) NOT NULL,
                Recipe_Description TEXT,
                Recipe_Instructions LONGTEXT NOT NULL,
                Creation_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                User_ID INT NOT NULL,
                PRIMARY KEY (Recipe_ID),
                FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
            );
    
            CREATE TABLE IF NOT EXISTS Category(
                Category_ID INT NOT NULL AUTO_INCREMENT,
                Category_Name VARCHAR(50) NOT NULL,
                PRIMARY KEY (Category_ID)
            );
            
            CREATE TABLE IF NOT EXISTS Hint(
                Hint_ID INT NOT NULL AUTO_INCREMENT,
                Hint VARCHAR(255) NOT NULL,
                Recipes_ID INT NOT NULL,
                FOREIGN KEY (Recipes_ID) REFERENCES Recipe(Recipe_ID),
                PRIMARY KEY (Hint_ID)
            );
            
            CREATE TABLE IF NOT EXISTS Notes(
                Notes_ID INT NOT NULL AUTO_INCREMENT,
                Notes VARCHAR(255) NOT NULL,
                Recipes_ID INT NOT NULL,
                FOREIGN KEY (Recipes_ID) REFERENCES Recipe(Recipe_ID),
                PRIMARY KEY (Notes_ID)
            );
            
            CREATE TABLE IF NOT EXISTS Ingredients(
                Ingredients_ID INT NOT NULL AUTO_INCREMENT,
                Ingredients_Name VARCHAR(50) NOT NULL,
                Ingredients_Quantity VARCHAR(50) NOT NULL,
                Recipes_ID INT NOT NULL,
                FOREIGN KEY (Recipes_ID) REFERENCES Recipe(Recipe_ID),
                PRIMARY KEY (Ingredients_ID)
            );
            
            CREATE TABLE IF NOT EXISTS Photos(
                Photo_ID INT NOT NULL AUTO_INCREMENT,
                Photo LONGTEXT NOT NULL,
                Recipes_ID INT NOT NULL,
                FOREIGN KEY (Recipes_ID) REFERENCES Recipe(Recipe_ID),
                PRIMARY KEY (Photo_ID)
            );
            
            CREATE TABLE IF NOT EXISTS Recipe_Category (
                Recipe_ID INT NOT NULL,
                Category_ID INT NOT NULL,
                PRIMARY KEY (Recipe_ID, Category_ID),
                FOREIGN KEY (Recipe_ID) REFERENCES Recipe(Recipe_ID),
                FOREIGN KEY (Category_ID) REFERENCES Category(Category_ID)
            );
    
            CREATE TABLE IF NOT EXISTS Favorites (
                User_ID INT NOT NULL,
                Recipe_ID INT NOT NULL,
                PRIMARY KEY (User_ID, Recipe_ID),
                FOREIGN KEY (User_ID) REFERENCES Users(User_ID),
                FOREIGN KEY (Recipe_ID) REFERENCES Recipe(Recipe_ID)
            );
    
            CREATE TABLE IF NOT EXISTS Shared_Recipes (
                Sharing_User_ID INT NOT NULL,
                Receiving_User_ID INT NOT NULL,
                Recipe_ID INT NOT NULL,
                PRIMARY KEY (Sharing_User_ID, Receiving_User_ID, Recipe_ID),
                FOREIGN KEY (Sharing_User_ID) REFERENCES Users(User_ID),
                FOREIGN KEY (Receiving_User_ID) REFERENCES Users(User_ID),
                FOREIGN KEY (Recipe_ID) REFERENCES Recipe(Recipe_ID)
            );
            ");

            // Inserção de dados iniciais (usuário, categorias e receita)
            $pdo->exec("
                INSERT INTO Users (User_Name, User_Email, User_Password) VALUES
                ('Diogo Reis', 'diogo.reis@ipvc.pt', '123456');
            ");

            $categories = [
                'Starters',
                'Appetizers',
                'Salads',
                'Soups',
                'Main Dishes',
                'Side Dishes',
                'Desserts',
                'Cakes',
                'Breads',
                'Beverages',
                'Vegetarian',
                'Vegan',
                'Gluten-Free',
                'Lactose-Free',
                'Quick and Easy',
                'Regional Cuisine',
                'International Cuisine',
                'Healthy Eating',
                'Recipes for Kids',
                'Recipes for 4 People',
                'Recipes for 1 Person',
                'Recipes for 2 People',
                'Portuguese Recipe',
                'Mexican Recipe',
                'Italian Recipe',
                'American Recipe',
                'French Recipe',
                'Japanese Recipe',
                'English Recipe',
                'For Christmas',
                'For Summer',
                'Chinese Recipe'
            ];
            

            foreach ($categories as $category) {
                $pdo->exec("
                    INSERT INTO Category (Category_Name) VALUES
                    ('$category');
                ");
            }

            $pdo->exec("
                INSERT INTO Recipe (Recipe_Name, Recipe_Description, Recipe_Instructions, User_ID) VALUES
                ('Francesinha', 'Francesinha is a Portuguese sandwich that originated in Porto. It typically consists of cured ham, linguiça (smoke-cured sausage), and steak sandwiched between layers of bread. The sandwich is then covered with melted cheese and a spicy tomato and beer sauce.', 
                '1. Prepare o molho picante.\n2. Grelhe as carnes.\n3. Monte a sanduíche com queijo e molho.\n4. Sirva e aproveite!', 1);
            ");

            $recipeID = $pdo->lastInsertId();

            $ingredients = [
                ['Linguiça', '200g'],
                ['Salsicha fresca', '200g'],
                ['Bife de carne de vaca', '150g'],
                ['Presunto', '50g'],
                ['Queijo flamengo', '100g'],
                ['Pão de forma', '2 fatias']
            ];

            foreach ($ingredients as list($ingredientName, $ingredientQuantity)) {
                $pdo->exec("
                    INSERT INTO Ingredients (Ingredients_Name, Ingredients_Quantity, Recipes_ID) VALUES
                    ('$ingredientName', '$ingredientQuantity', $recipeID);
                ");
            }

            $pdo->exec("
                INSERT INTO Notes (Notes, Recipes_ID) VALUES
                ('Lembre-se de servir com batatas fritas!', $recipeID);
            ");

            $pdo->exec("
                INSERT INTO Hint (Hint, Recipes_ID) VALUES
                ('Experimente adicionar um ovo frito por cima!', $recipeID);
            ");

            $photoPath = '../Images/Francesinha.jpg';

            // Verifica se o arquivo existe antes de tentar lê-lo
            if (file_exists($photoPath)) {
                // Lê o conteúdo do arquivo e converte para Base64
                $photoBase64 = base64_encode(file_get_contents($photoPath));

                $stmt = $pdo->prepare("
                    INSERT INTO Photos (Photo, Recipes_ID) VALUES
                    (:photoBase64, $recipeID);
                ");

                $stmt->bindParam(':photoBase64', $photoBase64, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                exit('Failed to find the image file.');
            }

            $pdo->exec("
                INSERT INTO Recipe_Category (Recipe_ID, Category_ID) VALUES
                ($recipeID, 5), 
                ($recipeID, 16), 
                ($recipeID, 21),
                ($recipeID, 23)
            ");

            // Assuming $pdo is your PDO connection object
            $pdo->exec("
            INSERT INTO Recipe (Recipe_Name, Recipe_Description, Recipe_Instructions, User_ID) VALUES
            ('Classic Cheesecake', 'Cheesecake is a rich and creamy dessert made with a crust, usually consisting of crushed cookies or graham crackers, and a filling primarily composed of cream cheese, sugar, and eggs. It can be baked or refrigerated, and various toppings like fruit compote or chocolate are often added.', 
            '1. Preheat oven to 325°F (163°C).\n2. Mix graham cracker crumbs with melted butter and press into the bottom of a springform pan.\n3. In a large bowl, beat cream cheese until smooth.\n4. Add sugar, vanilla extract, and eggs; beat until well combined.\n5. Pour the cream cheese mixture over the crust.\n6. Bake for about 50-60 minutes or until the center is set.\n7. Let it cool, then refrigerate for a few hours before serving.', 1);
            ");

            $cheesecakeID = $pdo->lastInsertId();

            // Insert ingredients for Cheesecake
            $cheesecakeIngredients = [
            ['Cream Cheese', '16 oz'],
            ['Sugar', '1 cup'],
            ['Vanilla Extract', '1 tsp'],
            ['Eggs', '4'],
            ['Graham Cracker Crumbs', '1 1/2 cups'],
            ['Butter (melted)', '1/2 cup']
            ];

            foreach ($cheesecakeIngredients as list($ingredientName, $ingredientQuantity)) {
            $pdo->exec("
                INSERT INTO Ingredients (Ingredients_Name, Ingredients_Quantity, Recipes_ID) VALUES
                ('$ingredientName', '$ingredientQuantity', $cheesecakeID);
            ");
            }

            // Insert notes for Cheesecake
            $pdo->exec("
            INSERT INTO Notes (Notes, Recipes_ID) VALUES
            ('You can add a fruit topping like strawberry or cherry for extra flavor!', $cheesecakeID);
            ");

            // Insert hints for Cheesecake
            $pdo->exec("
            INSERT INTO Hint (Hint, Recipes_ID) VALUES
            ('To prevent cracks, avoid overmixing the batter and don\'t overbake the cheesecake.', $cheesecakeID);
            ");

            // Insert a photo for Cheesecake
            $cheesecakePhotoPath = '../Images/Cheesecake.jpg';

            if (file_exists($cheesecakePhotoPath)) {
            $cheesecakePhotoBase64 = base64_encode(file_get_contents($cheesecakePhotoPath));

            $stmt = $pdo->prepare("
                INSERT INTO Photos (Photo, Recipes_ID) VALUES
                (:photoBase64, $cheesecakeID);
            ");

            $stmt->bindParam(':photoBase64', $cheesecakePhotoBase64, PDO::PARAM_STR);
            $stmt->execute();
            } else {
            exit('Failed to find the image file for Cheesecake.');
            }

            // Associate Cheesecake with categories
            $pdo->exec("
            INSERT INTO Recipe_Category (Recipe_ID, Category_ID) VALUES
            ($cheesecakeID, 7), -- Desserts
            ($cheesecakeID, 8), -- Cakes
            ($cheesecakeID, 13) -- Gluten-Free
            ");


            $pdo->exec("
            INSERT INTO Recipe (Recipe_Name, Recipe_Description, Recipe_Instructions, User_ID) VALUES
            ('Egg Fried Rice', 'Simple and delicious egg fried rice.', 
            '1. Cook rice according to package instructions and let it cool.\n2. Heat oil in a wok or large pan over medium heat.\n3. Add diced vegetables and stir-fry until they are slightly tender.\n4. Push the vegetables to the side and pour beaten eggs into the pan.\n5. Scramble the eggs and mix them with the vegetables.\n6. Add cooked rice to the pan and stir to combine.\n7. Season with soy sauce and any desired seasonings.\n8. Garnish with green onions and serve hot!', 1);
            ");

            $eggFriedRiceID = $pdo->lastInsertId();

            // Insert ingredients for Egg Fried Rice
            $eggFriedRiceIngredients = [
            ['Cooked Rice', '3 cups'],
            ['Vegetable Oil', '2 tbsp'],
            ['Mixed Vegetables (e.g., peas, carrots, corn)', '1 cup'],
            ['Eggs (beaten)', '2'],
            ['Soy Sauce', '2 tbsp'],
            ['Green Onions (chopped)', '2 tbsp'],
            ['Salt and Pepper to taste', ''],
            ];

            foreach ($eggFriedRiceIngredients as list($ingredientName, $ingredientQuantity)) {
            $pdo->exec("
                INSERT INTO Ingredients (Ingredients_Name, Ingredients_Quantity, Recipes_ID) VALUES
                ('$ingredientName', '$ingredientQuantity', $eggFriedRiceID);
            ");
            }

            // Insert notes for Egg Fried Rice
            $pdo->exec("
            INSERT INTO Notes (Notes, Recipes_ID) VALUES
            ('You can add protein like diced chicken, shrimp, or tofu for extra flavor.', $eggFriedRiceID);
            ");

            // Insert hints for Egg Fried Rice
            $pdo->exec("
            INSERT INTO Hint (Hint, Recipes_ID) VALUES
            ('Use cold, leftover rice for better texture in fried rice.', $eggFriedRiceID);
            ");

            // Insert a photo for Egg Fried Rice
            $eggFriedRicePhotoPath = '../Images/Egg_Fried_Rice.jpg';

            if (file_exists($eggFriedRicePhotoPath)) {
            $eggFriedRicePhotoBase64 = base64_encode(file_get_contents($eggFriedRicePhotoPath));

            $stmt = $pdo->prepare("
                INSERT INTO Photos (Photo, Recipes_ID) VALUES
                (:photoBase64, $eggFriedRiceID);
            ");

            $stmt->bindParam(':photoBase64', $eggFriedRicePhotoBase64, PDO::PARAM_STR);
            $stmt->execute();
            } else {
            exit('Failed to find the image file for Egg Fried Rice.');
            }

            // Associate Egg Fried Rice with categories
            $pdo->exec("
            INSERT INTO Recipe_Category (Recipe_ID, Category_ID) VALUES
            ($eggFriedRiceID, 5), -- Main Dishes
            ($eggFriedRiceID, 20), -- Quick and Easy
            ($eggFriedRiceID, 28), 
            ($eggFriedRiceID, 32) 
            ");

        } else {
            // Se o banco de dados já existir, apenas o seleciona
            $pdo->exec("USE $DATABASE_NAME");
        }

        return $pdo;
    } catch (PDOException $exception) {
        exit('Failed to connect to database: ' . $exception->getMessage());
    }
}

?>
