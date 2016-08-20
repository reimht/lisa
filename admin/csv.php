<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	

	//System auf UTF-8 einstellen
	if (function_exists('iconv') && PHP_VERSION_ID < 50600){
		//Older PHP Version
		iconv_set_encoding("input_encoding", $_SESSION["settings"]["character_encoding"]);
		iconv_set_encoding("internal_encoding", $_SESSION["settings"]["character_encoding"]);
		iconv_set_encoding("output_encoding", $_SESSION["settings"]["character_encoding"]);
	}
	elseif (PHP_VERSION_ID >= 50600){
		ini_set("default_charset", $_SESSION["settings"]["character_encoding"]);
	}
	header("Content-Type: text/html; charset=UTF-8");

	$error="";
	
	//Prüfen ob maximal Alter übertragen wurde
	$imgDateNewer=null;
	if( isset($_POST["imgdate"])){
		if(strlen($_POST["imgdate"])>=6){
			$date_tmp=explode(".",$_POST["imgdate"]);
			if(sizeof($date_tmp==3)){
				$imgDateNewer = mktime(0,0,0,$date_tmp[1],$date_tmp[0],$date_tmp[2]);
			}
			
		}
	}	
	
	
	
	//Daten der Schüler einlesen
	ob_start();
	getDir("../".$_SESSION["settings"]["images_matching_lisa"],"MAINDIRECTORY", "lisa");
	$student_data=lisaDirToStudentData($_SESSION["lisa"],$_SESSION["settings"]["images_matching_lisa"]);
	ob_end_clean(); 
	

	
	function lisaDirToStudentData(&$dirData,$imgbasepath){
		$student_data=array();
		if(is_array($dirData)){
			foreach($dirData AS $data){
				$student=array();
				$student["createTime"] = ( isset($data["createTime"]) ? $data["createTime"] : "");
				$student["given_name"] = ( isset($data["givenname"]) ? $data["givenname"] : "");
				$student["last_name"] = ( isset($data["lastname"]) ? $data["lastname"] : "");
				$student["birthday"] = ( (isset($data["birthday_year"]) AND isset($data["birthday_month"]) AND isset($data["birthday_day"]) ) ? $data["birthday_day"].".".$data["birthday_month"].".".$data["birthday_year"] : "");
				$student["class"] = ( isset($data["class"]) ? $data["class"] : "");
				$student["pic_small"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");
				$student["pic_big"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");

				$student_data[$student["class"]][]=$student;
			}
		}
		else{
			$student_data="";
		}
		return $student_data;
	
	}
	




//===============

	if( is_array($student_data)){
		if( sizeof($student_data) < 0 ){
			$error.="Fehler: Konnte ".sizeof($student_data)." Klasse einlesen!<br>\n";
		}
	}
	else{
		$error.="Fehler: Konnte keine Daten einlesen!<br>\n";
	}

	$csv_data="Nachname;Vorname;Gebdatum;Klasse\n";
	foreach( $student_data AS $class ){
		foreach( $class  AS $s){
			if( isset($s["given_name"]) AND isset($s["last_name"]) AND isset($s["birthday"]) AND isset($s["class"]) ){
				//Dateialter prüfen
				if( isset($s["createTime"] )  AND $imgDateNewer!=null ){
					if($s["createTime"]>$imgDateNewer) $csv_data.=$s["last_name"].";".$s["given_name"].";".$s["birthday"].";".$s["class"].";\n";
				}
				else{ //Keine Dateialterprüfung
					$csv_data.=$s["last_name"].";".$s["given_name"].";".$s["birthday"].";".$s["class"].";\n";
				}

			}
		}
	}

	$data_len=strlen($csv_data);
	if($data_len<=40){
		$error.="Konnte CSV-Datei nicht erstellen - Anzahl Zeichen: $data_len!<br>\n";
	}
	else{
		// http headers for text downloads
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=data.csv");
		header("Content-Transfer-Encoding: text");
		header("Content-Length: ".$data_len);
		echo $csv_data;
		ob_end_flush();
		exit(0);
			
	}
	
?>
<html>
	<head>
		<title>Lisa</title>
	</head>
	<body>
		<?php echo $error; ?>
	</body>
</html>




