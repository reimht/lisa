<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	session_start();
	session_regenerate_id(true); 
	require_once('../functions.php'); 
	$_SESSION=array();

	
	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");		
	


?>
			<h3>LiSA - Admin!</h3>
			
			<p>
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form><br>

<?php 


	if (extension_loaded('gd')) {
		echo "OK: Extension 'gd' ist geladen<br>\n";
	}
	else{
		echo "Error: Extension 'gd' ist nicht verfügbar<br>\n";
	}
	if( !function_exists("imagecreatefromjpeg")){
		echo "Error: Funktion 'imagecreatefromjpeg' ist nicht verfügbar<br><br>\n";
	}	
	
	echo "<br><br>\n";
	phpinfo();


?>	

				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php echo create_footer(""); ?>
