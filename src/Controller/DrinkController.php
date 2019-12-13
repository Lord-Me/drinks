<?php


namespace App\Controller;

use App\Entity\Drink;
use App\DBConnect;
use PDOException;
use App\RecipesControl;
use App\Model\DrinkModel;
use App\Model\UserModel;
use App\Entity\Filter;
use App\Core\Router;
use App\Core\Request;
use App\Exceptions\ExceptionPageNotFound;
use App\Exceptions\ExceptionInvalidData;
use App\Exceptions\ExceptionInvalidInput;
use App\Exceptions\ExceptionUsernameExists;

class DrinkController extends AbstractController
{
    /*
     * INDEX
     */
    public function index()
    {
        $dm = new DrinkModel($this->db);
        $drinks = $dm->getAll();
        require('views/index.view.php');
        return "";
    }

    /*
     * PAGE NOT FOUND
     */
    public function pageNotFound(){
        require("views/error.view.php");
    }

    /*
     * SHOW SINGLE POST
     */
    public function showById($id)
    {
        try {
            session_start();
            //Connect to the database
            $connection = new DBConnect();

            $pdo = $connection->getConnection();

            //create new drink via the Model
            $dm = new DrinkModel($pdo);

            if ($id == NULL) {
                throw new ExceptionPageNotFound();
            }

            //fetch the drink with the corresponding id
            $recipe = $dm->getById($id);


            //send it to the view
            $view = $recipe->renderPage();
            require("views/drink.view.php");

        } catch (ExceptionPageNotFound $e) {
            header("Location: /drinks/pageNotFound");
        }
    }

    /*
     * SHOW ALL DRINKS
     */
    public function showDrinks($currentPagi){
        try {
            session_start();
            //Create a new object to contain all the drinks
            $recipeList = new RecipesControl();

            //Connect to the database

            $connection = new DBConnect();
            $pdo = $connection->getConnection();

            //Using the Model for our drinks, I get all of the drinks with the same category
            $dm = new DrinkModel($pdo);

            /*
             * FILTERS
             */
            $filter = new Filter(null);

            //Check which filters are in use
            $author_id = $filter->checkAuthorId();

            if (isset($_GET["filterFormSubmit"]) && $_SERVER['REQUEST_METHOD'] == 'GET') {
                $filter->checkFilterRadio();
                $filter->checkSearchValue();
                $filter->checkDate();
            } else {
                $filter->setAll();  //if no filters are in use, set filter to all
            }

            //Run filters
            $filter->runFilter($dm);
            $filter->runSort();

            //retrieve the filtered drinks
            $drinks = $filter->getDrinks();
            /*
             * /FILTERS
             */

            //Send each array entry to the object recipeList to be stored
            foreach ($drinks as $drink) {
                $recipeList->add($drink);
            }

            //get the url and make a query string and remove pagi
            /*$str = $_SERVER['QUERY_STRING'];
            parse_str($str, $queryArray);

            if(!array_key_exists("pagi", $queryArray)){
                $addPagi = ["pagi" => 1];
                $queryArray = array_merge($queryArray, $addPagi);
            }*/
            //Get the current pagination page number
            /*if (!is_numeric($queryArray["pagi"]) || $queryArray["pagi"] < 1 || $queryArray["pagi"] == NULL) {
                $currentPagi=1;
            }else {
                $currentPagi = $queryArray["pagi"];
            }*/
            if($currentPagi < 1 || !isset($currentPagi)){
                $currentPagi = 1;
            }

            //Get the query string for the filter
            $str = $_SERVER['QUERY_STRING'];
            parse_str($str, $queryArray);

            //Turn each array entry into an array of all the pages(pagination) with html sting. FilterLocation is the page the filter form is on
            $pages = $recipeList->render($currentPagi, 5, "drinks", $queryArray);

            //Check if currentPagi is over the number of pages. If so, set is as last page
            if ($currentPagi > count($pages)) {
                $currentPagi = count($pages);
            }
            $thisPage = $pages[$currentPagi-1];

            $view = implode("", $thisPage);
            require('views/drinks.view.php');

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }
    }

    /*
     * LOGIN
     */
    public function login(){
        $errorText = [];//HAS ERROR DISPLAY
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=index');
            exit();
        }

