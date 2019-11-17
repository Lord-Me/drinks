<?php

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)??"index";
class ExceptionPageNotFound extends Exception{};
class ExceptionInvalidData extends Exception{};
class ExceptionInvalidInput extends Exception{};

/*
 * REQUIRES
 */
require("src/RecipesControl.php");
require("src/DBConnect.php");
require("src/DrinkModel.php");
require("src/UserModel.php");
require("src/Drink.php");
require("src/User.php");
require("src/Filter.php");

switch ($page) {
    /*
     * INDEX
     */
    case "index":
        require("views/$page.view.php");
        break;

    /*
     * DRINKS
     */
    case "drinks":
        try {
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
                $authorUrl = $filter->checkAuthorId();
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $filter->checkFilterRadio();
                    $filter->checkSearchValue();
                    $filter->checkDate();
                }else{
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

            //Get the current pagination page number for the back and forward buttons
            $currentPagi = filter_input(INPUT_GET, 'pagi', FILTER_SANITIZE_STRING)??0;
            if(!is_numeric($currentPagi) || $currentPagi<0){
                header("Location: index.php?page=drinks&pagi=0");
            }

            //Turn each array entry into an array of all the pages(pagination) with html sting. FilterLocation is the page the filter form is on
            $pages = $recipeList->render($currentPagi, 5, "drinks");

            //Check if currentPagi is over the number of pages. If so, set is as last page
            if($currentPagi > count($pages)-1){
                $lastPagi = count($pages)-1;
                header("Location: index.php?page=drinks&pagi=".$lastPagi);
            }
            $thisPage = $pages[$currentPagi];

            $view = implode("", $thisPage);
            require("views/$page.view.php");

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }

        break;

    /*
     * DRINK
     */
    case "drink":
        try {
            //Connect to the database
            $connection = new DBConnect();

            $pdo = $connection->getConnection();

            //create new drink via the Model
            $dm = new DrinkModel($pdo);

            //buscamos el id del segundo query del url. Si no enquentra nada, lanza una exception y va directo a la pagina 404
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) ?? NULL;

            if($id==NULL){
                throw new ExceptionPageNotFound();
            }

            //fetch the drink with the corresponding id
            $recipe = $dm->getById($id);


            //send it to the view
            $view=$recipe->renderPage();
            require("views/$page.view.php");

        }catch (ExceptionPageNotFound $e){
            require("views/error.view.php");
        }
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
        $view="<br><br><br>Coming soon...";
        require("views/$page.view.php");
        break;

    /*
     * LOGIN
     */
    case "login":
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
                die ('Please fill both the username and password field!');
            }

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
                    echo 'Incorrect password!';
                }
            } else {
                echo 'Incorrect username!';
            }
        }

        require("views/login.view.html");

        break;

    /*
     * LOGOUT
     */
    case "logout":
        session_start();
        session_destroy();
        // Redirect to the login page:
        header('Location: index.php?page=index');
        break;

    /*
     * REGISTER
     */
    case "register":
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=index');
            exit();
        }

        class ExceptionEmptyForm extends Exception{};
        class ExceptionUsernameExists extends Exception{};

        /*
         * AUTHENTICATE REGISTER
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $um = new UserModel($pdo);

            try {
                //Check filled in
                if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
                    // Could not get the data that should have been sent.
                    throw new ExceptionEmptyForm();
                }
                // Make sure the submitted registration values are not empty.
                if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
                    // One or more values are empty.
                    throw new ExceptionEmptyForm();
                }
                if(strlen($_POST["password"]) > 20 || strlen($_POST["password"]) < 5){
                    throw new ExceptionInvalidInput('Password must be between 5 and 20 characters long!');
                }

                $newUser = $um->getInsertFormData();
                $errors = $um->validate($newUser);
                if (empty($errors)) {
                    // We need to check if the account with that username exists.
                    try {
                        $user = $um->getUserByName($_POST['username']);
                        // Username already exists as it didn't throw an empty error
                        throw new ExceptionUsernameExists();
                    }
                    catch(PDOException $e){
                        // Username doesnt exists, insert new account
                        if ($um->insert($newUser)) {
                            header('Location: index.php?page=successfulRegister');
                        } else {
                            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                            throw new PDOException('Could not prepare statement!');
                        }
                    }
                } else {
                        throw new ExceptionInvalidData(implode("<br>", $errors));
                }
            }
            catch (ExceptionInvalidData $e) {
                die ($e->getMessage());
            }
            catch(ExceptionEmptyForm $e){
                die('Please complete the registration form!');
            }
            catch(ExceptionUsernameExists $e){
                die('Username exists, please choose another!');
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }

        require("views/register.view.html");

        break;

    /*
     * SUCCESSFUL REGISTER
     */
    case "successfulRegister":
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=index');
            exit();
        }

        require("views/successfulRegister.view.php");
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
        }
        catch (PDOException $err) {
            echo "Error";
        }

        require("views/profile.view.php");
        break;

    /*
     * CHANGE PASSWORD
     */
    case "changePassword":
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

                if(strlen($_POST["password"]) > 20 || strlen($_POST["password"]) < 5){
                    throw new ExceptionInvalidInput('Password must be between 5 and 20 characters long!');
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
                        echo("Failed to update password<br>");
                    }
                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }

            } catch (ExceptionInvalidInput $e) {
                die ($e->getMessage());
            } catch (ExceptionInvalidData $e) {
                die ($e->getMessage());
            }
        }

        require("views/$page.view.html");
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
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }


        // Check if image file is a actual image or fake image
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_dir = "img/avatars/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                echo "File is not an image.";
                $uploadOk = false;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = false;
            }
            // Check file size is less than 10KB
            if ($_FILES["fileToUpload"]["size"] > 50000) {
                echo "Sorry, your file is too large.";
                $uploadOk = false;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG & PNG files are allowed.";
                $uploadOk = false;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == false) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            try {
                if($uploadOk == true) {
                    $connection = new DBConnect();
                    $pdo = $connection->getConnection();
                    $um = new UserModel($pdo);

                    $user = $um->getUserByName($_SESSION['name']);


                    $newUser = $um->getUpdateFormData($user);
                    $errors = $um->validate($newUser);
                    if (empty($errors)) {
                        $userToEdit = $user->getId();
                        if ($um->update($newUser, $userToEdit)) {
                            header("Location: index.php?page=user");
                        } else {
                            echo("Failed to update password<br>");
                        }
                    } else {
                        throw new ExceptionInvalidData(implode("<br>", $errors));
                    }
                }

            } catch (ExceptionInvalidInput $e) {
                die ($e->getMessage());
            } catch (ExceptionInvalidData $e) {
                die ($e->getMessage());
            }

        }

        require("views/$page.view.html");
        break;

    /*
     * My Drinks
     */
    case "myDrinks"://TODO fix error which ocures when returning to the myDrinks page from the view drink page when there's a filter on (resubmit form error)
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }

        try{
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $filter->checkFilterRadio();
                $filter->checkTitleSearchValue();
                $filter->checkDate();
            }else{
                $filter->setAll();  //if no filters are in use, set filter to all
            }

            //Run filters
            $filter->runFilter($dm);
            $filter->runSort();
            $drinks = $filter->getDrinks();


            foreach ($drinks as $drink) {
                $recipeList->add($drink);
            }

            //Get the current pagination page number for the back and forward buttons
            $currentPagi = filter_input(INPUT_GET, 'pagi', FILTER_SANITIZE_STRING)??0;
            if(!is_numeric($currentPagi) || $currentPagi<0){
                header("Location: index.php?page=myDrinks&pagi=0");
            }

            //Turn each array entry into an array of all the pages(pagination) with html sting
            $pages = $recipeList->render($currentPagi, 10, "myDrinks");

            //Check if currentPagi is over the number of pages. If so, set is as last page
            if($currentPagi > count($pages)-1){
                $lastPagi = count($pages)-1;
                header("Location: index.php?page=myDrinks&pagi=".$lastPagi);
            }
            $thisPage = $pages[$currentPagi];

            $view = implode("", $thisPage);
            require("views/$page.view.php");

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }

        break;

    /*
     * DEFAULT 404
     */
    default:
        {
          require("views/error.view.php");
        };

}
