<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	$nextpage="index.php";

	test_session(4);

	//preecho($_SESSION);

	$error=0; 
	$error_msg="Fehler!<br>\n";
	$filename=$_SESSION["image_temp_filename"];
	$settings=$_SESSION["settings"];

	//Daten übernehmen;
	$given_name=$_SESSION["given_name"];
	$last_name=$_SESSION["last_name"];
	$birthday=$_SESSION["birth_day"].".".$_SESSION["birth_month"].".".$_SESSION["birth_year"];
	$class=$_SESSION["class"];


	if( isset($_SESSION["tan"] ) ){
	
		//Prüfen ob die TAN vorhanden ist
		$tan_exists=false;  //Grundannahme: TAN existiert nicht
		$filename=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["tan_list.txt"];
		if($handle = @fopen($filename, "r")){
			while(!feof($handle)){
				$zeile = trim( fgets($handle,1024) );
				$zeile=str_replace(" ", "", $zeile);
				if($zeile==$_SESSION["tan"]) $tan_exists=true;
			}
			fclose($handle);
		}
		else{
			echo "Warnung: Konnte TAN-Liste nicht öffnen!<br>";
		}
		
		if(  !$tan_exists  ){
			error_msg("TAN ist unbekannt");
		}

		//Prüfen ob die TAN bereits verwendet wurde
		$tan_used=false;   //Grundannahme: TAN wurde nicht verwender
		$filename=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["tan_used.txt"];
		if($handle = @fopen($filename, "r")){
			$tan_used=false;
			while(!feof($handle)){
				$zeile = trim( fgets($handle,1024) );
				$zeile=str_replace(" ", "", $zeile);
				if($zeile==$_SESSION["tan"]) $tan_used=true;
			}
		}
		else{
			echo "Warnung: Konnte verwendete TANs nicht laden!<br>";
		}
		if(  $tan_used  ){
			echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg",false);
			error_msg("Fehler: TAN wurde bereits verwendet!<br><br>Daten können nicht gespeichert werden!<br><br>Bitte sprechen Sie mit Ihrem Klassenlehrer!");
		}


		$filename=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["tan_used.txt"];
		if($handle = @ fopen($filename, "a")){
			fwrite($handle,$_SESSION["tan"]."\n");
			fclose($handle);
		}
		else{
			echo "Warnung: Konnte verwendete TANs nicht schreiben!<br>";
		}
	}

	//Verzeichnisse erstellen
	create_paths($class, $_SESSION["settings"], $_SESSION["lisa_path"]);
	//Bilder speichern
	$pic_file_name=$last_name."_".$given_name."_".create_birthday_bbs_planung().".".$_SESSION["image_type"];

	$class_path=realpath($_SESSION["lisa_path"]."/".$_SESSION["settings"]["target_image_file_path"]."/".$_SESSION["class"]);
	if($class_path == "" ){
		echo "Error: Folder class_path:'".$_SESSION["lisa_path"]."/".$_SESSION["settings"]["target_image_file_path"]."' not exists!<br>";
	}
	$image_filename_new=$class_path."/".$pic_file_name;


	if(isset($_SESSION["image_temp_modified_filename"])){
		$image_filename = $_SESSION["image_temp_modified_filename"];
		if(file_exists ( $image_filename )){
			$ret=copy_pic($image_filename, $image_filename_new );
			if($ret === false){
				$error_msg="Bilddatei: '$image_filename_new' konte nicht kopiert werden!<br>";
				$error=16;			
			}
		}
		else{
			$error_msg="Bilddatei: $target_filename nicht gefunden!<br>";
			$error=4;
		}
	}

	if($error==0){
		$pagetitle="Daten wurden gespeichert";
		$msg="Bitte verlassen Sie den PC!";
		//$url=$_SESSION["settings"]["domainSubFolder"]."/print/show_one_student.php?img=".urlencode(str_replace($_SESSION["lisa_path"],"",$image_filename_new,1)."&a=".$_SESSION["ablaufdatum"];
		
		//Zeige Link um Ausweis zu drucken (entferne dabei die Verzeichnisangaben aus sicht des Betriebsystems (lisa_path) )
		$url="show_one_student.php?img=".urlencode(str_replace($_SESSION["lisa_path"],"",$image_filename_new))."&a=".$_SESSION["ablaufdatum"];
		$msg.="<br><a href='$url' target='_blanc'><small>Ausweis drucken</small></a><br>";
		$meta_refresh= "<meta http-equiv='refresh' content='10; URL=index.php'>";
		if( $settings["write_csv"]){ //Nur wenn CSV-Dateien erstellt werden sollen 
			if( !isset($_SESSION["data_written"]) ){ //if(true){ //
				write_data_csv($given_name, $last_name, $birthday, $class, $filename, $filename);
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
	
	//$meta_refresh=""; //Nur fürs debugging
	
	echo create_header($_SESSION["settings"]["html_title"], "","",$meta_refresh,$body,"logolisa.svg",false);


	 echo create_footer(""); 

?>