        /*
         * AUTHENTTICATE LOGIN
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $um = new UserModel($pdo);

            // Now we check if the data from the login form was submitted, isset() will check if the data exists.
            if (!isset($_POST['username'], $_POST['password'])) {
                // Could not get the data that should have been sent.
                array_push($errorText, 'Please fill both the username and password field!');
            }

            try {
                $user = $um->getUserByName($_POST['username']);

                if (!empty($user)) {
                    // Account exists, now we verify the password.
                    // Note: remember to use password_hash in your registration file to store the hashed passwords.
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        // Verification success! User has loggedin!
                        // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                        session_start();
                        session_regenerate_id();
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['name'] = $_POST['username'];
                        $_SESSION['id'] = $user->getId();
                        $_SESSION['role'] = $user->getRole();
                        header('Location: index.php?page=index');
                    } else {
                        array_push($errorText, 'Incorrect password!');
                    }
                } else {
                    array_push($errorText, 'Incorrect username!');
                }
            }catch (PDOException $e){
                array_push($errorText, 'Incorrect username!');
            }
        }

        require("views/login.view.php");
    }

    /*
     * CREATE NEW POST
     */
    /*
    public function create()
    {
        /* insert té dos comportaments, quan s'ha enviat el formulari i quan no
           la variable submitted controlarà el comportament *//*
        $formSubmitted = false;
        $headerText = "Nova entrada";

        if ($this->request->isPost()) {
            //2. Tractar el formulari
            $formSubmitted = true;
            $dm = new DrinkModel($this->db);

            // Obté les dades del formulari
            $drink = $dm->getInsertFormData();

            //3. Valida l'objecte Drink
            $errors = $dm->validate($drink);

            //4. Executa el INSERT
            if (count($errors) == 0) {
                $result = $dm->insert($drink);
                $message = "El Drink s'ha insertat correctament";
            } else {
                $message = "El Drink NO s'ha insertat";
            }
            require("views/insert.view.php");
        } else {
            //1. Mostrar el formulari
            $drink = new Drink();
            require("views/insert.view.php");
        }
    }
    */
    /*
     * EDIT POST
     */
    /*
    public function update($id)
    {
        /* insert té dos comportaments, quan s'ha enviat el formulari i quan no
        la variable submitted controlarà el comportament *//*
        $formSubmitted = false;
        $headerText = "Editar entrada";
        $dm = new DrinkModel($this->db);

        if ($this->request->isPost()) {
            //2. Tractar el formulari
            $formSubmitted = true;

            // Obté les dades del formulari
            $drink = $dm->getUpdateFormData();
            $drink->setId($id);

            //3. Valida l'objecte Drink
            $errors = $dm->validate($drink);

            //4. Executa el UPDATE
            if (count($errors) == 0) {
                $result = $dm->update($drink);
                $message = "El Drink s'ha actualitzat correctament";
            } else {
                $message = "El Drink NO s'ha actualitzat correctament";
            }
            require("views/insert.view.php");
        } else {
            //1. Mostrar el formulari
            // Carreguem l'objecte $drink
            $drink = $dm->getById($id);

            require("views/insert.view.php");
        }
    }
    */

    /*
     * MY DRINKS
     */
    public function myDrinks($currentPagi){
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }

