<?php

$switches = 0;
session_start();
$dbuser= "xxxxxx";
$dbpass="xxxxxx";
$dbname = "guess-the-switch";
$con = mysqli_connect("localhost",$dbuser,$dbpass,$dbname);

// To initialize game data
function initialize(){
    $level = 1;
    global $switches;
    // Checking whether user completed previous level before going to next level
    if(isset($_SESSION['level']) && ($_SESSION['level']>0) && ($_SESSION['level']<6) && (isset($_SESSION['gamestatus']) && $_SESSION['gamestatus']) && (isset($_SESSION['gamedata'][$_SESSION['level']]['levelcompleted']) && $_SESSION['gamedata'][$_SESSION['level']]['levelcompleted'] == 1)){
        $level = $_SESSION['level']+1;
    }
    else{
        // Reinitializing game session data
        unset($_SESSION['gamedata']);
        unset($_SESSION['score']);
        unset($_SESSION['level']);
    }
    // initializing new level
    $_SESSION['gamedata'][$level] = array("level" => $level, "noofattempts"=> 0, "score" => 0,"levelcompleted" => 0);
    $_SESSION['level'] = $level;
    if(!isset($_SESSION['score'])){
        $_SESSION['score'] = 0;
    }
    // Deciding no of switches based on level
    switch($level){
        case 1:
            $switches = 5;
            break;
        case 2: 
            $switches = 6;
            break;
        case 3: 
            $switches = 7;
            break;
        case 4: 
            $switches = 8;
            break;
        case 5:
            $switches = 9;
            break;
        case 6:
            $switches = 10;
            break;
    }
    $switch = rand(0,$switches); // Assigning a random switch
    $switch = 1;
    $_SESSION['switch'] = $switch; // Storing switch
}

// To Check Whether the user guess is right or wrong
function check_guess($con){
    $level = $_SESSION['level'];
    // Incrementing no of attempts stored in session
    $_SESSION['gamedata'][$level]['noofattempts'] =  $_SESSION['gamedata'][$level]['noofattempts']+1;
    $noofattempts = $_SESSION['gamedata'][$level]['noofattempts'];
    // Checking the user guess with session data
    if(isset($_SESSION['switch']) && ($_POST['guess'] == $_SESSION['switch']) && ($noofattempts<=3)){
        $_SESSION['gamestatus'] = 1;
        $_SESSION['gamedata'][$level]['levelcompleted'] = 1; 
        switch($noofattempts){
            case 1:
                $_SESSION['gamedata'][$level]['score'] = 30;
                $_SESSION['score'] = $_SESSION['score'] + 30;
                break;
            case 2:
                $_SESSION['gamedata'][$level]['score'] = 20;
                $_SESSION['score'] = $_SESSION['score'] + 20;
                break;
            case 3:
                $_SESSION['gamedata'][$level]['score'] = 10;
                $_SESSION['score'] = $_SESSION['score'] + 10;
                break;
        }
        echo '{"status": "success","gamestatus":1,"score": '.$_SESSION['score'].',"level": '.$_SESSION['level'].'}';
    }
    else{
        if($noofattempts == 3){ // Game over
            $_SESSION['gamestatus'] = 0;
            $sql = "INSERT INTO game_data (remote_address, http_user_agent, gamedata) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".json_encode($_SESSION)."')";
            mysqli_query($con, $sql);
            echo '{"status": "failed","gamestatus":0}';
        }
        else{
            $_SESSION['gamestatus'] = 1;
            echo '{"status": "failed","gamestatus":1}';
        }
    }
}

if(isset($_POST['guess'])){
    check_guess($con);
}
else{
    initialize();
}
?>