<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");		
	if(!isset($_POST["folder"])){
		echo "Fehlende Angaben <br>\n";
	}
	else if( !isset($_SESSION["settings"][$_POST["folder"]]) ){
			echo "Ung&uuml;ltige Angaben<br>\n";	
	}
	else if($path=realpath($_SESSION["lisa_path"]."/".$_SESSION["settings"][$_POST["folder"]])){
		//Grober Test nicht ab "/" löschen
		if(strlen($path)>5){
			echo "Lösche den Inhalt des Verzeichnisses '$path' - (".$_POST["folder"].")!<br><br>";  
			remove_files($path,3);
		}
		else{
			echo "Fehler kann Pfad zu temporären Dateien nicht ermitteln ('$path')!<br><br>";  
		}
	}
	else{
		echo "Fehler kann Pfad zu den Dateien nicht ermitteln ";
	}

?>



				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php echo create_footer(""); ?>
