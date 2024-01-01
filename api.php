<?php
    $usersJSON = "data/users.json";
    $registeredUsers = json_decode(file_get_contents($usersJSON), true);
    $eventsJSON = "data/events.json";
    $createdEvents = json_decode(file_get_contents($eventsJSON), true);
    $newUser;
    $newEvent;
    function SignUp()
    {
        global $usersJSON;
        global $registeredUsers;
        global $newUser;

        $newUser = [
            "uid" => count($registeredUsers)+1,
            "email" => $_POST['email'],
            "name" => $_POST['Name'],
            "username" => $_POST['Username'],
            "password" => $_POST['password'],
            "status" => 3,
            "eventPar" => 0,
            "eventOrg" => 0
        ];

        $_SESSION['uid'] = $newUser['uid'];
        $_SESSION['name'] = $newUser['name'];
        $_SESSION['username'] = $newUser['username'];
        $_SESSION['email'] = $newUser['email'];
        $_SESSION['status'] = $newUser['status'];
        $_SESSION['eventPar'] = $newUser['eventPar'];
        $_SESSION['eventOrg'] = $newUser['eventOrg'];

        $registeredUsers[] = $newUser;
        if(file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT))){
            header("Location: Dashboard.php");
            exit();
        }else{
            echo "<script>alert('Something went wrong, please try again')</script>";
        }
    }

    function LogIn()
    {
        global $registeredUsers;
        foreach ($registeredUsers as $user){
            if($user['username'] == $_POST['username'] && $user['password'] === $_POST['password']){
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['uid'] = $user['uid'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['eventPar'] = $user['eventPar'];
                $_SESSION['eventOrg'] = $user['eventOrg'];
            }
        }
        if($_SESSION['status'] == 1){
            header("Location: DashboardAdmin.php");
            exit();
        } elseif ($_SESSION['status'] == 2){
            header("Location: DashboardOrganizer.php");
            exit();
        } elseif ($_SESSION['status'] == 3){
            header("Location: Dashboard.php");
            exit();
        } else {
            header("Location: LogInError.php");
            exit();
        }
    }

    function createNewEvent()
    {
        global $eventsJSON;
        global $registeredUsers;
        global $createdEvents;

        $lastEvent = end($createdEvents);
        if($lastEvent == null){
            $newEvent = [
                "eid" => 1,
                "eventName" => $_POST['eventName'],
                "eventType" => $_POST['eventType'],
                "eventDate" => $_POST['eventDate'],
                "eventTime" => $_POST['eventTime'],
                "eventLoc" => $_POST['eventLoc'],
                "organizer" => $_SESSION['name'],
                "eventDesc" => $_POST['eventDesc'],
                "status" => 0,
                "numPar" => 0,
                "UpVote" => 0,
                "DownVote" => 0
            ];
        } else {
            $newEvent = [
                "eid" => $lastEvent['eid']+1,
                "eventName" => $_POST['eventName'],
                "eventType" => $_POST['eventType'],
                "eventDate" => $_POST['eventDate'],
                "eventTime" => $_POST['eventTime'],
                "eventLoc" => $_POST['eventLoc'],
                "organizer" => $_SESSION['name'],
                "eventDesc" => $_POST['eventDesc'],
                "status" => 0,
                "numPar" => 0,
                "UpVote" => 0,
                "DownVote" => 0
            ];
        }

        $_SESSION['eid'] = $newEvent['eid'];
        $_SESSION['eventName'] = $newEvent['eventName'];
        $_SESSION['eventType'] = $newEvent['eventType'];
        $_SESSION['eventDate'] = $newEvent['eventDate'];
        $_SESSION['eventTime'] = $newEvent['eventTime'];
        $_SESSION['eventLoc'] = $newEvent['eventLoc'];
        $_SESSION['eventDesc'] = $newEvent['eventDesc'];

        $createdEvents[] = $newEvent;
        if(file_put_contents($eventsJSON, json_encode($createdEvents, JSON_PRETTY_PRINT))){
            if($_SESSION['status'] == 1){
                header("Location: DashboardAdmin.php");
                exit();
            } elseif ($_SESSION['status'] == 2){
                header("Location: DashboardOrganizer.php");
                exit();
            } elseif ($_SESSION['status'] == 3){
                header("Location: Dashboard.php");
                exit();
            }
        }else{
            echo "<script>alert('Something went wrong, please try again')</script>";
        }
    }

    function displayAllEvents()
    {
        global $createdEvents;
        global $registeredUsers;

        $reversed = array_reverse($createdEvents);

        foreach($reversed as $event){
            if($event['status'] == 0){
                echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white">
                       <form method="post"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                       <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                       <hr style="width: 75%; border-color: black">
                       <h4><b>'.$event['eventType'].'</b></h4>
                       <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                       <hr style="width: 75%; border-color: black">
                       <p>'.$event['eventDesc'].'</p>                       
                       <input type="submit" class="button" name="approveEvent" value="See Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>
                       </form>
                  </div>';
            }
        }

    }

    function displayAllApprovedEvents()
    {
        global $createdEvents;
        global $registeredUsers;

        $reversed = array_reverse($createdEvents);

        foreach($reversed as $event){
            if($event['status'] == 1 && $event['organizer'] != $_SESSION['name']){
                echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin: 10px; padding: 10px; border-radius: 10px; background-color: white">
                           <form method="get"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                           <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <h4><b>'.$event['eventType'].'</b></h4>
                           <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <p>'.$event['eventDesc'].'</p>                       
                           <input type="submit" class="button" name="join" value="Join Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>
                           </form>
                      </div>';
            } elseif($event['status'] == 1 && $event['organizer'] === $_SESSION['name']) {
                echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin: 10px; padding: 10px; border-radius: 10px; background-color: white">
                           <form method="get"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                           <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <h4><b>'.$event['eventType'].'</b></h4>
                           <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <p>'.$event['eventDesc'].'</p>                       
                           <input type="submit" class="button" name="organize" value="Organize Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>
                           </form>
                      </div>';
            }
        }
    }
?>
