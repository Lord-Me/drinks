<?php


namespace App\Controller;

use App\Entity\Drink;
use App\DBConnect;
use PDOException;
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
    private function getDefaultTwigProperties():array{
        if (isset($_SESSION['loggedin'])) {
            $sessionInfo = [
                "loggedIn" => true,
                "sessionId" => $_SESSION['id']
            ];
            if($_SESSION['role']==1) {
                $isAdmin = ["isAdmin" => true];
            }else{
                $isAdmin = ["isAdmin" => false];
            }
            $username = ["username" => $_SESSION['name']];
        }else {
            $sessionInfo = ["loggedIn" => false];
            $isAdmin = ["isAdmin" => false];
            $username = ["username" => null];
        }
        return array_merge($isAdmin, $sessionInfo, $username);
    }
    private function createPages(array $users, int $usersPerPage):array {
        $pages = [];

        $page = [];
        foreach ($users as $user) {
            array_push($page, $user);
            if (count($page) == $usersPerPage) {
                array_push($pages, $page);
                $page = [];
            }
        }
        //create a final page with left over drinks if there are any due to pagination
        if(!empty($page)){
            array_push($pages, $page);
        }

        return $pages;
    }


    /*
     * LOGIN
     */

    public function login()
    {
        $errorText = [];//HAS ERROR DISPLAY
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
                        ini_set( 'session.cookie_httponly', 1 );
                        session_regenerate_id();
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['name'] = $_POST['username'];
                        $_SESSION['id'] = $user->getId();
                        $_SESSION['role'] = $user->getRole();
                        $_SESSION['language'] = $user->getLanguage();
                        $_SESSION['sessionAge'] = new \DateTime();
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
        session_unset();
        setcookie(session_name(), '', time() - 3600);
        session_destroy();
        header('Location: /drinks');
    }

    /*
     * REGISTER
     */

    public function register()
    {
        $errorText = []; //Contains error messages
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
                        $um->getUserByName($_POST['username']);
                        // Username already exists as it didn't throw an empty error
                        throw new ExceptionUsernameExists();
                    } catch (PDOException $e) {
                        try {
                            $um->getUserByEmail($_POST['email']);
                            // Username already exists as it didn't throw an empty error
                            throw new ExceptionEmailExists();
                        } catch (PDOException $e) {
                            // Username doesnt exists, insert new account
                            if ($um->insert($newUser)) {
                                $user = $um->getUserByName($_POST['username']);
                                ini_set( 'session.cookie_httponly', 1 );
                                session_regenerate_id();
                                $_SESSION['loggedin'] = TRUE;
                                $_SESSION['name'] = $_POST['username'];
                                $_SESSION['id'] = $user->getId();
                                $_SESSION['role'] = $user->getRole();
                                $_SESSION['language'] = $user->getLanguage();
                                $_SESSION['sessionAge'] = new \DateTime();
                                header('Location: /drinks/user/profile');
                            } else {
                                // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                                throw new PDOException('Could not prepare statement!');
                            }
                        }
                    }
                } else {
                    throw new ExceptionInvalidData(implode("<br>", $errors));
                }
            } catch (ExceptionInvalidData $e) {
                array_push($errorText, $e->getMessage());
            } catch (ExceptionUsernameExists $e) {
                array_push($errorText, 'Username exists, please choose another!');
            } catch (ExceptionEmailExists $e) {
                array_push($errorText, 'Email already in use, please choose another!');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        require("views/register.view.php");
    }

    /*
     * USER MANAGEMENT
     */
    public function userManagement($currentPagi)
    {
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }
        if ($_SESSION['role'] != 1) {
            header('Location: /drinks/login');
            exit();
        }
        try {
            //Using the Model for our drinks, I get all of the drinks with the same category
            $dm = new DrinkModel($this->db);
            $um = new UserModel($this->db);

            $userFilter = filter_input(INPUT_GET, 'userFilter', FILTER_SANITIZE_SPECIAL_CHARS);

            if($userFilter == "admin"){
                $allUsers = $um->getAll();
                $users = [];
                foreach ($allUsers as $user){
                    if($user->getRole() == 1){
                        array_push($users, $user);
                    }
                }
            }elseif ($userFilter == "user"){
                $allUsers = $um->getAll();
                $users = [];
                foreach ($allUsers as $user){
                    if($user->getRole() == 2){
                        array_push($users, $user);
                    }
                }
            }else{
                $users = $um->getAll();
            }

            $pages = $this->createPages($users, 7);

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

            $subProperties= [
                "pages" => $pages,
                "currentPagi" => $currentPagi,
                "queryString" => $queryString
            ];
            $properties = array_merge($this->getDefaultTwigProperties(), $subProperties);
            return $this->render('users.twig', $properties);

        } Catch (PDOException $err) {
            // Mostrem un missatge genèric d'error.
            echo "Error: executant consulta SQL.";
        }
        require("views/users.view.php");
    }

    /*
     * USER PROFILE
     */
    public function profile()
    {
        // If the user is not logged in redirect to the login page...
        if (!isset($_SESSION['loggedin'])) {
            header('Location: /drinks/login');
            exit();
        }

        $um = new UserModel($this->db);
        $userInfo = $um->getUserById($_SESSION['id']);

        if(isset($_POST['submit'])){
            $language = filter_input(INPUT_POST, "language", FILTER_SANITIZE_STRING);
            $userInfo->setLanguage($language);

            $um->update($userInfo, $_SESSION['id']);

            //echo $um->getUserById($_SESSION["id"])->getLanguage();

            $_SESSION['language'] = $userInfo->getLanguage();

            header("Location: /drinks/user/profile");
        }

        require("views/profile.view.php");
    }

    /*
     * CHANGE PASSWORD
     */
    public function changePassword()
    {
        $errorText = [];//Error management is used here
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
        session_destroy();

        require("views/successfulPasswordChange.view.php");
    }

    /*
     * CHANGE AVATAR
     */
    public function changeAvatar()
    {
        $errorText = [];//error text used here
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

    /*
     * CHANGE ROLE
     */
    public function changeRole($id){
        try {
            // If the user is not logged in redirect to the login page...
            if (!isset($_SESSION['loggedin'])) {
                header('Location: /drinks/login');
                exit();
            }

            $um = new UserModel($this->db);

            //Get all drink IDs and test the given post ID to see if it exists
            $allUsers = $um->getAll();
            $allIds =[];
            foreach ($allUsers as $user){
                array_push($allIds, $user->getId());
            }
            if (!in_array($id, $allIds)) {
                header("Location: /drinks/user/admin/users/1");
                exit();
            }

            $user = $um->getUserById($id);

            if($_SESSION['id'] != $user->getId()){
                $um->changeRole($user);
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    /*
     * DELETE USER
     */
    public function deleteUser($id){
        try {
            // If the user is not logged in redirect to the login page...
            if (!isset($_SESSION['loggedin'])) {
                header('Location: /drinks/login');
                exit();
            }

            $um = new UserModel($this->db);
            $dm = new DrinkModel($this->db);

            //Get all drink IDs and test the given post ID to see if it exists
            $allUsers = $um->getAll();
            $allIds =[];
            foreach ($allUsers as $user){
                array_push($allIds, $user->getId());
            }
            if (!in_array($id, $allIds)) {
                header("Location: /drinks/user/admin/users/1");
                exit();
            }

            $user = $um->getUserById($id);

            if($dm->getAllByAuthorId($user->getId()) == NULL && $_SESSION['id'] != $user->getId()){
                $um->delete($user);
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);

        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}