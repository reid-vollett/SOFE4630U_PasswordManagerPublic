<?php
session_start();

include 'templates/user/user-header-template.php';


require_once("inc/config.php");
require_once("inc/db_query.php");

$conn = mysqli_connect($db_con, $db_user, $db_pass, $db_name);

echo "  <div id=\"index-banner\" class=\"parallax-container\">
    <div class=\"section no-pad-bot\">
      <div class=\"container\">
        <br><br>
        <h1 class=\"header center white-text\">Accounts</h1>
        <div class=\"row center\">
          <h5 class=\"header col s12 white-text\"></h5>
        </div>";

$webids = get_webids($conn, $_SESSION['email']);

for ($i=0; $i<count($webids); $i++){

    $data = get_login($conn, $_SESSION['email'], ($webids[$i]));

    echo"
            <div class=\"row center\">
            <h5 class=\"header center white-text\">Site: " . $data[0] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: " . $data[2] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: " . $data[3] . "</h5>
             </div>";
};

echo "
        <br><br>
		<center><a href=\"accountmanage.php\" id=\"logoff-button\" class=\"btn-large waves-effect waves-light red\">Back</a></center>
      </div>
    </div>
    <div class=\"parallax\"><img src=\"img/background4.jpg\"></div>
  </div>";


include 'templates/static/footer-template.php';
?>