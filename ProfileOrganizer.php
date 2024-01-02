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
            width: 1500px;
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
            width: 1500px;
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
        <a href="DashboardOrganizer.php" style="padding: 20px; color: gray">Home</a>
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
            <h4 style="color: white"><b>CREATE EVENT</b></h4>
            <hr style="width: 75%; border-color: white">
            <form method="post" style="text-align: justify">
                <label for="eventName" style="color: white">Event Name</label>
                <input name="eventName" id="eventName" type="text" style="border-radius: 30px; width: 350px; height: 50px; padding: 20px">
                <label for="eventType" style="color: white">Event Type</label>
                <input name="eventType" id="eventType" type="text" style="border-radius: 30px; width: 350px; height: 50px; padding: 20px">
                <label for="eventLoc" style="color: white">Event Location</label>
                <input name="eventLoc" id="eventLoc" type="text" style="border-radius: 30px; width: 350px; height: 50px; padding: 20px">
                <label for="eventDate" style="color: white; text-align: justify">Event Date</label>
                <input name="eventDate" id="eventDate" type="date" style="border-radius: 30px; width: 350px; height: 50px; padding: 20px">
                <label for="eventTime" style="color: white; text-align: justify">Event Time&nbsp&nbsp&nbsp</label>
                <input name="eventTime" id="eventTime" type="time" style="border-radius: 30px; width: 350px; height: 50px; padding: 20px">
                <label for="eventDesc" style="color: white; text-align: justify">Description</label>
                <textarea name="eventDesc" id=eventDesc rows="4" cols="61" style="line-height: 20px; border-radius: 30px; padding: 10px"></textarea>
                <br><br>
                <button type="submit" class="btn btn-primary" style="margin: auto; width: 480px; background-color: white; color: forestgreen; border-radius: 30px"><b>POST EVENT</b></button>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventName']) && isset($_POST['eventType'])){
                createNewEvent();
            }
            ?>
            <div style="background-color: forestgreen; border-radius: 30px">
                <hr style="width: 75%; border-color: white">
                <h4 style="color: white"><b>APPLY NOW!</b></h4>
                <hr style="width: 75%; border-color: white">
                <form method="post" style="text-align: center; background-color: white; margin: 10px; padding: 10px; border-radius: 30px">
                    <p style="text-align: center; width: 430px">Apply to be a MetroEvents Admin now and get Geared up to dive into the excitement of upcoming events!</p>
                    <?php
                        global $adminJSON;
                        global $admin;
                        $adminTemp = false;
                        foreach ($admin as $ad){
                            if($_SESSION['uid'] == $ad['uid']){
                                $adminTemp = true;
                            }
                        }
                        if($adminTemp){
                            echo "<input type= 'submit' name = 'AdminApp' value = 'Request Pending' style='margin: auto; background-color: forestgreen; color: white; border-radius: 30px'></button>";
                        }else{
                            echo "<input type= 'submit' name = 'AdminApp' value = 'Join the Team' style='margin: auto; background-color: forestgreen; color: white; border-radius: 30px'></button>";
                        }
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['AdminApp'])) {
                            requestToBeAdmin($_SESSION['uid']);
                        }
                    ?>
                </form>
            </div>
        </div>
        <div style="background-color: forestgreen; border-radius: 30px">
            <hr style="width: 75%; border-color: white">
            <h4 style="color: white"><b>NOTIFICATIONS</b></h4>
            <hr style="width: 75%; border-color: white">
            <?php echo DisplayNotifs(); ?>
        </div>
        <div style="background-color: forestgreen; border-radius: 30px">
            <hr style="width: 75%; border-color: white">
            <h4 style="color: white"><b>MY EVENTS</b></h4>
            <hr style="width: 75%; border-color: white">
            <?php echo OrganizerEventsView(); ?>
        </div>
    </div>
</div>
</body>
</html>