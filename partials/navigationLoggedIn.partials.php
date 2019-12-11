<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/drinks/">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/drinks/ourDrinks/1">Our Drinks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
            if($_SESSION['role']==1) {
            echo '<a class="navbar-brand" href = "/drinks/user/admin/users" > User Management </a >
            <button class="navbar-toggler" type = "button" data - toggle = "collapse" data - target = "#navbarResponsive" aria - controls = "navbarResponsive" aria - expanded = "false" aria - label = "Toggle navigation" >
                <span class="navbar-toggler-icon" ></span >
            </button >';
             }
        ?>
        <a class="navbar-brand" href="/drinks/user/myDrinks/1">My Drinks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/drinks/user/profile"><?php echo $_SESSION['name'] ?> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/drinks/user/logout">Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>