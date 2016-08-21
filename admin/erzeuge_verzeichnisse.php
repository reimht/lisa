<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	


	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");	

	echo "<h3>LiSA - Admin!</h3>";
	
	echo "Erzeuge Verzeichnisse<br><br>";
	

	if(isset($_SESSION["lisa_path"])){
		$path=realpath($_SESSION["lisa_path"]);
		if($path!=""){
			create_data_paths($path);
		}
		else{
			echo "Fehler: LiSA Pfad existiert nicht<br>";
		}
	}
	else{
		echo "Fehler: Konnte LiSA Pfad nicht ermitteln<br>";
	} 

 
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





