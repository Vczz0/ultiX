<?php

session_start();
$server_name = "Admin";
$server_pw = "root";

function logout(){
    $_SESSION = [];
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    session_unset();
}


function login($username, $password) {
    global $server_name;
    global $server_pw;
    
    if (($username == $server_name) && ($password == $server_pw)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        return true;          
    } else {
        return false;
    }
}

function is_login() {
    global $server_name;
    global $server_pw;

    if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
        if (($_SESSION["username"] == $server_name) && ($_SESSION["password"] == $server_pw)) {

            return true;

        } else {
            logout();
            return false;
        }
        
    }
}


?>
