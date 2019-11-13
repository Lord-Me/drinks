<?php
//TODO finish the profile page.
//TODO create login models to manage everything there

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)??"index";
class ExceptionPageNotFound extends Exception{};

/*
 * REQUIRES
 */
require("src/RecipesControl.php");
require("src/DBConnect.php");
require("src/DrinkModel.php");
require("src/UserModel.php");
require("src/Drink.php");
require("src/User.php");

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
     * LOGIN
     */
    case "login":
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
        if (isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=index');
            exit();
        }

        class ExceptionEmptyForm extends Exception{};
        class ExceptionUsernameExists extends Exception{};
        class ExceptionInvalidInput extends Exception{};

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

                $newUser = new User();
                $newUser->setUsername($_POST['username']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPassword($_POST['password']);

                // Check input validity
                //TODO make getformdata and validate in models. Example in databaseBlog/postmodel
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new ExceptionInvalidInput('Email is not valid!');
                }
                if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
                    throw new ExceptionInvalidInput('Username is not valid!');
                }
                if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 6) {
                    throw new ExceptionInvalidInput('Password must be between 6 and 20 characters long!');
                }

                // We need to check if the account with that username exists.
                try {
                    $user = $um->getUserByName($_POST['username']);
                    // Username already exists as it didn't throw an empty error
                    throw new ExceptionUsernameExists();
                }
                catch(PDOException $e){//TODO ODD WAT TO DO THIS
                    // Username doesnt exists, insert new account
                    if ($um->insert($newUser)) {
                        header('Location: index.php?page=successfulRegister');
                    } else {
                        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                        throw new PDOException('Could not prepare statement!');
                    }
                }
            }
            catch(ExceptionEmptyForm $e){
                die('Please complete the registration form!');
            }
            catch(ExceptionInvalidInput $e){
                die($e->getMessage());
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
        if (isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=index');
            exit();
        }

        require("views/successfulRegister.view.php");
        break;

    /*
     * CHANGE PASSWORD
     */
    case "changepassword":
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: index.php?page=login');
            exit();
        }


        require("views/profile.view.php");
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
     * DEFAULT 404
     */
    default:
        {
          require("views/error.view.php");
        };

}
