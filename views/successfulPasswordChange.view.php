<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="/drinks/css/registerStyle.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="register">
    <h1>Password Changed Successfully!</h1>
    <form method="post" autocomplete="off">
        <input type="submit" value="Login Again">
    </form>
</div>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Location: /drinks/login');
}
?>