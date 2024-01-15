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
