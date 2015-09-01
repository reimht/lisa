<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	session_regenerate_id(true); 
	require_once('../functions.php'); 
	$_SESSION=array();
	
	if(!isset($_SESSION["settings"])){
		require_once('../settings.php'); 
		$_SESSION["settings"]=$settings;
	}

	echo create_header("BBS2Leer", "","","","","logolisa.svg");	



	
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





