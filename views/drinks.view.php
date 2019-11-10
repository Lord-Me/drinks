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
            <form id="filterContainer">
                <div class="row">
                    Search: <input type="text" name="search" value="Search..."><br>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <input id="check1" type="checkbox" name="check" value="0" class="filter">
                        <label for="check1">Professional Drinks</label>
                        <br>
                        <input id="check2" type="checkbox" name="check" value="1" class="filter">
                        <label for="check2">Original Drinks</label>
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
