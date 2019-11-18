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
                <form action="index.php?page=newDrink" method="post" class="addForm">
                    <div class="row">
                        <div class="addFormCategory">
                            <input id="pro" type="radio" name="categoryRadio" value="pro" class="category">
                            <label for="pro">Professional Drinks</label>

                            <input id="ori" type="radio" name="categoryRadio" value="ori" class="category">
                            <label for="ori">Original Drinks</label>
                        </div>
                        <div class="addFormTitle">
                            <label for="title">Title</label>
                            <input id="title" type="text" name="text">
                        </div>
                        <div class="addTitleIngredients">

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
echo $view;
?>

<?php
require('partials/footer.partials.php');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/scrollify-master/jquery.scrollify.js"></script>
<script>
    $(document).ready(function() {
        $.scrollify({
            section : ".scroll",
        });
    });
</script>
</body>
</html>
