<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	session_start();
	session_regenerate_id(true); 
	require_once('../functions.php'); 
	$_SESSION=array();


	
	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");		
	
	$filename="";
	if(isset($_POST["file"])){
		if( $_POST["file"] == "tan_config_file" ){
			$msg="TAN Konfigurationsdatei";
			$filename="../".$_SESSION["settings"]["tan_config.txt"];
		}
		else if( $_POST["file"] == "tan_list" ) {
			$msg="TAN Liste";
			$filename="../".$_SESSION["settings"]["tan_list.txt"];
		}
		else if( $_POST["file"] == "tan_used" ) {
			$msg="Bereits verwendete TANs";
			$filename="../".$_SESSION["settings"]["tan_used.txt"];
		}
		else if( $_POST["file"] == "classes" ) {
			$msg="Vorgegebene Klassen";
			$filename="../".$_SESSION["settings"]["classes.csv"];
		}
		else{
			echo "unbekannte Datei";
			exit(0);
		}
	}
	else{
		echo "unbekannte Datei";
		exit(0);
	}

	if(isset($_POST["save"]) and isset($_POST["config"]) ){
		$handle = fopen($filename, "w");
		$content = fwrite($handle, $_POST["config"]);
		fclose($handle);
	}

	//Lese Datei ein
	$handle = fopen($filename, "r");
	$fsize=filesize($filename);
	if($fsize>0) $content = fread($handle, $fsize);
	else $content="";
	fclose($handle);


?>
			<h3>LiSA - Admin!</h3>
			<?php echo "$msg<br>"; ?>
			<p>
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
				<form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST' >
					<input type='submit' name='save' value='speichern'>
					<input type="hidden" name="file" value="<?php echo $_POST["file"] ?>">
					<textarea name='config'  cols='100' rows='50'><?php echo $content; ?></textarea><br>
					<input type='submit' name='save' value='speichern'>
				</form>
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php echo create_footer(""); ?>
