<?php
/*
 * TODO make all pages 1005 height so footer is always at the bottom
 * TODO Hover change image in profile page
 * TODO Add secondary recipes and do delete checking with them
 */
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
            $authorUrl = $filter->checkAuthorId();
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
            $pages = $recipeList->render($currentPagi, 5, "drinks", $queryArray);

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
     * DRINK
     */
    case "drink":
        try {
            session_start();
            //Connect to the database
            $connection = new DBConnect();

            $pdo = $connection->getConnection();

            //create new drink via the Model
            $dm = new DrinkModel($pdo);

            //buscamos el id del segundo query del url. Si no enquentra nada, lanza una exception y va directo a la pagina 404
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) ?? NULL;

            if ($id == NULL) {
                throw new ExceptionPageNotFound();
            }

            //fetch the drink with the corresponding id
            $recipe = $dm->getById($id);


            //send it to the view
            $view = $recipe->renderPage();
            require("views/$page.view.php");

        } catch (ExceptionPageNotFound $e) {
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
        $view = "<br><br><br>Coming soon...";
        require("views/$page.view.php");
        break;

    /*
     * LOGIN
     */
    case "login":
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
        $errorText = []; //Contains error messages
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
                $newUser = $um->getInsertFormData();
                $errors = $um->validate($newUser);
                if (empty($errors)) {
                    // We need to check if the account with that username exists.
                    try {
                        $user = $um->getUserByName($_POST['username']);
                        // Username already exists as it didn't throw an empty error
                        throw new ExceptionUsernameExists();
                    } catch (PDOException $e) {
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
            } catch (ExceptionInvalidData $e) {
                $errorText =array_merge($errorText, $e->getMessage());
            } catch (ExceptionUsernameExists $e) {
                array_push($errorText, 'Username exists, please choose another!');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        require("views/register.view.php");

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//TODO turn this into a UserModel function
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
            if ($_FILES["fileToUpload"]["size"] > 50000) {
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
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
                if ($uploadOk == true) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {//TODO change image name
                        //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                        //Delete previous image unless its the defaultAvatar one or used by another user.
                        if($um->getUserById($_SESSION['id'])->getAvatar() != "defaultAvatar.png") {
                            unlink("img/avatars/" . $um->getUserById($_SESSION['id'])->getAvatar());
                        }
                    } else {
                        array_push($errorText, "Sorry, there was an error uploading your file.");
                    }
                } /*else {
                    array_push($errorText, "Sorry, there was an error uploading your file.");
                }*/
            }

            try {
                if ($uploadOk == true) {
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

            } catch (ExceptionInvalidData $e) {
                array_push($errorText, $e->getMessage());
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $drink = $dm->getById($id);

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
                        array_push($successText,"Uploaded image successfully<br>");
                    } else {
                        array_push($errorText,"Failed to upload image<br>");
                    }
                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }
                echo "<script>alert(\"Updated content successfully\");</script>";
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
                break;
            }
            if($page == "deleteDrink") {
                $dm->markAsDeleted($id);
            }
            if($page == "undeleteDrink") {
                $dm->markAsUndeleted($id);
            }
            header("Location: index.php?page=myDrinks");

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