        try {
            //Create a new object to contain all the drinks
            $recipeList = new RecipesControl();

            //Connect to the database

            $connection = new DBConnect();
            $pdo = $connection->getConnection();

            //Using the Model for our drinks, I get all of the drinks with the same category
            $dm = new DrinkModel($pdo);

            /*
             * FILTER
             */
            $filter = new Filter($_SESSION['id']);

            //Check which filters are in use
            if (isset($_GET["filterFormSubmit"]) && $_SERVER['REQUEST_METHOD'] == 'GET') {
                $filter->checkFilterRadio();
                $filter->checkTitleSearchValue();
                $filter->checkDate();
            } else {
                $filter->setAll();  //if no filters are in use, set filter to all
            }

            //Run filters
            $filter->runFilter($dm);
            $filter->runSort();
            $drinks = $filter->getDrinks();

            foreach ($drinks as $drink) {
                $recipeList->add($drink);
            }

            if($currentPagi < 1 || !isset($currentPagi)){
                $currentPagi = 1;
            }

            //Get the query string for the filter
            $str = $_SERVER['QUERY_STRING'];
            parse_str($str, $queryArray);

            //Turn each array entry into an array of all the pages(pagination) with html sting. FilterLocation is the page the filter form is on
            $pages = $recipeList->render($currentPagi, 5, "myDrinks", $queryArray);

            //Check if currentPagi is over the number of pages. If so, set is as last page
            if ($currentPagi > count($pages)) {
                $currentPagi = count($pages);
            }
            $thisPage = $pages[$currentPagi-1];

            $view = implode("", $thisPage);
            require("views/myDrinks.view.php");

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }
    }

    /*
     * NEW DRINK
     */
    public function newDrink(){
        $errorText = [];
        $successText = [];
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }

        $connection = new DBConnect();
        $pdo = $connection->getConnection();

        $um = new UserModel($pdo);
        $dm = new DrinkModel($pdo);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            /*
             * CHECK author_id validity
             */
            try {
                $newDrink = $dm->getInsertFormData();
                $errors = $dm->validate($newDrink);
                $imageErrors = $dm->validateImage($newDrink);
                foreach ($imageErrors as $error) {
                    array_push($errors, $error);
                }
                if (empty($errors)) {
                    if ($dm->insert($newDrink)) {
                        array_push($successText,"Created new post successfully<br>");
                    } else {
                        array_push($errorText,"Failed to create new post");
                    }
                    if ($dm->uploadImage($newDrink)) {
                        array_push($successText,"Uploaded image successfully<br>");
                    } else {
                        array_push($errorText,"Failed to upload image<br>");
                    }

                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }

            } catch (ExceptionInvalidData $e) {
                array_push($errorText, $e->getMessage());
            }
        }

        $user = $um->getUserById($_SESSION["id"]);
        require("views/newDrink.view.php");
    }

    /*
     * EDIT DRINK
     */
    public function editDrink($id){
        $errorText = [];
        $successText = [];
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }

        $connection = new DBConnect();
        $pdo = $connection->getConnection();

        $um = new UserModel($pdo);
        $dm = new DrinkModel($pdo);


        if ($id < 0) {
            header("Location: /drinks"); //404
        }

        //Get all drink IDs and test the given post ID to see if it exists
        $allDrinks = $dm->getAll();
        $allIds =[];
        foreach ($allDrinks as $drink){
            array_push($allIds, $drink->getId());
        }
        if (!in_array($id, $allIds)) {
            header("Location: /drinks/pageNotFound"); //404
        }

        $drink = $dm->getById($id);
        //CHECK IF USER OWNS THIS POST OR IS ADMIN. IF NOT, KICK THEM
        if($_SESSION['id'] != $drink->getAuthor_id() && $_SESSION['id'] != 1){
            header('Location: /drinks');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $newDrink = $dm->getUpdateFormData($drink);
                $errors = $dm->validate($newDrink);
                $imageErrors = $dm->validateImage($newDrink);
                foreach ($imageErrors as $error) {
                    array_push($errors, $error);
                }
                if (empty($errors)) {
                    if ($dm->update($newDrink)) {
                        array_push($successText,"Updated post successfully<br>");
                    } else {
                        array_push($errorText,"Failed to update post<br>");
                    }
                    if ($dm->uploadImage($newDrink)) {
                        array_push($successText,"Uploaded new image successfully<br>");
                    }
                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }
            }
            catch (ExceptionInvalidData $e) {
                array_push($errorText,$e->getMessage());
            }
            catch (PDOException $e) {
                array_push($errorText,'Error: ' . $e->getMessage());
            }
        }


        try {
            $drink = $dm->getById($id);
        }catch (ExceptionPageNotFound $e) {
            array_push($errorText,'Error: ' . $e->getMessage());
        }

        $user = $um->getUserById($_SESSION["id"]);
        require("views/editDrink.view.php");
    }

    /*
     * DELETE DRINK
     */
    public function toggleDeleteDrink($id){
        try {
            // We need to use sessions, so you should always start sessions using the below code.
            session_start();
            // If the user is not logged in redirect to the login page...
            if (!isset($_SESSION['loggedin'])) {
                header('Location: /drinks/login');
                exit();
            }


            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $dm = new DrinkModel($pdo);


            if ($id < 0) {
                header("Location: /drinks/pageNotFound"); //404
            }

            //Get all drink IDs and test the given post ID to see if it exists
            $allDrinks = $dm->getAll();
            $allIds =[];
            foreach ($allDrinks as $drink){
                array_push($allIds, $drink->getId());
            }
            if (!in_array($id, $allIds)) {
                header("Location: /drinks/user/myDrinks");
                exit();
            }

            $drink = $dm->getById($id);
            //CHECK IF USER OWNS THIS POST OR IS ADMIN. IF NOT, KICK THEM
            if($_SESSION['id'] != $drink->getAuthor_id() && $_SESSION['id'] != 1){
                header('Location: /drinks');
                exit();
            }

            //If all's okay, set as deleted
            if($drink->getView() == 1) {
                $dm->markAsDeleted($id);
            }else{
                $dm->markAsUndeleted($id);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}