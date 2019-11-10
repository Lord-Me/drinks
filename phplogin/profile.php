<?php

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php?page=login');
    exit();
}

require("src/DBConnect.php");

$connection = new DBConnect();
$pdo = $connection->getConnection();
try {
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
    $stmt = $pdo->prepare('SELECT password, email, role FROM users WHERE id = ?');
// In this case we can use the account ID to get the account info.
    $stmt->bindparam(1, $_SESSION['id'], pdo::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_PROPS_LATE);
    $stmt->execute();
    $userInfo = $stmt->fetch();
    $stmt = null;
}
catch(PDOException $err){
    echo "Error";
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
require('partials/head.partials.php');
?>
</head>
<body>
<?php
if (isset($_SESSION['loggedin'])) {
    require('partials/navigationLoggedIn.partials.php');
}else {
    require('partials/navigation.partials.php');
}
?>
<div class="content">
    <h2>Profile Page</h2>
    <div>
        <p>Your account details are below:</p>
        <table>
            <tr>
                <td>Username:</td>
                <td><?=$_SESSION['name']?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?=$userInfo["password"]?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?=$userInfo["email"]?></td>
            </tr>
        </table>
    </div>
</div>
<?php
require('partials/footer.partials.php');
?>
</body>
</html>
