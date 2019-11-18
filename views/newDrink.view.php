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
<br><br><br>
<section>
    <div class="container">
        <div  class="row align-items-center text-center">
            <div class='col-lg-12'>
                <form action="index.php?page=newDrink" method="post"  enctype="multipart/form-data" class="addForm">
                    <div class="row">

                        <div class="addFormTitle">
                            <label for="title">Title</label>
                            <input id="title" type="text" name="title">
                        </div>
                        <div class="addFormIngredients">
                            <label for="ingredients">Ingredients</label>
                            <textarea id="ingredients" rows="4" cols="50" name="ingredients"></textarea>
                        </div>
                        <div class="addFormContent">
                            <label for="content">Instructions</label>
                            <textarea id="content" rows="4" cols="50" name="content"></textarea>
                        </div>
                        <div class="addFormCategory">
                            <input id="pro" type="radio" name="category" value=1 class="category">
                            <label for="pro">Professional Drinks</label>

                            <input id="ori" type="radio" name="category" value=2 class="category">
                            <label for="ori">Original Drinks</label>
                        </div>
                        <div class="addFormImage">
                            <label for="fileToUpload"><i class="fas fa-chevron-circle-up"></i></label>
                            <input type="file" name="fileToUpload" placeholder="Preview Image" id="fileToUpload">
                        </div>
                    </div>
                    <div class="row filterFormSubmit">
                        <input type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<?php
require('partials/footer.partials.php');
?>
</body>
</html>
