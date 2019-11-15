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
<br><br><br>
<section>
    <div class="container">
        <div  class="row align-items-center">
            <form action="index.php?page=drinks<?php echo $authorUrl ?>" method="post" id="filterContainer">
                <div class="row">
                    Search: <input type="text" name="search" placeholder="Search..."><br>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <input id="filterAll" type="radio" name="categoryFilter" value="all" checked="checked" class="filter">
                        <label for="filterAll">All Drinks</label>

                        <input id="filterProf" type="radio" name="categoryFilter" value="pro" class="filter">
                        <label for="filterProf">Professional Drinks</label>

                        <input id="filterOri" type="radio" name="categoryFilter" value="ori" class="filter">
                        <label for="filterOri">Original Drinks</label>
                    </div>
                    <div class="col-lg-6">
                        Search between times
                        <input type="date" name="time1" value=""><br>
                        <input type="date" name="time2" value=""><br>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="Submit">
                </div>
            </form>
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
