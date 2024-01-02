<?php
session_start();
include("api.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>METRO EVENTS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/zzlogoooo.png" alt="Bootstrap" width="50" height="50">
            <b style="font-family: 'Lucida Calligraphy'; color: forestgreen">Metro Events</b>
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link"><?php echo '<h5><b>'.$_SESSION['username'].'</b></h5>'?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ProfileUser.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="DashboardIntro.php">Log Out</a>
                </li>
            </ul>
        </div>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search Events" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</nav>
<div style="display: flex">
    <img src="images/zzlandingPage.png" width="1535">
</div>

<div>
    <h3 style="text-align: center; color: forestgreen"><b>ALL EVENTS</b></h3>
    <hr style="width: 50%; margin: auto; padding: 10px">
    <div>
        <?php DisplayEventDetails();?>
    </div>

</div>
</body>
</html>