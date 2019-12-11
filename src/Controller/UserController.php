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

class UserController extends AbstractController
{
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
                        header('Location: /drinks');
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
     * LOGOUT
     */

    public function logout(){
        session_start();
        session_destroy();
        // Redirect to the login page:
        header('Location: /drinks');
    }

    /*
     * REGISTER
     */

    public function register(){
        $errorText = []; //Contains error messages
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: /drinks');
            exit();
        }

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
                            header('Location: /drinks/user/successfulRegister');
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
    }

    /*
     * SUCCESSFUL REGISTER
     */
    public function successfulRegister(){
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: /drinks');
            exit();
        }

        require("views/successfulRegister.view.php");
    }

}