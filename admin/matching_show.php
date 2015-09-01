<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	iconv_set_encoding("input_encoding", "UTF-8");
	iconv_set_encoding("internal_encoding", "UTF-8");
	iconv_set_encoding("output_encoding", "UTF-8");
	header("Content-Type: text/html; charset=utf-8");
	
	session_start();
	require_once('../functions.php'); 

	echo create_header("BBS2Leer", "","","","","logolisa.svg");	
	
	echo "<br><a href='matching_index.php'> Zur&uuml;ck </a><br>";	
	echo "<h1> Daten aus BBS Plan</h1>";
	



	if(isset($_SESSION["bbsplan"])){
	
		echo "<table border='1'>";
		echo "<tr><td>Nachname</td><td>Vorname</td><td>Geburtstag</td><td>Klasse</td><td>Bilddatei</td><td>Key</td><td>Key</td></tr>";

		foreach($_SESSION["bbsplan"] AS $key => $student){
			if( isset($student["lastname"]) ){
				//echo "<tr><td>".enc_html($student["lastname"])."</td><td>".enc_html($student["givenname"])."</td><td>".enc_html($student["birthday"])."</td><td>".enc_html($student["class"])."</td><td>".enc_html($student["picfile"])."</td><td>$key</td><td>".$student["nr"]."</td></tr>";
				echo "<tr><td>".$student["lastname"]."</td><td>".$student["givenname"]."</td><td>".$student["birthday"]."</td><td>".$student["class"]."</td><td>".$student["picfile"]."</td><td>$key</td><td>".$student["nr"]."</td></tr>";

			}
		 }
		 
		 echo "</table>";
		 echo "<a href='matching_index.php'> Zur&uuml;ck </a><br>";
	}
	else{
		echo "<br>Daten nicht eingelesen!<br>\n";
	}	
	
	echo "<h1> Daten aus Lisa</h1>";
	
	if(isset($_SESSION["lisa"])){		
		

		echo "<table border='1'>";
		echo "<tr><td>Nachname</td><td>Vorname</td><td>Geburtstag</td><td>Klasse</td><td>Bilddatei</td><td>Key</td><td>Key</td></tr>";
		foreach($_SESSION["lisa"] AS $key => $student){
			if( isset($student["lastname"]) ){
				echo "<tr><td>".$student["lastname"]."</td><td>".$student["givenname"]."</td><td>".$student["birthday"]."</td><td>".$student["class"]."</td><td>".$student["picfile"]."</td><td>$key</td><td>".$student["nr"]."</td></tr>";		}
		 }

		echo "</table>";
	
	}
	else{
		echo "<br>Daten nicht eingelesen!<br>\n";
	}
	
	echo "<br><a href='matching_index.php'> Zur&uuml;ck </a><br>";	
	
?>