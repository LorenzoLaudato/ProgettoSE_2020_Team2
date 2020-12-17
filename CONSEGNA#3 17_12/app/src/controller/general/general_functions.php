<?php


/**days is an array in which there are the name of the day and his associate number of the day */
$days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];



/**This functions takes in input a number and returns the name associated to it.
 * Params: $num: number of the day.
 * */
function getDayName($num)
{
    global $days;
    return $days[$num];
}


/**This functions shows an HTML list containing all the skills needed to carry out the selected Activity.
 * Params: $ret: return value of the query to the DB about all the skills needed to carry out the selected Activity.
 * */
function showSkillsNeeded($ret)
{
    //serializzo l'array in formato string
    while ($row = pg_fetch_row($ret)) {
        $serialized = "";
        for ($i = 0; $i < count($row); $i++) {
            $serialized .= $row[$i];
            echo "<li>$serialized</li>"; //aggiunta di un elemento alla lista delle skills da mostrare all'utente
        }
    }
}


/**This functions manages the login of the user .*/
function manageLogger()
{
    if ($_POST['username'] || $_POST['password'] || $_POST['userType']) {
        $username1 =  $_POST['username'];
        $password1 =  $_POST['password'];
        $userType = $_POST['userType']; //Planner - Maintainer - System Administrator


        $micro = ServiceFactory::create();
        $hash = $micro->getPassword($username1, $userType);
        if (!$hash) {
            echo "<p> The user $username1 does not exist and/or he is not a $userType. <a href=\"login.html\">Try again!</a></p>";
        } else {
            if (password_verify($password1, $hash)) {
                echo "USER: $username1 - $userType";
                echo "<p>Login successful</p>";
                echo '<img src="../../resources/images/user.png" style="width:150px; height: 150px;" />';
                //Se il login è corretto, inizializziamo la sessione
                session_start();
                $_SESSION['username'] = $username1;
                if ($userType == 'Planner') {
                    echo "<p><b><a href=\"..\planner\page1.php\">Access</a></b> the content reserved for users.<p>";
                }
                /*USER MAINTAINER ANCORA NON IMPLEMENTATO
                if($userType == 'Maintainer')
                    echo "<p><a href=\"planner\page1.php\">Sign in</a> al contenuto riservato solo agli utenti registrati<p>";
                */
                if ($userType == 'System Administrator')
                    echo "<p><b><a href=\"..\system administrator\configuration.php?username=$username1\">Access</a></b> the content reserved for users.<p>";
            } else {
                echo 'The username or password you have entered is invalid. <b><a href="login.html">Try again!</a></b>';
            }
        }
    } else {
        echo "<p>ERROR: username or password not entered <b><a href=\"login.html\">Try again!</a></b></p>";
        exit();
    }
}
