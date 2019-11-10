<?php
require("../src/DBConnect.php");

$connection = new DBConnect();
$pdo = $connection->getConnection();

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    // Could not get the data that should have been sent.
    die ('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    // One or more values are empty.
    die ('Please complete the registration form!');
}

// We need to check if the account with that username exists.
if ($stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = :username')) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die ('Email is not valid!');
    }
    if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
        die ('Username is not valid!');
    }
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 6) {
        die ('Password must be between 6 and 20 characters long!');
    }
    $stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    if ($stmt->rowCount() > 0) {
        // Username already exists
        echo 'Username exists, please choose another!';
    } else {
        // Username doesnt exists, insert new account
        if ($stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)')) {
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(":username", $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->execute();
            echo 'You have successfully registered, you can now login!';
        } else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            echo 'Could not prepare statement!';
        }
    }
    $stmt=null;
} else {
    // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
    echo 'Could not prepare statement!';
}
$stmt = null;
?>