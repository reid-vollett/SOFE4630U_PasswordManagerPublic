<?php
//Initialize configuration
require_once "inc/config.php";

//Retrieve session information for user in $_SESSION
session_start();
$activeuser = $_SESSION['email'];

//Wait for POST request, uploadfile exists, valid uploadfile and upload
if(($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_FILES['uploadfile'])) && ($_FILES['uploadfile']['error'] == UPLOAD_ERR_OK) && (is_uploaded_file($_FILES['uploadfile']['tmp_name']))) {	
    //Extract uploaded file name
	$filename = $_FILES['uploadfile']['name'];
	
    $file_ext = pathinfo($filename);
    
    //Append user user session name to generate subdirectory
    $keyname = $activeuser . '/' . $filename;
	
    //Attempt an upload to Amazon A3
	try {
	    //putObject upload to A3
        $result = $s3->putObject(array(
	        //A3 bucket
            'Bucket' => $bucket,
	        //FileName
            'Key'    => $keyname,
	        //Open-Read
            'Body'   => fopen($_FILES['uploadfile']['tmp_name'], 'rb'),
	        //Public Accessible in bucket
            'ACL'    => 'public-read'
	    ));        
	} catch (S3Exception $e) {
        //Exception from Amazon A3
	    echo $e->getMessage()."\n";
	}
        
    //Upload finished, return to logged-in homepage
    header("Location:filemanage.php");
}
//Until POST request is seen, generate upload form
else {
    include 'templates/user/user-header-template.php';  
	?>
	<div class="section"></div>
	<main>
		<center>
			<h3 class="black-text">Upload File: <?php echo $_SESSION['email'] ?></h3>
			<div class="section"></div>
			<div class="container">
				<div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">	
					<div class="row">
						<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
							<input name="uploadfile" type="file" class="btn-large waves-effect waves-light black lighten-1">
							<input type="submit" class="btn-large btn-lg red" value="Upload">
						</form>		     
					</div>
				</div>
				<div class="section"></div>
				<a href="filemanage.php" id="logoff-button" class="btn-large waves-effect waves-light red">Back</a>
				<div class="section"></div>
				<div class="section"></div>
			</div>
    </center>
	<?php
    include 'templates/static/footer-template.php';
};	

/**
<link href="css/fileupload.css" type="text/css" rel="stylesheet" media="screen,projection"/>

			<form action="fileupload.php" method="POST">
				<input type="file" multiple>
				<p>Drag your files here or click in this area.</p>
				<button type="submit">Upload</button>
			</form>	
        </div>
<script type="application/javascript" src="fileupload.js" /script>
**/
?>