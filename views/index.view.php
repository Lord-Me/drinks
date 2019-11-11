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
<?php
require('partials/banner.partials.php');
?>
  <section class="sectionMarginTop">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div class="p-5">
            <img class="img-fluid rounded-circle" src="img/professionalDrinks.jpg" alt="">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div class="p-5">
            <h2 class="display-4">Professional cocktails!</h2>
            <p>Professional cocktails, just like you'd find at the bar, now at your fingertips, just like they're served in the most prestigious of cocktail bars.</p>
              <a href="index.php?page=#" class="btn btn-primary btn-xl rounded-pill mt-5">Learn More</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="sectionMarginTop">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="p-5">
            <img class="img-fluid rounded-circle" src="img/originalDrinks.jpg" alt="">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-5">
            <h2 class="display-4">Original cocktails!</h2>
            <p>Original cocktails, made and designed by Greg himself, all based off popular video games, series, movies and more!</p>
              <a href="index.php?page=#" class="btn btn-primary btn-xl rounded-pill mt-5">Learn More</a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php
require('partials/footer.partials.php');
?>
</body>
</html>