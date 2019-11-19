<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=index">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="index.php?page=drinks&pagi=0">Drinks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
            if($_SESSION['role']==1) {
            echo '<a class="navbar-brand" href = "index.php?page=users" > User Management </a >
            <button class="navbar-toggler" type = "button" data - toggle = "collapse" data - target = "#navbarResponsive" aria - controls = "navbarResponsive" aria - expanded = "false" aria - label = "Toggle navigation" >
                <span class="navbar-toggler-icon" ></span >
            </button >';
             }
        ?>
        <a class="navbar-brand" href="index.php?page=myDrinks">My Drinks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=user"><?php echo $_SESSION['name'] ?> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=logout">Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>