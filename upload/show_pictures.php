<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	session_start();
	require_once('../functions.php'); 
	if(!isset($_SESSION["settings"])){
		require_once('../settings.php'); 
		$_SESSION["settings"]=$settings;
	}

	//Start Page ($tilte, $style,$script,$meta,$body)
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
	$pictures=read_pictures_in_dir($path);
	$nr_pics=sizeof($pictures);
	
	echo"
	<form action='index.php' method='post'>
		<input type='submit' name='start' value='fertigstellen'>
	</form>";
	
	echo "<table><tr>";
	if($nr_pics>0){ 
		$i=1;
		if( !file_exists( $path."thumb" ) ) {
			//Event. Verzeichnis für Voransicht erstellen
			umask(0002);
			mkdir($path."thumb", 0770); 
		}
		foreach($pictures AS $pic){
			$filename=$path.$pic;
			$thumbfilename=$path."thumb".DIRECTORY_SEPARATOR.$pic;
			if(  !file_exists ( $thumbfilename )  ) create_thumb($filename,$thumbfilename); //Event.  Voransicht erstellen
				echo "	<td><img src='$thumbfilename'></td>";
				if( ($i%3)==0 ) echo "\n</tr>\n<tr>";
				$i++;
			}
	}
	echo "</tr></table>";
	echo"
	<form action='index.php' method='post'>
		<input type='submit' name='fertigstellen' value='fertigstellen'>
	</form>";			
	echo create_footer("");

?>





