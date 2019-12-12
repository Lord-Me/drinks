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
                    <a href="/drinks/user/profile/changeAvatar">
                        <img class="img-fluid rounded-circle avatar" src="/drinks/img/avatars/<?=$userInfo->getAvatar() ;?>" alt="userAvatar">
                    </a>
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
                            <td><a href="/drinks/user/profile/changePassword" >Change Password</a></td>
                        </tr>
                    </table>
                    <a href="#" onclick="showForm()">Change language</a>
                    <form id="hiddenForm" method="post" action="#">
                        <select name="language">
                            <option value="english">English</option>
                            <option value="spanish">Español</option>
                            <option value="valencian">Valencià/Català</option>
                        </select>
                        <input type="submit" value="Canviar Idioma">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    window.onload = function() {
        document.getElementById("hiddenForm").style.display = "none";
    };

    function showForm(){
        document.getElementById("hiddenForm").style.display = "block";
    }
</script>
<?php
require('partials/footer.partials.php');
?>
</body>
</html>
