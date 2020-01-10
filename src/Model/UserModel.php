<?php

namespace App\Model;

use PDO;
use PDOException;
use App\Entity\User;

class UserModel{
    private $pdo;

    public function  __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /*
     * RETURN ALL
     */
    function getAll():array{
        $posts = $this->pdo->query('SELECT * FROM users');
        return $posts->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\Entity\User');
    }

    function getUserById(int $id):User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\Entity\User');
        $stmt->execute();
        $user = $stmt->fetch();
        if(!empty($user)){
            return $user;
        }else{
            throw new PDOException();
        }
    }

    function getUserByName(string $username):User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\Entity\User');
        $stmt->execute();
        $user = $stmt->fetch();
        if(!empty($user)){
            return $user;
        }else{
            throw new PDOException("El nombre de usuario es invalido");
        }
    }

    function getUserByEmail(string $email):User{
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'App\Entity\User');
        $stmt->execute();
        $user = $stmt->fetch();
        if(!empty($user)){
            return $user;
        }else{
            throw new PDOException("El email es invalido");
        }
    }

    public function getInsertFormData():User{
        $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
        $province = filter_input(INPUT_POST, "province", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $avatar = "defaultAvatar.png";

        $newUser = new User();
        $newUser->setFirstName($firstName);
        $newUser->setLastName($lastName);
        $newUser->setProvince($province);
        $newUser->setUsername($username);
        $newUser->setEmail($email);
        $newUser->setPassword($password);
        $newUser->setAvatar($avatar);
        return $newUser;
    }

    public function getUpdateFormData(User $user):User{
        if(!empty($_POST["firstName"])) {
            $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $firstName = $user->getFirstName();
        }
        if(!empty($_POST["lastName"])){
            $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $lastName = $user->getLastName();
        }
        if(!empty($_POST["province"])){
            $province = filter_input(INPUT_POST, "province", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $province = $user->getProvince();
        }
        if(!empty($_POST["username"])) {
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $username = $user->getUsername();
        }
        if(!empty($_POST["email"])) {
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $email = $user->getEmail();
        }
        if(!empty($_POST["password"])) {
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $password = $user->getPassword();
        }
        if(!empty($_FILES["fileToUpload"])) {
            $avatar = basename( $_FILES["fileToUpload"]["name"]);
            $avatar = filter_var($avatar, FILTER_SANITIZE_SPECIAL_CHARS);
        }else{
            $avatar = $user->getAvatar();
        }

        $newUser = new User();
        $newUser->setFirstName($firstName);
        $newUser->setLastName($lastName);
        $newUser->setProvince($province);
        $newUser->setUsername($username);
        $newUser->setEmail($email);
        $newUser->setPassword($password);
        $newUser->setAvatar($avatar);
        return $newUser;
    }

    function insert(User $user):bool {
        try {
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $province = $user->getProvince();
            $username = $user->getUsername();
            $email = $user->getEmail();
            $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

            $stmt = $this->pdo->prepare('INSERT INTO users (firstName, lastName, province, username, email, password) VALUES (:firstName, :lastName, :province, :username, :email, :password)');
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            $stmt->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $stmt->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $stmt->bindParam(":province", $province, PDO::PARAM_STR);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        catch (PDOException $err) {
            return false;
        }
    }

    function update(User $newUser, int $userToEdit):bool{
        try{
            $firstName = $newUser->getFirstName();
            $lastName = $newUser->getLastName();
            $province = $newUser->getProvince();
            $username = $newUser->getUsername();
            $email = $newUser->getEmail();
            $password = password_hash($newUser->getPassword(), PASSWORD_BCRYPT);
            $avatar = $newUser->getAvatar();

            $stmt = $this->pdo->prepare('UPDATE users SET firstName = :firstName, lastName = :lastName, province = :province, username = :username, email = :email, password = :password, avatar = :avatar where id = :id');
            $stmt->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $stmt->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $stmt->bindParam(":province", $province, PDO::PARAM_STR);
            $stmt->bindParam(':id', $userToEdit, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':avatar', $avatar, PDO::PARAM_STR);
            $stmt->execute();
            echo $stmt->rowCount();
            $stmt = null;

            return true;
        }
        catch (PDOException $err) {
            return false;
        }
    }

    // Rep un objecte Post i comprova que les propietats siguen vàlides.
    public function validate(User $newUser):array {
        $errors = [];
        $firstName = $newUser->getFirstName();
        $lastName = $newUser->getLastName();
        $province = $newUser->getProvince();
        $username = $newUser->getUsername();
        $email = $newUser->getEmail();
        $password = $newUser->getPassword();
        $avatar = $newUser->getAvatar();

        if(!is_string($firstName) || $firstName == NULL || preg_match('/[A-Za-z0-9]+/', $firstName) == 0){
            array_push($errors, "Invalid First Name");
        }
        if(!is_string($lastName) || $lastName == NULL || preg_match('/[A-Za-z0-9]+/', $lastName) == 0){
            array_push($errors, "Invalid LastName");
        }
        if(!is_string($province) || $province == NULL || preg_match('/[A-Za-z0-9]+/', $province) == 0){
            array_push($errors, "Invalid Province");
        }
        if(!is_string($username) || $username == NULL || preg_match('/[A-Za-z0-9]+/', $username) == 0){
            array_push($errors, "Invalid Username");
        }
        if(!is_string($email) || $email == NULL || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errors, "Invalid email");
        }
        if(!is_string($password) || $password == NULL || strlen($_POST["password"]) > 20 || strlen($_POST["password"]) < 5){
            array_push($errors, "Password must be between 5 and 20 characters long!");
        }
        if(!is_string($avatar) || $avatar == NULL){
            array_push($errors, "Invalid avatar");
        }

        return $errors;
    }
}