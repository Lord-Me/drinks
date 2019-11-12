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
