<?php
/*	id
	username    varchar(20)
	email	varchar(40)
    password	varchar(100)
	role	int(11)
	avatar	varchar(40)
*/
require("../src/DBConnect.php");

$connection = new DBConnect();
$pdo = $connection->getConnection();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    die ('Please fill both the username and password field!');
}

$stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
$stmt->bindparam(1, $_POST['username'], pdo::PARAM_STR);
$stmt->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $loginInfo = $stmt->fetch();
    // Account exists, now we verify the password.
    // Note: remember to use password_hash in your registration file to store the hashed passwords.
    if (password_verify($_POST['password'], $loginInfo["password"])) {
        // Verification success! User has loggedin!
        // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $loginInfo["id"];
        echo $_SESSION['loggedin'];
        echo $_SESSION['name'];
        echo $_SESSION['id'];
        header('Location: home.php');
    } else {
        echo 'Incorrect password!';
    }
} else {
    echo 'Incorrect username!';
}
$stmt=null;
?>