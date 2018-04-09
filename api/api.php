<?php

require_once "../inc/db_query.php";

// Database Access
//aa17ib7w8h2n5yh.cjsny3ozhk06.us-east-2.rds.amazonaws.com
$db_con = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);

if ($conn->connect_errno) { // checking connection
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}


function error(){

    $data_all = array('Error:' => "Incorrect usage of API, see FAQ for details"
    );

    $jsondata = json_encode($data_all, JSON_PRETTY_PRINT);

    echo $jsondata;

    exit;
}

function add_account($mysqli, $user, $website, $webname, $webuser, $webpass){
    if(add_website($mysqli, $user, $website, $webname, $webuser, $webpass)){
        $data_all = array('reply' => "success"
        );
    }else{
        $data_all = array('reply' => "failed"
        );
    }

    $jsondata = json_encode($data_all, JSON_PRETTY_PRINT);

    echo $jsondata;

    exit;
}

function remove_account($mysqli, $user, $website){
    if(remove_website($mysqli, $user, $website)){
        $data_all = array('reply' => "success"
        );
    }else{
        $data_all = array('reply' => "failed"
        );
    }

    $jsondata = json_encode($data_all, JSON_PRETTY_PRINT);

    echo $jsondata;

    exit;
}


function update_account($mysqli, $user, $website, $webname, $webuser, $webpass, $webID){
    if(update_website($mysqli, $user, $webID, $website, $webname, $webuser, $webpass)){
        $data_all = array('reply' => "success"
        );
    }else{
        $data_all = array('reply' => "failed"
        );
    }

    $jsondata = json_encode($data_all, JSON_PRETTY_PRINT);

    echo $jsondata;

    exit;
}


function get_account($mysqli, $user, $webid){

    $id = get_user_id($mysqli, $user);
    $sql = "SELECT website, username, password
              FROM ebdb.`$id`
              WHERE id=?;";

    if ($stmt = mysqli_prepare($mysqli, $sql)) {

        $stmt->bind_param('s', $webid);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $website, $username, $password);

        while (mysqli_stmt_fetch($stmt)) {

           $data_all = array('Website:' => $website,
            'Username:' => $username,
            'Password' => $password
            );
        }
    }


    $jsondata = json_encode($data_all, JSON_PRETTY_PRINT);

    echo $jsondata;

    exit;
}

function get_rnd_password($length = 12, $style = 1){
    switch ($style){
        case 1:
            // Default security
            $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            break;
        case 2:
            // No number
            $keyspace = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            break;
        case 3:
            // Max security
            $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!%@#^*";
            break;
        default;
            $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    $data = array('Password:' => random_str($length, $keyspace));
    $jsondata = json_encode($data, JSON_PRETTY_PRINT);
    echo $jsondata;
    exit;
}

if (isset($_GET['type']))
{
    $type = $_GET['type'];

    if (strcmp($type, "getaccount") == 0){
        $hash = hash_password($_GET['pass']);
        if (verify_password($_GET['pass'], $hash)){
            get_account($conn, $_GET['user'], $_GET['webid']);
        }else{
            error();
        }
    }elseif (strcmp($type, "getrandpassword") == 0){
        $length = 12;
        $style = 1;
        if (isset($_GET['length'])){
            $length = $_GET['length'];
        }
        if (isset($_GET['style'])){
            $length = $_GET['style'];
        }
        get_rnd_password($length, $style);
    }elseif (strcmp($type, "addaccount") == 0){
        $hash = hash_password($_GET['pass']);
        if (verify_password($_GET['pass'], $hash)){
            add_account($conn, $_GET['user'], $_GET['website'], $_GET['webname'], $_GET['webuser'], $_GET['webpass']);
        }else{
            error();
        }
    }elseif (strcmp($type, "removeaccount") == 0){
        $hash = hash_password($_GET['pass']);
        if (verify_password($_GET['pass'], $hash)){
            remove_account($conn, $_GET['user'], $_GET['website']);
        }else{
            error();
        }
    }elseif (strcmp($type, "updateaccount") == 0){
        $hash = hash_password($_GET['pass']);
        if (verify_password($_GET['pass'], $hash)){
            $webID = get_website_id($conn, get_user_id($conn, $_GET['user']), $_GET['websiteOld'], $_GET['webuserOld']);
            update_account($conn, $_GET['user'], $_GET['website'], $_GET['webname'], $_GET['webuser'], $_GET['webpass'], $webID);
        }else{
            error();
        }
    }else{
        error();
    }
}else{
    error();
}
