<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Avatar</title>
    <link href="/drinks/css/loginStyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="/drinks/css/myStyles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="login">
    <h1>Change Avatar</h1>
    <form action="/drinks/user/profile/changeAvatar" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">
            <i class="fas fa-chevron-circle-up"></i>
        </label>
        <input type="file" name="fileToUpload" placeholder="New Avatar" id="fileToUpload" required>
        <p class="errorText"><?=implode("<br>", $errorText)?></p>
        <input type="submit" value="Change Avatar">
        <button onclick="history.go(-1);">Back </button>
    </form>
</div>
</body>
</html>