<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 

	echo create_header("BBS2Leer", "","","","","logolisa.svg");	
	
	echo "<h1> Erstelle Zip-Datei </h1>";
	echo "<br><a href='matching_index.php'> Zurück </a><br>";	


	if( isset($_POST["encoding"]) ){
		if($_POST["encoding"]==""){
			$encoding=null;
		}
		else{
			$encoding=trim($_POST["encoding"]);
			$_SESSION["zeichensatz"]=$encoding;
		}
	}	

	//Überprüfe ob event vorhandene Ziel-Datei gelöscht werden soll.
	if( isset($_POST["delete_zip_file"]) ){
		echo "<h2> Lösche alte Zip-Datei </h2>";
		$filename_zip = realpath($_SESSION["bbsplan_source"])."/pics.zip";
		if (file_exists($filename_zip )) unlink($filename_zip );		//Löschen
		if (file_exists($filename_zip )) echo "Warnung: Konnte Datei $filename_zip nicht löschen<br>";   //Kontrolle
	}


	echo "<h2> Kopiere Bilddateien </h2>";

	//Zip-Datei vorbereiten
	$zip = new ZipArchive();
	$filename_zip = realpath($_SESSION["bbsplan_source"])."/pics.zip";
	if ($zip->open($filename_zip, ZIPARCHIVE::CREATE)!==TRUE) {
		exit("cannot open <$filename_zip>\n");
	}

	//Durchlaufe alle Übereinstimmungen
	foreach($_SESSION["matches"] AS $key => $match){

		if( isset($match["lisa"]["picfile_org"]) && isset($match["bbsplan"]["picfile_org"] ) && isset($match["checked"] )){
			//Path zu Bilddateien bestimmen

			$bbsplan_pic=realpath($_SESSION["bbsplan_source"]."/".$match["bbsplan"]["picfile_org"]);

			$lisa_pic=realpath($_SESSION["lisa_source"]."/".$match["lisa"]["picfile_org"]);

			if( !isset($_SESSION["matches"][$key]["copy_done"]) ) $_SESSION["matches"][$key]["copy_done"]=0;

			if($match["checked"]==1 ){ //&& $_SESSION["matches"][$key]["copy_done"]==0){
				if( !file_exists($bbsplan_pic) ){
					echo "Fehler kann BBSPlan Datei: $bbsplan_pic nicht finden<br>\n";
				}
				else if( !file_exists($lisa_pic) ){
					echo "Fehler kann LiSA Datei: $lisa_pic nicht finden<br>\n";
				}
				else{
					//Kopiere Dateien
					if(!@copy($lisa_pic,$bbsplan_pic)){
						$errors= error_get_last();
						echo "Fehler kann Datei $lisa_pic nicht nach $bbsplan_pic kopieren. Fehlertyp:".$errors['type']. " ";
						echo "Fehlermeldung:".$errors['message']."<br>\n";
						$_SESSION["matches"][$key]["copy_done"]=0;
					}
					else{
						$_SESSION["matches"][$key]["copy_done"]=1;
						$newfilename=$match["bbsplan"]["class"]."/".basename($bbsplan_pic);
						//Damit die Datei unter Windows funktioniert.
						if( $encoding!=null ) $newfilename=convert_string($newfilename, null , $encoding);
						$zip->addFile($bbsplan_pic,$newfilename );
					}

				}
			}
		}
		else{
			echo "<!-- Fehler: Bildfelder fehlen";
			preecho($match);
			echo "-->\n";			
		}
	 }

	echo "<h2> Infos Zip-Datei </h2>";
	echo "Anzahl Dateien: " . $zip->numFiles . "<br>";
	echo "Letzte Meldung (sollte 0 sein, 23=Dateien in Zip-Datei &uuml;berschrieben.):" . $zip->status . "<br>";
	$zip->close();
	echo "Encoding der Dateinamen: $encoding<br>";


	if (file_exists($filename_zip )) {
		//Gebe Download Link aus
		$link=$_SESSION["bbsplan_source"]."/pics.zip";
		echo "<a href='$link'>  Download Zip-Datei </a><br>";

		//Erzeuge Alternativen Download Link
		$i=strlen($_SERVER["PHP_SELF"]);
		$j=$i-strlen(basename($_SERVER["PHP_SELF"]))-1;
		$pathlink=substr ( $filename_zip , 0 , $j );
		$link="http://".$_SERVER["SERVER_NAME"]."/".str_replace($pathlink, "", $filename_zip);
		echo "<a href='$link'> Alternativer Download Zip-Datei </a>";
	}
	else{
		echo "Warnung: Es wurde keine ZIP-Datei erstellt<br>";
	}



?>