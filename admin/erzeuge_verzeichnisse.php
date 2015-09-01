<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();


	require_once('../functions.php'); 

	$settings=$_SESSION["settings"];


	echo create_header("BBS2Leer", "","","","","logolisa.svg");	

	echo "<h3>LiSA - Admin!</h3>";
	
	echo "Erzeuge Verzeichnisse<br><br>";
	
	create_data_paths("../");

?>
			
			<p>
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php 
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>





