<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start(); 
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	$nextpage="index.php";
	require_once('functions.php'); 
	test_session(4);

	//preecho($_SESSION);

	$error=0; 
	$error_msg="Fehler!<br>\n";
	$filename=$_SESSION["filename"];
	$settings=$_SESSION["settings"];

	//Daten übernehmen;
	$given_name=$_SESSION["given_name"];
	$last_name=$_SESSION["last_name"];
	$birthday=$_SESSION["birth_day"].".".$_SESSION["birth_month"].".".$_SESSION["birth_year"];
	$class=$_SESSION["class"];


	if( isset($_SESSION["tan"] ) ){

		//Prüfen ob die TAN vorhanden ist
		$filename=$_SESSION["settings"]["tan_list.txt"];
		$handle = fopen($filename, "r");
		$tan_exists=false;
		while(!feof($handle)){
			$zeile = trim( fgets($handle,1024) );
			$zeile=str_replace(" ", "", $zeile);
			if($zeile==$_SESSION["tan"]) $tan_exists=true;
		}
		fclose($handle);
		if(  !$tan_exists  ){
			error_msg("TAN ist unbekannt");
		}

		//Prüfen ob die TAN bereits verwendet wurde
		$filename=$_SESSION["settings"]["tan_used.txt"];
		$handle = fopen($filename, "r");
		$tan_used=false;
		while(!feof($handle)){
			$zeile = trim( fgets($handle,1024) );
			$zeile=str_replace(" ", "", $zeile);
			if($zeile==$_SESSION["tan"]) $tan_used=true;
		}
		if(  $tan_used  ){
			echo create_header("BBS2Leer", "","","","");
			error_msg("Fehler: TAN wurde bereits verwendet!<br><br>Daten können nicht gespeichert werden!<br><br>Bitte sprechen Sie mit Ihrem Klassenlehrer!");
		}


		$filename=$_SESSION["settings"]["tan_used.txt"];
		$handle = fopen($filename, "a");
		fwrite($handle,$_SESSION["tan"]."\n");
		fclose($handle);
	}

	//Verzeichnisse erstellen
	create_paths($class);
	//Bilder speichern
	$pic_file_name=$last_name."_".$given_name."_".create_birthday_bbs_planung();

	if(isset($_SESSION["target_filename_small"])){
		$target_filename_small=$_SESSION["target_filename_small"];
		if(file_exists ( $target_filename_small )){
			$filename_small=copy_pic($target_filename_small, $pic_file_name, "SMALL", $class, $debug );
			if($filename_small==""){
				$error_msg="Bilddatei: $target_filename_small konte nicht kopiert werden!<br>";
				$error=8;			
			}
		}
		else{
			$error_msg="Bilddatei: $target_filename_small nicht gefunden!<br>";
			$error=2;
		}
	}
	if(isset($_SESSION["target_filename_big"])){
		$target_filename_big=$_SESSION["target_filename_big"];
		if(file_exists ( $target_filename_big )){
			$filename_big=copy_pic($target_filename_big, $pic_file_name, "BIG", $class, $debug );
			if($filename_big==""){
				$error_msg="Bilddatei: $target_filename_big konte nicht kopiert werden!<br>";
				$error=16;			
			}
		}
		else{
			$error_msg="Bilddatei: $target_filename_big nicht gefunden!<br>";
			$error=4;
		}
	}

	if($error==0){
		$pagetitle="Daten wurden gespeichert";
		$msg="Bitte verlassen Sie den PC!";
		$url="admin/show_one_student.php?img=".urlencode("../".$filename_small);
		$msg.="<br><a href='$url'><small>Ausweis drucken</small></a><br>";
		$meta_refresh= "<meta http-equiv='refresh' content='10; URL=index.php'>";
		if( $settings["write_csv"]){ //Nur wenn CSV-Dateien erstellt werden sollen 
			if( !isset($_SESSION["data_written"]) ){ //if(true){ //
				write_data_csv($given_name, $last_name, $birthday, $class, $filename_small, $filename_big);
				write_bbsplanung_data_csv($given_name, $last_name, create_birthday_bbs_planung(), $class);
			}
		}
//		$_SESSION=array();
//		$_SESSION["data_written"]="1";
	}
	else{
		$pagetitle="Fehler";
		$meta_refresh="";
		$msg =  $error_msg;
	}	
	


	//Start Page ($tilte, $style,$script,$meta,$body)
	

	
	$body="<h3> $pagetitle </h3> $msg \n<br><br>\n<a href='index.php'>Neue Eingabe starten</a><br>\n";
	
	echo create_header("BBS2Leer", "","",$meta_refresh,$body);


	 echo create_footer(""); 

?>




