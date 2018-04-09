<?php
//Initialize configuration
require_once "inc/config.php";

include "templates/user/user-header-template.php";

//Retrieve session information for user in $_SESSION
session_start();
$activeuser = $_SESSION['email'];

//Attempt to download from Amazon A3
try {
    //Retrieve list of all available files uploaded to Amazon S3 bucket
    $objects = $s3->getIterator('ListObjects', array(
        "Bucket" => $bucket,
    ));
    
    //Generate list of files available for download
    ?>
	<main>
		<center>
			<h3 class="black-text">Files</h3>
			<br>
    <?php
	
    //Filelist to contain all files for offline query
    $filelist = array();
    
    //Create list using hovering list class
    ?><div class="collection"><?php
	
    //Iterate through each file in list
    foreach ($objects as $object) {
        //Filename
        $okeyname = $object['Key'];
        
        $keyname = preg_replace('/\s+/', '+', $okeyname);

        $file_ext = pathinfo($keyname);
        #echo ($file_ext['extension']);
        //Narrow down list, display only files belonging to session user
        if ((strpos($keyname, $activeuser) !== false)) {
            $filelist[] = $object['Key'];
        }//if
    }//foreach            
    ?>
			<center>
		</div>
	</div>
	<?php
	
} catch (S3Exception $e) {
    //Exception from Amazon A3
    echo $e->getMessage()."\n";
}//try-catch

if($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['deletefile']))) {
    //Attempt delete file to Amazon A3
	echo $_POST['deletefile'];
	
	$filex = $activeuser . '/' . $_POST['deletefile'];
    //echo $filex;
	
    try {
	    //deleteObject A3
        $result = $s3->deleteObject(array(
	        //A3 bucket
            'Bucket' => $bucket,
	        //FileName
            'Key'    => $filex
	    ));        
	} catch (S3Exception $e) {
        //Exception from Amazon A3
	    echo $e->getMessage()."\n";
	}//try-catch
	header("Location:filedelete.php");
} else {

foreach ($filelist as &$value){
    $data = explode("/", $value);
    $value = $data[1];
}
    ?>
    <br>
    <div class="row">
          <div class="row">
          <form class="center-align" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <fieldset>
                <label for="deletefile" class="control-label">File Name:</label>
                <select id="deletefile" name="deletefile" class="browser-default">
                    <?php
                        for ($x = 0; $x < count($filelist); $x++)
                            echo'<option value=' . urlencode($filelist[$x]) . '>' . $filelist[$x] . '</option>'
                    ?>
                </select>
                <br><button type="submit" id="deletefiles" name="deletefiles" class="btn-large waves-effect waves-light red">Delete File</button>
            </fieldset>
            </form>
          </div>
		  <br><br><br><a href="filemanage.php" id="logoff-button" class="btn-large waves-effect waves-light red">Back</a>
    </div>
    <?php
    }

include "templates/static/footer-template.php";
?>