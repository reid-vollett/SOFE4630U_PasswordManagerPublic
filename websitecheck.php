<?php
session_start();

require_once("inc/config.php");
require_once("inc/db_query.php");

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST['url']))
{
    $currUser = $_SESSION['email'];
    $website = $_POST['url'];
    $webname = $_POST['name'];
    $user = $_POST['username'];
    $pass = $_POST['password'];

    add_website($conn, $currUser, $website, $webname, $user, $pass);
}


if (isset($_POST['url-update']))
{
    $currUser = $_SESSION['email'];
    $currWebID = $_SESSION['webid'];
    $website = $_POST['url-update'];
    $webname = $_POST['name'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    update_website($conn, $currUser, $currWebID, $website, $webname, $user, $pass);
    $_SESSION['webid'] = 0;
}

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'accountmanage.php';
header("refresh:1;url=http://$host$uri/$extra");
die();
?>
