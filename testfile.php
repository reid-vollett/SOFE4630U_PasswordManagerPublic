<?php


require "inc/config.php";
require "inc/db_query.php";

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);

$data = get_login($conn, 'test@test.com', 1);

foreach ($data as &$value){
    echo $value;
    echo "<br>";
}
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'accountmanage.php';
header("refresh:3;http://$host$uri/$extra");
die();