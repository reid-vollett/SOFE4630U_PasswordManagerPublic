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
        <h1 class=\"header center white-text\">Manage Accounts</h1>
        <div class=\"row center\">
          <h5 class=\"header col s12 black-text\"></h5>
        </div>";

$webids = get_webids($conn, $_SESSION['email']);

for ($i=0; $i<count($webids); $i++){

    $data = get_login($conn, $_SESSION['email'], $webids[$i]);

        echo"
            <div class=\"row center\">
                <form action = \"editaccount.php\" method = \"post\">
                    <button class=\"btn-large waves-effect waves-light red\" type=\"submit\" value=\"". ($webids[$i]) ."\" name=\"webid\">
                        Site: ". $data[1] ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login: " . $data[2] . " 
                    </button>
                 </form>
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
