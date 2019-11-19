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
                <form action="index.php?page=myDrinks" method="get" id="filterContainer" class="filterForm">
                    <input type="hidden" name="page" value="<?=$_GET["page"] ?>">
                    <div class="row">
                        <div class="col-lg-6 order-lg-1 filterFormLeft">
                            <div class="row filterFormSearch">
                                <label for="titleSearch">Search title: </label>
                                <input id="titleSearch" type="text" name="titleSearch" placeholder="Search..." class="filter"><br>
                            </div>
                            <div class="row filterFormRadio">
                                <input id="filterAll" type="radio" name="categoryFilter" value="all" checked="checked" class="filter">
                                <label for="filterAll">All Drinks</label>

                                <input id="filterProf" type="radio" name="categoryFilter" value="pro" class="filter">
                                <label for="filterProf">Professional Drinks</label>

                                <input id="filterOri" type="radio" name="categoryFilter" value="ori" class="filter">
                                <label for="filterOri">Original Drinks</label>
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-2 filterFormRight">
                            <p>Search between dates</p>
                            <input class="filterFormDate" type="date" name="time1" value="">
                            <input class="filterFormDate" type="date" name="time2" value="">
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

<section>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="p-5 text-center">
                    <a class="newDrinkButton" href="index.php?page=newDrink">Create New Drink</a>
                </div>
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
</body>
</html>
