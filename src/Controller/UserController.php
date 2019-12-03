<?php


namespace App\Controller;

use App\Entity\Drink;
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

class UserControllerController extends AbstractController
{

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

}