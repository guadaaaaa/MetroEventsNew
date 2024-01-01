<?php
session_start();
include("api.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['OrgApp'])) {
    requestToBeOrganizer($_SESSION['uid']);
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>METRO EVENTS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body{
            background-image: url("https://images.squarespace-cdn.com/content/v1/55c37beae4b0336075603f86/1443039469134-E9SLQBQ2OW1Y69KPKFDO/image-asset.jpeg?format=2500w");
            background-size: cover;
            font-family: "Century Gothic";
        }
        .flex-container {
            display: flex;
            flex-direction: column;
        }

        .flex-container > div {
            background-color: whitesmoke;
            width: 1000px;
            margin: 10px;
            text-align: center;
            line-height: 30px;
            border-radius: 30px;
        }

        .flex-container-row {
            display: flex;
            flex-direction: row;
        }

        .flex-container-row > div {
            background-color: whitesmoke;
            width: 1000px;
            margin: 10px;
            padding: 20px;
            text-align: center;
            line-height: 30px;
            border-radius: 30px;
        }
    </style>
</head>
<body>
<div class="flex-container" style="align-items: center">
    <div style="font-family: 'Century Gothic'">
        <a href="Dashboard.php" style="padding: 20px; color: gray">Home</a>
        <a href="DashboardIntro.php" style="padding: 20px; color: gray">Log Out</a>
        <hr style="width: 50%; margin: auto; border-color: black">
        <img src="images/zzlogoooo.png" alt="Bootstrap" width="200" height="200">
        <hr style="width: 50%; margin: auto; border-color: black">
        <?php echo '<h1><b>'.$_SESSION['name'].'</b></h1>'?>
        <hr style="width: 50%; margin: auto; padding:10px; border-color: black">
        <?php //echo '<p>'."No. of Events Participated ".$_SESSION['eventPar'].'</p>'?>
    </div>
    <div class="flex-container-row">
        <div style="background-color: forestgreen; border-radius: 30px">
            <hr style="width: 75%; border-color: white">
            <h4 style="color: white"><b>JOIN US!</b></h4>
            <hr style="width: 75%; border-color: white">
            <form method="post" style="text-align: center; background-color: white; margin: 10px; padding: 10px; border-radius: 30px">
                <p style="text-align: center; width: 500px">Organize events with MetroEvents! Get Geared up to dive into the excitement of upcoming events!</p>
                <?php
                    global $orgJSON;
                    global $organizers;
                    $orgTemp = false;
                    foreach ($organizers as $org){
                        if($_SESSION['uid'] == $org['uid']){
                            $orgTemp = true;
                        }
                    }
                    if($orgTemp){
                        echo "<input type= 'submit' name = 'OrgApp' value = 'Request Pending' style='margin: auto; background-color: forestgreen; color: white; border-radius: 30px'></button>";
                    }else{
                        echo "<input type= 'submit' name = 'OrgApp' value = 'Join the Team' style='margin: auto; background-color: forestgreen; color: white; border-radius: 30px'></button>";
                    }
                ?>
            </form>

            <hr style="width: 75%; border-color: white">
            <h4 style="color: white"><b>NOTIFICATIONS</b></h4>
            <hr style="width: 75%; border-color: white">

        </div>
        <div style="background-color: forestgreen; border-radius: 30px">
            <hr style="width: 75%; border-color: white">
            <h4 style="color: white"><b>EVENTS</b></h4>
            <hr style="width: 75%; border-color: white">
            <?php echo displayAllEvents(); ?>
        </div>
    </div>
</div>
</body>
</html>