<!DOCTYPE html>
<html>
<head>
    <title>Metro Events</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: url("https://groupgordon.com/wp-content/uploads/2022/04/Messe_Luzern_Corporate_Event.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="main">
    <form method="post">
        <div>
            <img src="images/zzlogoooo.png" width="200" height="200" style="margin: auto; padding: 10px">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="email-help" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input name="Username" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter username">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input name="Name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary" style="background-color: forestgreen; color: white">Submit</button>
        <small>Already have an account? <a href="LogIn.php" id="logIn">Log In Now!</a></small>
    </form>
</div>
</body>
</html>

<?php
session_start();
include("api.php");

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    SignUp();
}
?>