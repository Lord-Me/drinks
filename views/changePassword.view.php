<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <link href="/drinks/css/loginStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="/drinks/css/myStyles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="login">
    <h1>Change Password</h1>
    <form action="/drinks/user/profile/changePassword" method="post">
        <label for="currentPassword">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="currentPassword" placeholder="Current Password" id="currentPassword" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="New Password" id="password" required>
        <p class="errorText"><?=implode("<br>", $errorText)?></p>
        <input type="submit" value="Change Password">
        <button onclick="history.go(-1);">Back </button>
    </form>
</div>
</body>
</html>