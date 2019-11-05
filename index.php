<?php
$page = $_GET['page']??"index";

switch ($page) {
    case "index":
        require("views/$page.view.php");
        break;


    case "posts":
        require("views/$page.view.php");
        //
        break;

    case "drinks":
        require("views/$page.view.php");
        //.
        break;

    case "professionalCocktails":
        require("views/$page.view.php");
        require("src/RecipesControl.php");
        require("src/DBConnect.php");

        try {
            $recipeList = new RecipesControl();

            $stmt = new DBConnect();
            $stmt->connect();
            $stmt->getByCategory(1,$pdo);

            foreach ($stmt as $drink) {
                print_r($drink);
                //$input = new Post($row["id"], $row["author_id"], $row["title"], $row["slug"], $row["summary"], $row["content"], $row["published_at"]);
                //$recipeList->add($input);
            }

            //echo $recipeList ->render();

            #Per alliberar els recursos utilitzats en la consulta SELECT
            $stmt = null;
        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }

        break;

    case "originalCocktails":
        require("views/$page.view.php");
        //.
        break;

    case "login":
        require("views/$page.view.php");
        ///
        break;

    case "register":
        require("views/$page.view.php");
        ////
        break;

    default:
        {
          require("views/error.view.php");
        };

}
