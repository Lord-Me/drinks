<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="css/loginStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="css/myStyles.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="login">
    <h1>Login</h1>
    <form action="index.php?page=login" method="post">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Password" id="password" required>
        <p class="errorText"><?=implode("<br>", $errorText)?></p>
        New here? <a href="index.php?page=register">Create an account</a>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>