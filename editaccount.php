<?php
session_start();


require_once("inc/config.php");
require_once("inc/db_query.php");

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);


if (isset($_POST['webid'])){

    include 'templates/user/user-header-template.php';

    $user = $_SESSION['email'];
    $webid = $_POST['webid'];
    $data = get_login($conn, $user, $webid);
    $_SESSION['webid'] = $webid;

    echo "<body>
    <div class=\"wrap\">
        <div class=\"container\">
            <form class=\"cool-b4-form\" method=\"post\" action=\"websitecheck.php\">
                <h4 class=\"text-center pt-2\">Edit Site</h4>
                <div class=\"form-row\">
                    <div class=\"col-md-6\">
                        <div class=\"form-group\">
                            <input type=\"text\" class=\"form-control\" name=\"url-update\" id=\"url-update\" value=\"" . $data[0] . "\">
                            <label for=\"url\">^ Login URL</label>
                            <span class=\"input-highlight\"></span>
                        </div>
                        <div class=\"form-group\">
                            <input type=\"text\" class=\"form-control\" name=\"name\" id=\"name\" value=\"" . $data[1] . "\">
                            <label for=\"name\">^ Website Name</label>
                            <span class=\"input-highlight\"></span>
                        </div>
                        <div class=\"form-group\">
                            <input type=\"text\" class=\"form-control\" name=\"username\" id=\"username\" value=\"" . $data[2] . "\">
                            <label for=\"username\">^ Username</label>
                            <span class=\"input-highlight\"></span>
                        </div>
                        <div class=\"form-group\">
                            <input type=\"password\" class=\"form-control\" name=\"password\" id=\"password\" value=\"" . $data[3] . "\">
                            <label for=\"password\">^ Password</label>
                            <span class=\"input-highlight\"></span>
                        </div>
                    </div>
                </div>
                <div><br><br></div>
                <div class=\"col-md-10 mx-auto mt-3\">
                    <center><button type=\"submit\" class=\"btn-large waves-effect waves-light red\">Submit</button></center>
                </div>
    
                <div class=\"col-md-10 mx-auto mt-3\">
                    <br><br>
                    <center><a href=\"accountmanage.php\" id=\"logoff-button\" class=\"btn-large waves-effect waves-light red\">Back</a></center>
                </div>
            </form>
            <div><br><br></div>
        </div>
    </div>
    ";
}


if (isset($_POST['webid-remove'])){
    $user = $_SESSION['email'];
    $webid = $_POST['webid-remove'];
    remove_website($conn, $user, $webid);

    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'accountmanage.php';
    header("refresh:1;url=http://$host$uri/$extra");
}


include 'templates/static/footer-template.php';
?>
