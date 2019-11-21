<!DOCTYPE html>
<html lang="en">
<?php
require('partials/head.partials.php');
?>
</head>
<body>
<?php
    session_start();
if (isset($_SESSION['loggedin'])) {
    require('partials/navigationLoggedIn.partials.php');
}else {
    require('partials/navigation.partials.php');
}
?>
<div class="container">
    <br><br><br><br><br><br><br>
<h1>404</h1>
</div>
<?php
require('partials/footer.partials.php');
?>
</body>
</html>