<?php


function hash_password($pass){
    $hashOptions = [
        'cost' => 11,
        'salt' => random_bytes(30),
    ];
    return password_hash($pass, PASSWORD_BCRYPT, $hashOptions);
}


function verify_password($password, $hash){
    if (password_verify($password, $hash)) {
        return true;
    } else {
        return false;
    }
}


function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

/**
 * @param $mysqli
 * @param $id
 * @param $website
 * @param $username
 * @return mixed
 */
function get_website_id($mysqli, $id, $website, $username){

    $sql = "SELECT id FROM ebdb.`$id` WHERE website=? AND username=?;";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('ss', $website, $username);
        /* execute statement */
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $webid);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            return $webid;
        }
    }
}

/**
 * @param $mysqli
 * @param $user
 * @param $webid
 * @return array
 */
function get_login($mysqli, $user, $webid){
    $return = [];

    $id = get_user_id($mysqli, $user);

    $sql = "SELECT * FROM ebdb.`$id` WHERE id=?;";

    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('s', $webid);
        /* execute statement */
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $webid, $website, $webname, $username, $password);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            $return[] = $website;
            $return[] = $webname;
            $return[] = $username;
            $return[] = $password;
        }

        return $return;
    }
}

/**
 * @param $mysqli
 * @param $user
 * @return mixed
 */
function count_logins($mysqli, $user){

    $id = get_user_id($mysqli, $user);

    $sql = "SELECT COUNT(id) FROM ebdb.`$id`";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {

        /* execute statement */
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $amount);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            return $amount;
        }
    }
}

/**
 * @param $mysqli
 * @param $user
 * @return array
 */
function get_webids($mysqli, $user){
    $webids = array();

    $id = get_user_id($mysqli, $user);

    $sql = "SELECT id FROM ebdb.`$id`";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {

        /* execute statement */
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $id);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            $webids[] = $id;
        }
    }

    return $webids;
}

/**
 * @param $mysqli
 * @param $user
 * @param $webid
 */
function remove_website($mysqli, $user, $webid){
    $id = get_user_id($mysqli, $user);

    $sql = "DELETE FROM ebdb.`$id`
            WHERE id=?";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('s', $webid);
        /* execute statement */
        mysqli_stmt_execute($stmt);
        return true;
    }
    return false;
}

/**
 * @param $mysqli
 * @param $user
 * @param $website
 * @param $webname
 * @param $username
 * @param $pass
 */
function update_website($mysqli, $user, $currWebID, $website, $webname, $username, $pass){
    $id = get_user_id($mysqli, $user);

    $sql = "UPDATE ebdb.`$id`
            SET website=?, username=?, password=?, webname=?
            WHERE id=?";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('sssss', $website, $username, $pass, $webname, $currWebID);
        /* execute statement */
        mysqli_stmt_execute($stmt);
        return true;
    }
    return false;
}

/**
 * @param $mysqli
 * @param $user
 * @param $website
 * @param $webname
 * @param $username
 * @param $pass
 * @return bool
 */
function add_website($mysqli, $user, $website, $webname, $username, $pass){
    $id = get_user_id($mysqli, $user);

    $sql = "INSERT INTO ebdb.`$id`(website, webname, username, password) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('ssss', $website, $webname, $username, $pass);
        /* execute statement */
        mysqli_stmt_execute($stmt);
        return true;
    }
    return false;
}



/**
 * @param $mysqli - MySQL conenction
 * @param $user - User to search
 * @return mixed - Returns the ID that matches the email
 */
function get_user_id($mysqli, $user){
    $sql = "SELECT id
              FROM users
              WHERE email=?";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('s', $user);
        /* execute statement */
        mysqli_stmt_execute($stmt);
        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $id);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            return $id;
        }
    }
}

/**
 * @param $mysqli - MySQL Connection
 * @param $user - User to be inserted
 * @param $pass - Pass to be inserted
 */
function insert_user($mysqli, $user, $pass){
    $pass = hash_password($pass);

    $sql = "INSERT INTO users(email, password) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('ss', $user, $pass);
        /* execute statement */
        mysqli_stmt_execute($stmt);
    }
    create_user_table($mysqli, $user);
}

/**
 * @param $mysqli - MySQL Connection
 * @param $query - Email to search
 * @return bool - Return if user is already created
 */
function check_user($mysqli, $query){
    $sql = "SELECT email
              FROM users";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        /* execute statement */
        mysqli_stmt_execute($stmt);
        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $user);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            if($query == $user) {
                return true;
            }
        }
    }
    return false;
}

/**
 * @param $mysqli
 * @param $user
 * @param $pass
 * @return bool
 */
function check_pass($mysqli, $user, $pass){
    $sql = "SELECT password
              FROM users
              WHERE email = ?";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        $stmt->bind_param('s', $user);
        /* execute statement */
        mysqli_stmt_execute($stmt);
        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $password);
        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            if(verify_password($pass, $password)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * @param $mysqli
 * @return mixed
 */
function get_all_users($mysqli){
    $sql = "SELECT id
              FROM users";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        /* execute statement */
        mysqli_stmt_execute($stmt);
        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $ids);
        return $ids;
    }
}

/**
 * @param $mysqli
 */
function drop_users($mysqli){
    $ids = get_all_users($mysqli);

    foreach ($ids as $id){
        $sql = "DROP TABLE ?";
        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            $stmt->bind_param('s', $id);
            /* execute statement */
            mysqli_stmt_execute($stmt);
        }
    }
    $sql = "DROP TABLE users";

    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        /* execute statement */
        mysqli_stmt_execute($stmt);
    }
}

function create_user_table($mysqli, $user){
    $id = get_user_id($mysqli, $user);
    $sql = "CREATE TABLE `$id`
                (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    website TEXT(512) NOT NULL,
                    webname TEXT(512) NOT NULL,
                    username TEXT(512) NOT NULL,
                    password TEXT(512) NOT NULL
                );";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        mysqli_stmt_execute($stmt);
        return true;
    }
    return false;
}

function create_tables($mysqli){
    $sql = "CREATE TABLE users
                (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    email TEXT(255) NOT NULL,
                    password TEXT(255) NOT NULL
                );
            CREATE UNIQUE INDEX users_id_uindex ON users (id);";
    if ($stmt = mysqli_prepare($mysqli, $sql)) {
        mysqli_stmt_execute($stmt);
        return true;
    }
    return false;
}
?>