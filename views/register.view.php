<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="css/registerStyle.css" rel="stylesheet" type="text/css">
    <link href="css/myStyles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="register">
    <h1>Register</h1>
    <form action="/drinks/register" method="post" autocomplete="off">
        <label for="firstName">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="firstName" placeholder="First Name" id="firstName" required>

        <label for="lastName">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="lastName" placeholder="Last Name" id="lastName" required>

        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="username" placeholder="Username" id="username" required>

        <label for="province">
            <i class="fas fa-map-marker-alt"></i>
        </label>
        <input type="text" name="province" placeholder="Province" id="province" required>

        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="email" name="email" placeholder="Email" id="email" required>

        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Password" id="password" required>

        <p class="errorText"><?=implode("<br>", $errorText)?></p>
        <input type="submit" value="Register">
        <button onclick="history.go(-1);">Back </button>
    </form>
</div>
</body>
</html>