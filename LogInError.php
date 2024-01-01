<?php
session_start();
include("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    LogIn();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Metro Events</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main">
    <div style="flex: 1">
        <img src="images/zzlogoooo.png" width="200" height="200" id="image">
    </div>
    <form method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input name="username" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password">
            <p style="color: red">Username and Password is not registered</p>
            <small>Don't have an account?<a href="SignUp.php" id="register"> Sign Up Now!</a></small>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
