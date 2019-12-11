<?php

use App\DBConnect;
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

require __DIR__ . '/config/bootstrap.php';

$di = new \App\Utils\DependencyInjector();

$db = new DBConnect();
$di->set('PDO', $db->getConnection());


$request = new Request();

//var_dump($request);
$route = new Router($di);
$route->route($request);

$action = $_GET['action'] ?? "indexARR";

//echo $_SERVER['REQUEST_URI'];
//echo $_SERVER['QUERY_STRING'];



$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)??"index";
switch ($page) {
    /*
     * INDEX
     */
    case "index":
        break;

    /*
     * DRINKS
     */
    case "drinks":
        break;

    /*
     * DRINK
     */
    case "drink":
        break;

    /*
     * GESTION DE USUARIOS
     */
    case "users":
        session_start();
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if ($_SESSION['role'] != 1) {
            header('Location: index.php?page=index');
            exit();
        }
        $view = "<br><br><br>Coming soon...";
        require("views/$page.view.php");
        break;

    /*
     * LOGIN
     */
    case "login":
        break;

    /*
     * LOGOUT
     */
    case "logout":
        break;

    /*
     * REGISTER
     */
    case "register":
        break;

    /*
     * SUCCESSFUL REGISTER
     */
    case "successfulRegister":
        break;


    /*
     * USER PAGE
     */
    case "user":
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }


        $connection = new DBConnect();
        $pdo = $connection->getConnection();

        $um = new UserModel($pdo);

        try {
            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $um = new UserModel($pdo);

            $userInfo = $um->getUserById($_SESSION['id']);
        } catch (PDOException $err) {
            echo "Error";
        }

        require("views/profile.view.php");
        break;

    /*
     * CHANGE PASSWORD
     */
    case "changePassword":
        $errorText = [];//Error management is used here
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $connection = new DBConnect();
                $pdo = $connection->getConnection();
                $um = new UserModel($pdo);

                $user = $um->getUserByName($_SESSION['name']);

                //See if the password is the correct one
                if (!password_verify($_POST['currentPassword'], $user->getPassword())) {
                    throw new ExceptionInvalidInput('Current password is incorrect');
                }

                if (password_verify($_POST['password'], $user->getPassword())) {
                    throw new ExceptionInvalidInput('You must use a new password');
                }

                $newUser = $um->getUpdateFormData($user);
                $errors = $um->validate($newUser);
                if (empty($errors)) {
                    $userToEdit = $user->getId();
                    if ($um->update($newUser, $userToEdit)) {
                        header("Location: index.php?page=successfulPasswordChange");
                    } else {
                        array_push($errorText, "Failed to update password");
                    }
                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }

            } catch (ExceptionInvalidInput $e) {
                array_push($errorText, $e->getMessage());
            } catch (ExceptionInvalidData $e) {
                array_push($errorText, $e->getMessage());
            }
        }

        require("views/$page.view.php");
        break;

    /*
     * successfulPasswordChange
     */
    case "successfulPasswordChange":
        session_start();
        session_destroy();

        require("views/successfulPasswordChange.view.php");
        break;

    /*
     * Change Avatar
     */
    case "changeAvatar":
        $errorText=[];//error text used here
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $connection = new DBConnect();
        $pdo = $connection->getConnection();
        $um = new UserModel($pdo);

        // Check if image file is a actual image or fake image
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_dir = "img/avatars/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                array_push($errorText, "File is not an image.");
                $uploadOk = false;
            }
            // Check file size is less than 50KB
            if ($_FILES["fileToUpload"]["size"] > 100000) {
                array_push($errorText, "Sorry, your file is too large.");
                $uploadOk = false;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                array_push($errorText, "Sorry, only JPG, JPEG & PNG files are allowed.");
                $uploadOk = false;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == false) {
                //echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                // Check if file already exists and delete it
                foreach (glob("img/".$_SESSION["id"]."*") as $filename) {
                    unlink($filename);
                }
                if ($uploadOk == true) {
                    $path = $_FILES['fileToUpload']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $_SESSION["id"] . "." . $ext)) {
                        //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                    } else {
                        array_push($errorText, "Sorry, there was an error uploading your file.");
                    }
                } /*else {
                    array_push($errorText, "Sorry, there was an error uploading your file.");
                }*/
            }

            if ($uploadOk == true) {
                $user = $um->getUserByName($_SESSION['name']);

                $newUser = $um->getUpdateFormData($user);
                $newUser->setAvatar($user->getId() .".". $ext);

                $userToEdit = $user->getId();
                if ($um->update($newUser, $userToEdit)) {
                    header("Location: index.php?page=user");
                } else {
                    array_push($errorText, "Failed to update avatar");
                }
            }
        }

        require("views/$page.view.php");
        break;

    /*
     * My Drinks
     */
    case "myDrinks":
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
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

            //get the url and make a query string and remove pagi
            $str = $_SERVER['QUERY_STRING'];
            parse_str($str, $queryArray);

            if(!array_key_exists("pagi", $queryArray)){
                $addPagi = ["pagi" => 1];
                $queryArray = array_merge($queryArray, $addPagi);
            }
            //Get the current pagination page number
            if (!is_numeric($queryArray["pagi"]) || $queryArray["pagi"] < 1 || $queryArray["pagi"] == NULL) {
                $currentPagi=1;
            }else {
                $currentPagi = $queryArray["pagi"];
            }

            //Turn each array entry into an array of all the pages(pagination) with html sting. FilterLocation is the page the filter form is on
            $pages = $recipeList->render($currentPagi, 5, "myDrinks", $queryArray);

            //Check if currentPagi is over the number of pages. If so, set is as last page
            if ($queryArray["pagi"] > count($pages)) {
                $currentPagi = count($pages);
            }
            $thisPage = $pages[$currentPagi-1];

            $view = implode("", $thisPage);
            require("views/$page.view.php");

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }

        break;


    /*
     * ADD NEW DRINK
     */


    case "newDrink":
        $errorText = [];
        $successText = [];
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
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
        require("views/$page.view.php");
        break;

    case "editDrink":
        $errorText = [];
        $successText = [];
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $connection = new DBConnect();
        $pdo = $connection->getConnection();

        $um = new UserModel($pdo);
        $dm = new DrinkModel($pdo);

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)??NULL;
        if ($id == NULL) {
            header("Location: index.php?page=default"); //404
        }

        //Get all drink IDs and test the given post ID to see if it exists
        $allDrinks = $dm->getAll();
        $allIds =[];
        foreach ($allDrinks as $drink){
            array_push($allIds, $drink->getId());
        }
        if (!in_array($id, $allIds)) {
            header("Location: index.php?page=default"); //404
        }

        $drink = $dm->getById($id);
        //CHECK IF USER OWNS THIS POST OR IS ADMIN. IF NOT, KICK THEM
        if($_SESSION['id'] != $drink->getAuthor_id() && $_SESSION['id'] != 1){
            header('Location: index.php?page=index');
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
        require("views/$page.view.php");


        break;

    case "deleteDrink": case "undeleteDrink":
        try {
            // We need to use sessions, so you should always start sessions using the below code.
            session_start();
            // If the user is not logged in redirect to the login page...
            if (!isset($_SESSION['loggedin'])) {
                header('Location: index.php?page=login');
                exit();
            }


            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $dm = new DrinkModel($pdo);

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT )??NULL;

            if ($id == NULL) {
                header("Location: index.php?page=default"); //404
            }

            //Get all drink IDs and test the given post ID to see if it exists
            $allDrinks = $dm->getAll();
            $allIds =[];
            foreach ($allDrinks as $drink){
                array_push($allIds, $drink->getId());
            }
            if (!in_array($id, $allIds)) {
                header("Location: index.php?page=myDrinks");
                exit();
            }

            $drink = $dm->getById($id);
            //CHECK IF USER OWNS THIS POST OR IS ADMIN. IF NOT, KICK THEM
            if($_SESSION['id'] != $drink->getAuthor_id() && $_SESSION['id'] != 1){
                header('Location: index.php?page=index');
                exit();
            }

            //If all's okay, set as deleted
            if($page == "deleteDrink") {
                $dm->markAsDeleted($id);
            }
            if($page == "undeleteDrink") {
                $dm->markAsUndeleted($id);
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        break;

    /*
     * DEFAULT 404
     */
    default:
          require("views/error.view.php");
          break;

}
