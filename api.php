<?php
    $usersJSON = "data/users.json";
    $registeredUsers = json_decode(file_get_contents($usersJSON), true);
    $eventsJSON = "data/events.json";
    $createdEvents = json_decode(file_get_contents($eventsJSON), true);
    $orgJSON = "data/organizer.json";
    $organizers = json_decode(file_get_contents($orgJSON), true);
    $adminJSON = "data/admin.json";
    $admin = json_decode(file_get_contents($adminJSON),true);
    $notifsJSON = "data/notifs.json";
    $notifications = json_decode(file_get_contents($notifsJSON), true);

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
            "role" => 3,
            "eventPar" => 0,
            "eventOrg" => 0
        ];

        $_SESSION['uid'] = $newUser['uid'];
        $_SESSION['name'] = $newUser['name'];
        $_SESSION['username'] = $newUser['username'];
        $_SESSION['email'] = $newUser['email'];
        $_SESSION['role'] = $newUser['role'];
        $_SESSION['eventPar'] = $newUser['eventPar'];
        $_SESSION['eventOrg'] = $newUser['eventOrg'];

        $registeredUsers[] = $newUser;
        if(file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT))){
            header("Location: Dashboard.php");
            exit();
        }else{
            echo "<script>alert('Error Signing Up!')</script>";
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
                $_SESSION['role'] = $user['role'];
                $_SESSION['eventPar'] = $user['eventPar'];
                $_SESSION['eventOrg'] = $user['eventOrg'];
            }
        }
        if($_SESSION['role'] == 1){
            header("Location: DashboardAdmin.php");
            exit();
        } elseif ($_SESSION['role'] == 2){
            header("Location: DashboardOrganizer.php");
            exit();
        } elseif ($_SESSION['role'] == 3){
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
            if ($_SESSION['role'] == 2){
                header("Location: DashboardOrganizer.php");
                exit();
            } elseif ($_SESSION['role'] == 3){
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
            $eventID = $event['eid'];
            echo "<div style='display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white'>
                       <form method='post'><h3 style='color: forestgreen'><b>".$event['eventName']."</b></h3>
                       <h6><b>".$event['eventType']."</b></h6>
                       <p style='font-size: 15px'>"."Organized by "."<b>".$event['organizer']."</b></p>
                       <hr style='width: 75%; border-color: black'>
                       <h4><b>".$event['eventLoc']."</b></h4>
                       <p><b>".$event['eventDate']. " | ". $event['eventTime']."</b></p>
                       <hr style='width: 75%; border-color: black'>
                       <p style='line-height: 20px'>".$event['eventDesc']."</p>                       
                       <input type='submit' formaction='EventDetails.php' class='button' name='SeeEvent' value='See Event' style='color: white; background-color: forestgreen; padding: 10px; border-radius: 30px'/>
                       <input type='hidden' name='SeeEventDetermine' value= $eventID>
                       </form>
                       </div>";
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['SeeEvent'])) {
            $eventIDPar = $_POST['SeeEventDetermine'];
            SeeEvent($eventIDPar);
        }
    }

    function SeeEvent($eventIDPar)
    {
        echo "running";
        echo $eventIDPar;
        global $createdEvents;
        foreach ($createdEvents as $event){
            if($event['eid'] == $eventIDPar){
                echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white">';
                echo $event['eventName'];
                echo '</div>';
            }
        }
        echo "running";
    }


    function displayAdminEvents()
    {
        global $createdEvents;
        global $registeredUsers;

        $reversed = array_reverse($createdEvents);

        foreach($reversed as $event){
            echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white">
                           <form method="post"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                           <h6><b>'.$event['eventType'].'</b></h6>
                           <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <h4><b>'.$event['eventLoc'].'</b></h4>
                           <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <p style="line-height: 20px">'.$event['eventDesc'].'</p>                       
                           <input type="submit" class="button" name="approveEvent" value="Cancel Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>
                           </form>
                      </div>';
        }

    }

    function displayOrganizerEvents()
    {
        global $createdEvents;
        global $registeredUsers;

        $reversed = array_reverse($createdEvents);

        foreach($reversed as $event){
            if($event['organizer'] == $_SESSION['name']){
                echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white">
                           <form method="post"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                           <h6><b>'.$event['eventType'].'</b></h6>
                           <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <h4><b>'.$event['eventLoc'].'</b></h4>
                           <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <p style="line-height: 20px">'.$event['eventDesc'].'</p>                       
                           <input type="submit" class="button" name="approveEvent" value="See Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>
                           </form>
                      </div>';
            }
        }
    }

    function requestToBeOrganizer($userID){
        global $orgJSON;
        global $organizers;

        foreach($organizers as $or){
            if($or['uid'] == $userID){
                return;
            }
        }

        $newOrgReq = [
            'uid' => $userID,
            'status' => "pending"
        ];

        $newApp[] = $newOrgReq;
        if(file_put_contents($orgJSON, json_encode($newApp, JSON_PRETTY_PRINT))){
            header("Refresh:0");
        }

    }

    function requestToBeAdmin($userID){
        global $adminJSON;
        global $admin;

        foreach($admin as $ad){
            if($ad['uid'] == $userID){
                return;
            }
        }

        $newAdminApp = [
            'uid' => $userID,
            'status' => "pending"
        ];

        $newApp[] = $newAdminApp;
        file_put_contents($adminJSON, json_encode($newApp, JSON_PRETTY_PRINT));
    }

    function sendNotification($userID, $senderID, $message){
        global $notifsJSON;
        global $usersJSON;
        global $notifications;
        global $registeredUsers;
        $Sender = null;

        $last = end($notifications);
        if($last == null){
            $newID = 1;
        } else {
            $newID = $last['id']+1;
        }

        foreach($registeredUsers as $user){
            if($user['uid'] == $senderID){
                $Sender = $user['name'];
            }
        }

        $newNotif = [
            'id' => $newID,
            'userId' => $userID,
            'name' => $Sender,
            'type' => "Event Request Update",
            'message' => $message
        ];

        $notifications[] =$newNotif;
        if(file_put_contents($notifsJSON, json_encode($notifications, JSON_PRETTY_PRINT))){
            header("Refresh:0");
        }
    }

    function RequestApplications(){
        global $adminJSON;
        global $orgJSON;
        global $usersJSON;
        global $registeredUsers;
        global $organizers;
        global $admin;

        $id = null;
        echo "<hr style='width: 75%; border-color: white'>";
        echo "<h5 style='color: white'><b>ORGANIZER APPLICATIONS</b></h5>";
        echo "<hr style='width: 75%; border-color: white'>";
        if(!empty($organizers)){
            foreach($organizers as $or){
                $id = $or['uid'];
                foreach($registeredUsers as $user){
                    if($or['uid'] == $user['uid'] && $or['status'] == "pending"){
                        echo "<div style='display: inline-block; line-height: 10px; width: 300px; height: 150px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white'>";
                        echo "<div>";
                        echo "<h4><b>".$user['name']."</b></h4>";
                        echo "<p>Requested to be an Organizer</p>";
                        echo "<form method='POST' action = ''>";
                        echo "<input type='submit' name = 'approveUserOrg' value = 'Approve' style='background-color: forestgreen; border-radius: 30px; padding: 10px; color: white'>";
                        echo "<input type='hidden' name='approveUserOrgDetermine' value= $id>";
                        echo "</form>";
                        echo "<form method = 'POST'>";
                        echo "<input type= 'submit' name = 'declineUserOrg' value = 'Decline' style='background-color: forestgreen; border-radius: 30px; padding: 10px; color: white'>";
                        echo "<input type='hidden' name='declineUserOrgDetermine' value= $id>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approveUserOrg'])) {
                            $userIDPar = $_POST['approveUserOrgDetermine'];
                            OrganizerApplication(true, $userIDPar);
                        }

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['declineUserOrg'])) {
                            $userIDPar = $_POST['declineUserOrgDetermine'];
                            OrganizerApplication(false, $userIDPar);
                        }
                    }
                }
            }
        } else {
            echo '<h5>'.'No Applications Pending'.'</h5>';
        }


        echo "<hr style='width: 75%; border-color: white'>";
        echo "<h5 style='color: white'><b>ADMINISTRATION APPLICATIONS</b></h5>";
        echo "<hr style='width: 75%; border-color: white'>";
        foreach($admin as $ad){
            $id = $ad['uid'];
            foreach($registeredUsers as $user){
                if($ad['uid'] == $user['uid'] && $ad['status'] == "pending"){
                    echo "<div style='display: inline-block; line-height: 10px; width: 300px; height: 150px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white'>";
                    echo "<div>";
                    echo "<h4><b>".$user['name']."</b></h4>";
                    echo "<p>Requested to be an Admin</p>";
                    echo "<form method='POST' action = ''>";
                    echo "<input type='submit' name = 'approveUserAdmin' value = 'Approve' style='background-color: forestgreen; border-radius: 30px; padding: 10px; color: white'>"; //Line 236
                    echo "<input type='hidden' name='approveUserAdminDetermine' value= $id>";
                    echo "</form>";

                    echo "<form method = 'POST'>";
                    echo "<input type= 'submit' name = 'declineUserAdmin' value = 'Decline' style='background-color: forestgreen; border-radius: 30px; padding: 10px; color: white'>";
                    echo "<input type='hidden' name='declineUserAdminDetermine' value= $id>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approveUserAdmin'])) {
                        $userIDPar = $_POST['approveUserAdminDetermine'];
                        AdminApplication(true, $userIDPar);
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['declineUserAdmin'])) {
                        $userIDPar = $_POST['declineUserAdminDetermine'];
                        AdminApplication(false, $userIDPar);
                    }
                }
            }
        }
    }

    function OrganizerApplication($statusApp, $uid){
        global $usersJSON;
        global $orgJSON;
        global $organizers;
        global $registeredUsers;

        if($statusApp){
            foreach($organizers as &$or){
                if($or['uid'] == $uid){
                    $or['status'] = "accepted";
                    sendNotification($uid, $_SESSION['uid'], "Your application to be an organizer was accepted!");
                    foreach($registeredUsers as &$user){
                        if($user['uid'] == $uid){
                            $user['role'] = 2;
                        }
                    }
                }
            }
        }else{
            foreach($organizers as $or){
                if($or['userId'] == $uid){
                    $or['status'] = "declined";
                    sendNotification($uid, $_SESSION['uid'], "Your application to be an organizer was declined!");
                }
            }
        }
        if(file_put_contents($orgJSON, json_encode($organizers, JSON_PRETTY_PRINT)) && file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT))){
            header("Refresh:0");
        }
    }

    function AdminApplication($statusApp, $uid){
        global $usersJSON;
        global $adminJSON;
        global $admin;
        global $registeredUsers;

        if($statusApp){
            foreach($admin as &$ad){
                if($ad['uid'] == $uid){
                    $ad['status'] = "accepted";
                    sendNotification($uid, $_SESSION['uid'], "Your application to be an Administrator was accepted!");
                    foreach($registeredUsers as &$user){
                        if($user['uid'] == $uid){
                            $user['role'] = 1;
                        }
                    }
                }
            }
        }else{
            foreach($admin as &$ad){
                if($ad['uid'] == $uid){
                    $ad['status'] = "declined";
                    sendNotification($uid, $_SESSION['uid'], "Your application to be an Administrator was declined!");
                }
            }
        }

        if(file_put_contents($adminJSON, json_encode($admin, JSON_PRETTY_PRINT)) && file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT))){
            header("Refresh:0");
        }
    }

    function DisplayNotifs(){
        global $notifications;
        $notifID = null;
        foreach($notifications as $notification){
            if($notification['userId'] == $_SESSION['uid']){
                $notifID = $notification['id'];
                echo "<div style='display: inline-block; line-height: 12px; width: 300px; height: 130px; border: 1px solid black; padding: 10px; border-radius: 10px; background-color: white'>";
                echo "<div>";
                echo "<h4>From <b>{$notification['name']}</b></h4>";
                echo "<p>{$notification['message']}</p>";
                echo "<form method = 'POST'>";
                echo "<input type= 'submit' name = 'deleteNotif' value = 'Delete' style='background-color: forestgreen; border-radius: 30px; padding: 10px; color: white'></button>";
                echo "<input type='hidden' name='deleteNotifDetermine' value= $notifID>";
                echo "</form>";
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteNotif'])) {
                    $notificationIDPar = $_POST['deleteNotifDetermine'];
                    deleteNotification($notificationIDPar);
                }
                echo "</div>";
                echo "</div>";
            }
        }
    }

    function deleteNotification($notificationIDPar){
        global $notifsJSON;
        global $notifications;

        foreach($notifications as $key => $notif){
            if($notif['id'] == $notificationIDPar){
                unset($notifications[$key]);
                if(file_put_contents($notifsJSON, json_encode($notifications, JSON_PRETTY_PRINT))){
                    header("Refresh:0");
                }
            }
        }
    }

?>
