<?php
session_start();

require_once("inc/config.php");
require_once("inc/db_query.php");

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['emailcreate']))
{
    $user = $_POST['emailcreate'];
    $pass = $_POST['passwordcreate'];
    $passcheck = $_POST['password-repeat'];

    if(check_user($conn, $user)) {
        if(strcmp($pass, $passcheck) != 0){
            include 'templates/static/header-template.php';
            include 'templates/login/createfail-template.php';
        }
    }else{
        insert_user($conn, $user, $pass);
		include 'templates/static/header-template.php';
        include 'templates/login/createsuccess-template.php';
    }
}

if (isset($_POST['emaillogin']))
{
    $user = $_POST['emaillogin'];
    $pass = $_POST['passwordlogin'];

    if(check_user($conn, $user)){
        if(check_pass($conn, $user, $pass)){
            $_SESSION['email'] = $user;
			include 'templates/user/user-header-template.php';
            include 'templates/user/user-landing-template.php';
        }else{
            include 'templates/static/header-template.php';
            include 'templates/login/accountfailed-template.php';
        }
    }else{
		include 'templates/static/header-template.php';
        include 'templates/login/accountfailed-template.php';
    }
}

include 'templates/static/footer-template.php';
?>
