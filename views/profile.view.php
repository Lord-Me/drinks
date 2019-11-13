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
<section>
    <div style='height: 59px;'></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 order-lg-2">
                <div class="p-5">
                    <img class="img-fluid rounded-circle" src="img/avatars/<?=$userInfo->getAvatar() ;?>.jpg" alt="userAvatar">
                </div>
            </div>
            <div class="col-lg-8 order-lg-1">
                <div class="p-5">
                    <h2 class="display-4">Profile Page</h2>
                    <p>Welcome to your profile page!</p>
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><?=$userInfo->getUsername();?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?=$userInfo->getEmail();?></td>
                        </tr>
                        <tr>
                            <td><a href="index.php?page=changePassword" >Change Password</a></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
require('partials/footer.partials.php');
?>
</body>
</html>
