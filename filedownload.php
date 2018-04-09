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
			<h3 class="black-text">View/Download File: <?php echo $_SESSION['email'] ?></h3>
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
            //Force using us-east due to free account limitions, avoid the fees!
            ?><a href=https://s3.us-east-2.amazonaws.com/sofe4630-passwordmanager/<?php echo $keyname?> class="collection-item"><?php $test = explode("/",$keyname); echo $test[1];?></a><?php
            $filelist[] = $object['Key'];
        }//if
    }//foreach            
    ?>
			<center>
		</div>
	</div>
	<br><br>
	<a href="filemanage.php" id="logoff-button" class="btn-large waves-effect waves-light red">Back</a>
	<br><br><br><br>
	<?php
	
} catch (S3Exception $e) {
    //Exception from Amazon A3
    echo $e->getMessage()."\n";
}//try-catch

if($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['deletefile']))) {
    //Attempt delete file to Amazon A3
	$filex = $activeuser . '/' . $_GET['deletefile'];
    echo $filex;
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
    header("Location:filedownload.php");
}
include "templates/static/footer-template.php";
?>