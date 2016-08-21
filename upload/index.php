<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("upload");	

	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");	



	
?>
			<h3>LiSA - Upload!</h3>
			<p>
				<form action='select_class.php' method='POST' >
					<input type='submit' value='Bilder einer Klasse hochladen'>
				</form>
			</p>	

<?php 
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>





