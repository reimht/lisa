<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 


	echo create_header("BBS2Leer", "","","","","logolisa.svg");

	if( isset($_SESSION["class"]) ){
		$class=$_SESSION["class"];
	}
	else if( isset($_POST["class"]) ){
		$class=$_POST["class"];
	}
	else if( isset($_GET["class"]) ){
		$class=$_GET["class"];
	}
	else{
		echo create_footer("Fehler: Klassenname nicht erkannt<br>"); 
		exit(0);
	}
	$path="../".$_SESSION["settings"]["images_school_classes"]."$class/";



	$classes=read_classes_from_csv("../".$_SESSION["settings"]["classes.csv"]);
	//Pr¸fen ob eine Klasse hinzugef¸gt werden muss
	if( isset($_POST["add_class"]) AND !in_array($class,$classes) ){



		if( isset($_SESSION["settings"]["classes.csv"]) ){
			//Klasse in Vorauswahl-Datei schreiben
			$filename="../".$_SESSION["settings"]["classes.csv"];
			$fp = fopen($filename,"a");
			fwrite($fp, "\n".trim($class)."\n");
			fclose($fp);
		}
		else{
			echo "Fehler: Kann Klasse nicht hinzuf&uuml;gen (classes.csv fehlt)";
		}


	}

?>
			<h3>LiSA - Upload!</h3>


<?php 

//preecho($_POST);
//preecho($_GET);
//preecho($_FILES);


$nr_files=( isset($_FILES['files']['name']) ? count($_FILES['files']['name']) : 0);

for($i=0;$i<$nr_files;$i++){
	if( !isset($_FILES['files']['name'][$i]) ){
		echo "Error index $i filename not set!<br>";
	}
	else 	if( !isset($_FILES['files']['tmp_name'][$i]) ){
		echo "Error index $i tmp_name not set!<br>";
	}
	else 	if( !isset($_FILES['files']['error'][$i]) ){
		echo "Error index $i error not set!<br>";
	}
	else 	if( !isset($_FILES['files']['size'][$i]) ){
		echo "Error index $i size not set!<br>";
	}
	else 	if( $_FILES['files']['error'][$i] != 0 ){
		echo "Error index $i filename ".$_FILES['files']['name'][$i]." error nr ".$_FILES['files']['error'][$i]."!<br>";
	}	
	//Hier sollten alle Informationen vorliegen
	else{
		$filename_tmp=$_FILES['files']['tmp_name'][$i];
		$filename=$_FILES['files']['name'][$i];
		echo "Lade die Datei: $filename ... ";
		if (move_uploaded_file($filename_tmp, $path.$filename)) {
			echo "OK!.<br>\n";
			umask(0002);
			chmod($path.$filename, 0660);
		} else {
			echo "m√∂glicherweise eine Dateiupload-Attacke!<br>\n";
		}
	}
}
?>
	<form action="show_pictures.php" method="post">
		<input type="hidden" name="class" value="<?php echo $class?>">
		<input type="submit" name="Vorschaubilder berechnen" value="Vorschaubilder berechnen">
	</form>

<?php


	$body="<hr><br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>




