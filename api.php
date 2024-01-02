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
        ];

        $_SESSION['uid'] = $newUser['uid'];
        $_SESSION['name'] = $newUser['name'];
        $_SESSION['username'] = $newUser['username'];
        $_SESSION['email'] = $newUser['email'];
        $_SESSION['role'] = $newUser['role'];

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
                "numPar" => [],
                "UpVote" => 0,
                "reviews" => []
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
                "numPar" => [],
                "UpVote" => 0,
                "reviews" => []
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
            } else {
                header("Location: DashboardAdmin.php");
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
    }


    function displayAdminEvents()
    {
        global $createdEvents;
        global $registeredUsers;

        $reversed = array_reverse($createdEvents);

        foreach($reversed as $event){
            $eventID = $event['eid'];
            echo '<div style="display: inline-block; line-height: 10px; width: 300px; height: 300px; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white">
                           <form method="post"><h3 style="color: forestgreen"><b>'.$event['eventName'].'</b></h3>
                           <h6><b>'.$event['eventType'].'</b></h6>
                           <p style="font-size: 15px">'.'Organized by '.'<b>'.$event['organizer'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <h4><b>'.$event['eventLoc'].'</b></h4>
                           <p><b>'.$event['eventDate']. ' | '. $event['eventTime'].'</b></p>
                           <hr style="width: 75%; border-color: black">
                           <p style="line-height: 20px">'.$event['eventDesc'].'</p>                       
                           <input type="submit" class="button" name="cancelEvent" value="Cancel Event" style="color: white; background-color: forestgreen; padding: 10px; border-radius: 30px"/>';
            echo "<input type='hidden' name='CancelEventDetermine' value= $eventID>
                  </form>
                  </div>";

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelEvent'])) {
                $eventIDPar = $_POST['CancelEventDetermine'];
                cancelEvent($eventIDPar);
            }
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
        file_put_contents($notifsJSON, json_encode($notifications, JSON_PRETTY_PRINT));
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
            echo '<h5 style="color: red; font-size: 10px"><i>'.'No Organizer Applications Pending'.'</i></h5>';
        }


        echo "<hr style='width: 75%; border-color: white'>";
        echo "<h5 style='color: white'><b>ADMIN APPLICATIONS</b></h5>";
        echo "<hr style='width: 75%; border-color: white'>";
        if(!empty($admin)){
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
        } else {
            echo '<h5 style="color: red; font-size: 10px"><i>'.'No Admin Applications Pending'.'</i></h5>';
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
        file_put_contents($orgJSON, json_encode($organizers, JSON_PRETTY_PRINT)) && file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT));
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

        file_put_contents($adminJSON, json_encode($admin, JSON_PRETTY_PRINT)) && file_put_contents($usersJSON, json_encode($registeredUsers, JSON_PRETTY_PRINT));
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
                file_put_contents($notifsJSON, json_encode($notifications, JSON_PRETTY_PRINT));
            }
        }
    }

    function DisplayEventDetails(){
        global $createdEvents;
        global $organizers;
        global $registeredUsers;
        $parCounter = 0;
        $userPendingFlag = false;
        $userAcceptedFlag = false;
        $eventID = null;
        $reverse = array_reverse($createdEvents);
        if (!empty($reverse)) {
            foreach ($reverse as $event) {
                $userPendingFlag = false;
                $userAcceptedFlag = false;
                $parCounter = 0;
                echo "<div style='display: inline-block;  color: white; align-content: center; line-height: 10px; width: 1000px; height: flex; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: forestgreen'>";
                echo "<div>";
                echo "<hr style='width: 75%; border-color: white'>";
                echo "<h4 style='color: white'><b>".$event['eventName']."</b></h4>";
                echo "<hr style='width: 75%; border-color: white'>";
                $eventID = $event['eid'];
                echo "<h6>{$event['eventType']}</h6>";
                foreach ($registeredUsers as $user){
                    if($event['organizer'] == $user['name']){
                        echo "<p style='font-size: 12px'>Organized by&nbsp<b>".$user['name']."</b></p>";
                    }
                }

                echo "<hr style='width: 25%; border-color: white'>";
                echo "<h6 style='color: white'><b>Participants:</b></h6>";

                if (!empty($event['numPar'])) {
                    foreach ($event['numPar'] as $part) {
                        if($_SESSION['uid'] == $part['userId']){
                            if($part['status'] == "pending"){
                                $userPendingFlag = true;
                            }else if($part['status'] == "accepted"){
                                $userAcceptedFlag = true;
                            }
                        }
                        $parCounter++;
                    }
                    if($parCounter == 1){
                        echo "<p>$parCounter user has joined</p>";
                    }else{
                        echo "<p>$parCounter users have joined</p>";
                    }

                } else {
                    echo "<p style='color: red; font-size: 10px'><i>No participants for this event.</i></p>";
                }

                echo "<hr style='width: 25%; border-color: white'>";
                echo "<p><b>Upvotes: {$event['UpVote']}</b></p>";

                echo "<hr style='width: 25%; border-color: white'>";
                echo "<h6><b>Reviews:</b></h6>";
                if (!empty($event['reviews'])) {
                    foreach ($event['reviews'] as $review) {
                        foreach($registeredUsers as $user){
                            if($review['userId'] == $user['id']){
                                echo $user['name'];
                            }
                        }
                        echo "<p style='font-size: 15px'>{$review['text']}</p>";
                    }
                } else {
                    echo "<p style='font-size: 10px; color: red'><i>No reviews for this event.</i></p>";
                }
                echo "<hr style='width: 25%; border-color: white'>";

                if($userPendingFlag){
                    echo "<button style='border-radius: 30px; background-color: white; padding: 10px; color: forestgreen'>Request to Join Sent</button>";
                }else if($userAcceptedFlag){
                    echo "<button style='border-radius: 30px; background-color: white; padding: 10px; color: forestgreen'>Joined</button>";
                }else{
                    echo "<form method = 'POST'>";
                    echo "<input type= 'submit' name = 'joinEvent' value = 'Join Event' style='border-radius: 30px; background-color: white; padding: 10px; color: forestgreen'></button>";
                    echo "<input type='hidden' name='joinEventDetermine' value= $eventID>";
                    echo "</form>";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['joinEvent'])) {
                        $eventIDPar = $_POST['joinEventDetermine'];
                        requestToJoin($eventIDPar);
                        header("Refresh:0");
                        exit();
                    }
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo '<h5 style="color: red; font-size: 10px; text-align: center"><i>'.'No Available Events'.'</i></h5>';
        }
    }

    function requestToJoin($eventID){
        global $eventsJSON;
        global $createdEvents;
        $counter = 0;

        foreach($createdEvents as $event){
            $counter++;
            if($event['eid'] == $eventID){
                foreach($event['numPar'] as $participant){
                    if($participant['uid'] == $_SESSION['uid']){
                        return;
                    }
                }
                $newParticipant = [
                    'userId' => $_SESSION['uid'],
                    'status' => "pending"
                ];
                $createdEvents[$counter-1]['numPar'][] = $newParticipant;
            }
        }

        file_put_contents($eventsJSON, json_encode($createdEvents, JSON_PRETTY_PRINT));
    }

    function OrganizerEventsView(){
        global $createdEvents;
        global $registeredUsers;
        $parctr = 0;
        $parctr2 = 0;
        $eventID = null;
        if (!empty($createdEvents)) {
            foreach ($createdEvents as $event) {
                $participantCounter = 0;
                echo "<div style='display: inline-block; color: forestgreen; line-height: 10px; width: 500px; height: flex; border: 1px solid black; margin-top: 1.5%; padding: 10px; border-radius: 10px; background-color: white'>";
                echo "<div>";
                echo "<hr style='width: 75%; border-color: forestgreen'>";
                echo "<h4 style='color: forestgreen'><b>".$event['eventName']."</b></h4>";
                echo "<hr style='width: 75%; border-color: forestgreen'>";
                $eventID = $event['eid'];
                echo "<h6>{$event['eventType']}</h6>";
                foreach ($registeredUsers as $user){
                    if($event['organizer'] == $user['name']){
                        echo "<p style='font-size: 12px'>Organized by&nbsp<b>".$user['name']."</b></p>";
                    }
                }

                echo "<hr style='width: 25%; border-color: forestgreen'>";
                echo "<h6 style='color: forestgreen'><b>Participants:</b></h6>";

                if (!empty($event['numPar'])) {
                    foreach ($event['numPar'] as $parts) {
                        if($_SESSION['uid'] == $parts['userId']){
                            if($parts['status'] == "pending"){
                                $userPendingFlag = true;
                            }else if($parts['status'] == "accepted"){
                                $userAcceptedFlag = true;
                            }
                        }
                        $parctr++;
                    }
                    if($parctr == 1){
                        echo "<p>$parctr user has joined</p>";
                    }else{
                        echo "<p>$parctr users have joined</p>";
                    }

                } else {
                    echo "<p style='color: red; font-size: 10px'>No participants for this event.</p>";
                }

                echo "<hr style='width: 25%; border-color: forestgreen'>";
                echo "<h6><b>List of Accepted Users</b></h6>";
                foreach($event['numPar'] as $parti){
                    foreach($registeredUsers as $user){
                        if($user['uid'] == $parti['userId'] && $parti['status'] == 'accepted'){
                            echo $user['name'];
                            echo "<br>";
                            $parctr2++;
                        }
                    }
                }
                if($parctr2 == 0){
                    echo "<p style='color: red; font-size: 10px'>No participants for this event.</p>";
                }

                echo "<h6><b>List of Pending Users</b></h6>";
                $parctr2 = 0;
                if(!empty($event)){
                    foreach($event['numPar'] as $partic){
                        foreach($registeredUsers as $user){
                            if($user['uid'] == $partic['userId'] && $partic['status'] == 'pending'){
                                $idToAction = $user['uid'];
                                echo $user['name'];
                                $parctr2++;

                                echo "<form method='POST' action = ''>";
                                echo "<input type='submit' name = 'approveUser' value = 'Approve' style='border-radius: 30px; padding: 10px; background-color: forestgreen; color: white; margin: 10px'>";
                                echo "<input type='hidden' name='approveUserDetermine' value= $idToAction>";
                                echo "<input type='hidden' name='eventDetermine' value= $eventID>";
                                echo "</form>";

                                echo "<form method = 'POST'>";
                                echo "<input type= 'submit' name = 'declineUser' value = 'Decline' style='border-radius: 30px; padding: 10px; background-color: forestgreen; color: white'>";
                                echo "<input type='hidden' name='declineUserDetermine' value= $idToAction>";
                                echo "<input type='hidden' name='eventDetermine' value= $eventID>";
                                echo "</form>";

                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approveUser'])) {
                                    $userIDPar = $_POST['approveUserDetermine'];
                                    $eventIDPar = $_POST['eventDetermine'];
                                    respondToEventRequest(true, $eventIDPar, $userIDPar);
                                }

                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['declineUser'])) {
                                    $userIDPar = $_POST['declineUserDetermine'];
                                    $eventIDPar = $_POST['eventDetermine'];
                                    respondToEventRequest(false,  $eventIDPar, $userIDPar);
                                }

                            }
                        }
                    }
                }

                if($parctr2 == 0){
                    echo "<p style='font-size: 10px; color: red;'><i>No pending registrants for this event.</i></p>";
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
             echo '<h5 style="color: red; font-size: 10px"><i>'.'No Applications Pending'.'</i></h5>';
        }
    }

    function respondToEventRequest($status, $eventID, $userID){
        global $eventsJSON;
        global $createdEvents;
        $accepted = "accepted";

        if($status){
            foreach($createdEvents as &$event){
                foreach($event['numPar'] as &$participant){
                    if($participant['userId'] == $userID && $participant['status'] == "pending"){
                        $participant['status'] =  $accepted;
                        sendNotification($userID, $_SESSION['uid'], "Let's Party! Your request to join ".$event['eventName']." event is accepted!");
                        break;
                    }
                }
            }
        } else {
            foreach($createdEvents as &$event){
                if($event['eid'] == $eventID){
                    foreach($event['participants'] as &$participant){
                        if($participant['userId'] == $userID && $participant['status'] == "pending"){
                            $participant['status'] = "declined";
                            $organizerID = $_SESSION['uid'];
                            echo $organizerID;
                            sendNotification($userID, $organizerID, "Sorry, your request to join ".$event['eventName']." event is declined!");
                            break;
                        }
                    }
                }
            }
        }

        file_put_contents($eventsJSON, json_encode($createdEvents, JSON_PRETTY_PRINT));
    }

    function cancelEvent($eventID){
        global $eventsJSON;
        global $createdEvents;
        $found = false;

        foreach($createdEvents as $key => $event){
            if($event['eid'] == $eventID){
                unset($createdEvents[$key]);
                $found = true;
            }
        }

        if($found){
            file_put_contents($eventsJSON, json_encode($createdEvents, JSON_PRETTY_PRINT));
        }
    }
?>
