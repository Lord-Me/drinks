<?php
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)??"index";
class ExceptionPageNotFound extends Exception{};

switch ($page) {
    case "index":
        require("views/$page.view.php");
        break;

    case "posts":
        require("views/$page.view.php");
        //
        break;

    case "drinks":
        require("src/RecipesControl.php");
        require("src/DBConnect.php");
        require("src/DrinkModel.php");
        require("src/Drink.php");

        try {
            //Create a new object to contain all the drinks
            $recipeList = new RecipesControl();

            //Connect to the database

            $connection = new DBConnect();
            $pdo = $connection->getConnection();

            //Using the Model for our drinks, I get all of the drinks with the same category
            $drink = new DrinkModel($pdo);

            $drinks = $drink->getAll();

            //Get all the drinks in the selected category
            //$drinks = $drink->getByCategory($type_id);

            //Send each array entry to the object recipeList to be stored
            foreach ($drinks as $drink) {
                $recipeList->add($drink);
            }

            //Turn each array entry into a string and send it to the view
            $view=$recipeList->render();
            require("views/$page.view.php");

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }

        break;

    case "drink":
        require("src/RecipesControl.php");
        require("src/DBConnect.php");
        require("src/DrinkModel.php");
        require("src/Drink.php");
        try {
            //Connect to the database
            $connection = new DBConnect();

            $pdo = $connection->getConnection();

            //create new drink via the Model
            $drink = new DrinkModel($pdo);

            //buscamos el id del segundo query del url. Si no enquentra nada, lanza una exception y va directo a la pagina 404
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) ?? NULL;

            if($id==NULL){
                throw new ExceptionPageNotFound();
            }

            //fetch the drink with the corresponding id
            $recipe = $drink->getById($id);


            //send it to the view
            $view=$recipe->renderPage();
            require("views/$page.view.php");

        }catch (ExceptionPageNotFound $e){
            require("views/error.view.php");
        }
        break;

    case "login":
        require("phplogin/index.html");
        break;

    case "register":
        require("phplogin/register.html");
        break;

    case "successfulRegister":
        require("phplogin/successfulRegister.php");
        break;

    case "user":
        require("phplogin/profile.php");
        break;
    default:
        {
          require("views/error.view.php");
        };

}
