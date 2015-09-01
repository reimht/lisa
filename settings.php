<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	error_reporting(E_ALL);

	$settings=array();

	$settings["debug"]=3;										// Auflösung der Ziel-Datei	
	$settings["classes.csv"]="config/classes.csv"; 						// "classes.csv" - Datei in der die verfügbaren Klassen eingetragen sind
	$settings["tan_config.txt"]="config/tan_config.txt"; 				// "classes.csv" - Datei in der Einstellungen für die TAN-Erzeugung  eingetragen sind
	$settings["tan_list.txt"]="config/tan_list.txt"; 						// "classes.csv" - Datei in der die verfügbaren TANs eingetragen sind
	$settings["tan_used.txt"]="config/tan_used.txt"; 					// "classes.csv" - Datei in der die verwendeten TANs eingetragen sind

	$settings["write_csv"]=false; 									// false/true - aktiviert das Erstellen von CSV-Dateien mit den Schülernamen
	$settings["data.csv"]="data.csv"; 								// "data.csv" - Datei in der die Schülerdaten eingetragen werden
	$settings["images_matching"]="images/matching/"; 				// "school_matching/" or "/var/www/images/school_matching" - Verzeichnis für Vergleichsdaten (BBS Plan Bilder)
	$settings["images_school_classes"]="images/classes_upload/"; 		// "school_class/" or "/var/www/images/school_class/" - Verzeichnis für Klassenfotos
	$settings["temp_image_file_path"]="images/temp/"; 				// "images_temp/" or "/tmp/" - Verzeichnis in dem Bilder zwischen gespeichert werden
	$settings["target_image_file_path"]="images/classes/";				// "images/" or "/var/www/images/"
	$settings["images_matching_lisa"]="images/classes/small/";			// "images/" or "/var/www/images/"
	$settings["target_image_type"]="JPEG"; 							// "JPEG" or "PNG" - Dateityp der resultierenden Bilder
	$settings["target_filesize"]=15000;								// "200000" - Maximale Bildgröße in Byte für die kleine Ansicht
	$settings["picture_aspectRatio"]="3/4"; 							// "1/2" or "3/4" or "1/1" or ... - Seitenverhältnis der Bildauswahl/Crop
	$settings["picture_min_x"]="10";		
	$settings["picture_min_y"]="200";

	$settings["picture_target_width"]="600";			// Auflösung der Ziel-Datei
	$settings["picture_target_height"]="800";			// Auflösung der Ziel-Datei
	
	$settings["show_picture_max_width"]="300";			// Umrechung für die Anzeige bei "rotage" und "crop"
	$settings["show_picture_max_height"]="400";			// Umrechung für die Anzeige bei "rotage" und "crop"
	
	$settings["upload_file_max_width"]="4096";
	$settings["upload_file_max_height"]="4096";
	$settings["tan_active"]="no";
	$settings["ip_notan"]=array("10.231.0.51","10.96.8.1","10.96.8.2","10.0.2.2");// 
/*,
					"10.12.0.10","10.22.0.10","10.96.8.1","10.96.8.2",
					"10.5.101.0","10.5.101.111","10.5.101.2","10.5.101.3","10.5.101.4","10.5.101.5","10.5.101.6","10.5.101.7","10.5.101.8","10.5.101.9",
					"10.5.101.10","10.5.101.11","10.5.101.12","10.5.101.13","10.5.101.14","10.5.101.15","10.5.101.16","10.5.101.17","10.5.101.18","10.5.101.19",
					"10.5.101.20","10.5.101.21","10.5.101.22","10.5.101.23","10.5.101.24","10.5.101.25","10.5.101.26","10.5.101.27","10.5.101.28","10.5.101.29",
					"10.5.101.30","10.5.101.31","10.5.101.32","10.5.101.33","10.5.101.34","10.5.101.35","10.5.101.36","10.5.101.37","10.5.101.38","10.5.101.39");
*/
	$settings["demo_mode"]=true;
?>
