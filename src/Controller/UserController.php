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

    public function login()
    {
        $errorText = [];//HAS ERROR DISPLAY
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: /drinks');
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
            } catch (PDOException $e) {
                array_push($errorText, 'Incorrect username!');
            }
        }

        require("views/login.view.php");
    }

    /*
     * LOGOUT
     */

    public function logout()
    {
        session_start();
        session_destroy();
        // Redirect to the login page:
        header('Location: /drinks');
    }

    /*
     * REGISTER
     */

    public function register()
    {
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
                $errorText = array_merge($errorText, $e->getMessage());
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
    public function successfulRegister()
    {
        session_start();
        if (isset($_SESSION['loggedin'])) {
            header('Location: /drinks');
            exit();
        }

        require("views/successfulRegister.view.php");
    }

    /*
     * USER MANAGEMENT
     */
    public function userManagement()
    {
        session_start();
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }
        if ($_SESSION['role'] != 1) {
            header('Location: /drinks/login');
            exit();
        }
        $view = "<br><br><br>Coming soon...";
        require("views/users.view.php");
    }

    /*
     * USER PROFILE
     */
    public function profile()
    {
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

        try {
            $connection = new DBConnect();
            $pdo = $connection->getConnection();
            $um = new UserModel($pdo);

            $userInfo = $um->getUserById($_SESSION['id']);
        } catch (PDOException $err) {
            echo "Error";
        }

        require("views/profile.view.php");
    }

    /*
     * CHANGE PASSWORD
     */
    public function changePassword()
    {
        $errorText = [];//Error management is used here
        // We need to use sessions, so you should always start sessions using the below code.
        session_start();
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
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
                        header("Location: /drinks/user/profile/successfulPasswordChange");
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

        require("views/changePassword.view.php");
    }

    /*
     * SUCCESSFUL PASSWORD CHANGE
     */
    public function successfulPasswordChange()
    {
        session_start();
        session_destroy();

        require("views/successfulPasswordChange.view.php");
    }

    /*
     * CHANGE AVATAR
     */
    public function changeAvatar()
    {
        $errorText = [];//error text used here
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

        // Check if image file is a actual image or fake image
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_dir = __DIR__ . "/../../../drinks/img/avatars/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = true;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = true;
            } else {
                array_push($errorText, "File is not an image.");
                $uploadOk = false;
            }
            // Check file size is less than 200KB
            if ($_FILES["fileToUpload"]["size"] > 200000) {
                array_push($errorText, "Sorry, your file is too large.");
                $uploadOk = false;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                array_push($errorText, "Sorry, only JPG, JPEG & PNG files are allowed.");
                $uploadOk = false;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == true) {
                // Check if file already exists and delete it
                foreach (glob("/drinks/img/avatars/" . $_SESSION["id"] . "*") as $filename) {
                    unlink($filename);
                }

                $path = $_FILES['fileToUpload']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $_SESSION["id"] . "." . $ext)) {//TODO error here but only shows when theres the die()
                    echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                } else {
                    array_push($errorText, "Sorry, there was an error uploading your file.");
                    die("test");
                }
            }

            if ($uploadOk == true) {
                $user = $um->getUserByName($_SESSION['name']);

                $newUser = $um->getUpdateFormData($user);
                $newUser->setAvatar($user->getId() . "." . $ext);

                $userToEdit = $user->getId();
                if ($um->update($newUser, $userToEdit)) {
                    header("Location: /drinks/user/profile");
                } else {
                    array_push($errorText, "Failed to update avatar");
                }
            }
        }

        require("views/changeAvatar.view.php");
    }
}