<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin"); //area = false => auto = folder name
	
	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");		

	echo "<h3>LiSA - Matching!</h3>";
	echo "<br><a href='matching_index.php'> Zur&uuml;ck </a><br>";	

	if( !isset($_POST["source"])){
		$error="Fehler: Keine Quelle angegeben<br>";
		echo create_footer("$error"); 
		exit(0);
	}
	else if( $_POST["source"]=="lisa" ){
		$target="lisa";
		$dirSource=$_SESSION[$target."_source"];
	}
	else if( $_POST["source"]=="bbsplan" ){
		$target="bbsplan";
		$dirSource=$_SESSION[$target."_source"];
	}
	else{
		$error="Fehler: Unbekannte Quelle angegeben<br>";
		echo create_footer("$error"); 
		exit(0);
	}

	if( !file_exists($dirSource) ){
		$error="Das Verzeichnis $dirSource ist nicht vorhanden oder kein Zugriff m&ouml;glich<br>";
		echo create_footer("$error"); 
		exit(0);
	}

	echo "<br>Lese das Verzeichnis $dirSource ein!<br>";

	$_SESSION[$target]=array();
	$studentnr=1;
	

	getDir($dirSource,"MAINDIRECTORY", $target);

	//Sort DATA
	function compare_picfile($a, $b){
		return strcmp( strtolower($a["picfile"]),strtolower($b["picfile"]));
	}

	uasort($_SESSION[$target], "compare_picfile");  

	$_SESSION[$target."_size"]=sizeof($_SESSION[$target]);

	echo "<br>Gefundene Eintr&auml;ge: ".$_SESSION[$target."_size"]."<br>";

	//preecho($_SESSION[$target]["sortbygeb"]);

	echo "<br><a href='matching_index.php'> Zur&uuml;ck </a><br>";
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>

