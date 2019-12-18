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
    private function getDefaultTwigProperties():array{
        if (isset($_SESSION['loggedin'])) {
            $loggedIn = ["loggedIn" => true];
            if($_SESSION['role']==1) {
                $isAdmin = [
                    "isAdmin" => true,
                    "sessionId" => $_SESSION['id']
                ];
            }else{
                $isAdmin = ["isAdmin" => false];
            }
            $username = ["username" => $_SESSION['name']];
        }else {
            $loggedIn = ["loggedIn" => false];
            $isAdmin = ["isAdmin" => false];
            $username = ["username" => null];
        }
        $properties=[];
        array_merge($properties, $isAdmin, $loggedIn, $username);
        return $properties;
    }

    /*
     * INDEX
     */
    public function index()
    {
        $dm = new DrinkModel($this->db);
        $drinks = $dm->getAll();

        $properties = [];
        array_merge($this->getDefaultTwigProperties(), $properties);
        return $this->render('index.twig', $properties);
    }

    /*
     * PAGE NOT FOUND
     */
    public function pageNotFound(){
        $properties = [];
        array_merge($this->getDefaultTwigProperties(), $properties);
        return $this->render('404.twig', $properties);
    }

    /*
     * SHOW SINGLE POST
     */
    public function showById($id)
    {
        try {
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

            $properties= [
                "drink" => $view,
            ];
            array_merge($this->getDefaultTwigProperties(), $properties);
            return $this->render('drink.twig', $properties);

        } catch (ExceptionPageNotFound $e) {
            header("Location: /drinks/pageNotFound");
        }
    }

    /*
     * SHOW ALL DRINKS
     */
    public function showDrinks($currentPagi){
        try {
            //Create a new object to contain all the drinks
            $rc = new RecipesControl();

            //Using the Model for our drinks, I get all of the drinks with the same category
            $dm = new DrinkModel($this->db);
            $um = new UserModel($this->db);

            /*
             * FILTERS
             */
            $filter = new Filter(null);

            //Check which filters are in use
            $author_id = $filter->checkAuthorId();
            $filter->removeDeleted();

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

            //Send each array entry to be stored
            foreach ($drinks as $drink) {
                $drink->setAuthor_name($um->getUserById($drink->getAuthor_id())->getUsername());
                $rc->add($drink);
            }
            $pages = $rc->createPages(6);

            if($currentPagi < 1 || !isset($currentPagi)){
                $currentPagi = 1;
            }
            if($currentPagi > count($pages)){
                $currentPagi = count($pages);
            }

            //Get the query array and turn it into string
            $str = $_SERVER['QUERY_STRING'];
            parse_str($str, $queryArray);
            $keys = array_keys($queryArray);
            $i = 0;
            $queryString = "";
            foreach ($queryArray as $item){
                $queryString .= $keys[$i] . "=" . $item . "&";
                $i++;
            }
            $queryString = substr($queryString, 0, -1);
            $queryString = "?".$queryString;

            $properties= [
                "pages" => $pages,
                "currentPagi" => $currentPagi,
                "author_id" => $author_id,
                "queryString" => $queryString
            ];
            array_merge($this->getDefaultTwigProperties(), $properties);
            return $this->render('drinks.twig', $properties);
            //require ("views/drinks.view.php");

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
     * MY DRINKS
     */
    public function myDrinks($currentPagi){
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