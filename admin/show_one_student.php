<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	//=== Nichts cachen - Seite immer neu aufrufen
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store');
	header('Pragma: no-cache');
	//System auf UTF-8 einstellen
	iconv_set_encoding("input_encoding", "UTF-8");
	iconv_set_encoding("internal_encoding", "UTF-8");
	iconv_set_encoding("output_encoding", "UTF-8");
	header("Content-Type: text/html; charset=UTF-8");
	
	require_once('../functions.php'); 
?>
<!DOCTYPE html>
<html lang='en'>
	<head>
	<title>BBS2Leer</title>
	<style type="text/css">
		@page { size:8.56cm 5.39cm; margin:0.1cm 0.1cm 0.1cm 0.1cm; }
	</style>


    <style>
	/*Höhe der ersten Zeilen*/
	.topline
		{
			height:0.3cm;
			margin:0cm;
			padding:0cm;
			font-size:0.4cm;
			/*Bild oben bündig*/
			vertical-align: top;
			/*horizontal zentriert*/
			margin-left: auto;
			margin-right: auto;
			/*Gesamt Breite des Ausweises max 8.56*/
			width:8.4cm;
		}
	/*Größe der Zelle in dem das Bild ist*/
	.imgpass_td
		{
			width:3.5cm;
			height:4cm;
			/*Bild oben bündig*/
			/*vertical-align: top;	*/

		}
	/*Größe des Bildes*/
	.imgpass
		{
			display: block;
			max-width:3.1cm;
			max-height:4cm;
  			margin-right:0.3cm;
			/*horizontal zentriert*/
			margin-left: auto;

					
		} 
	.pass_field_description
		{
			font-size:0.2cm;
			padding-left:0cm;
			height:0.27cm;
			background: white;
			//width:4cm;
			vertical-align: bottom;
		} 
	.pass_field_value
		{
			font-size:0.4cm;
			padding-left:0.2cm;
			height:0.5cm;
			background: white;
			border-bottom:2px solid #000000; //Line unterhalb
		} 
	.long_entry
		{
			font-size:0.3cm;
		} 
	.very_long_entry
		{
			font-size:0.2cm;
		} 
    </style>

	
</head>
<body> 

<?php

//Breite eines Stings abschätzen
function strwidth($string){
    
    $tl=0;
    
    $string_laenge = strlen($string);

    //Abstand

    for($i=0; $i < $string_laenge; $i++){
        $string_x = $string{$i};

        if($string_x == " "){
            $tl += 0.3;
        } elseif($string_x == "0"){
            $tl += 0.5;
        } elseif($string_x == "1"){
            $tl += 0.3;
        } elseif($string_x == "2"){
            $tl += 0.5;
        } elseif($string_x == "3"){
            $tl += 0.5;
        } elseif($string_x == "4"){
            $tl += 0.5;
        } elseif($string_x == "5"){
            $tl += 0.5;
        } elseif($string_x == "6"){
            $tl += 0.5;
        } elseif($string_x == "7"){
            $tl += 0.5;
        } elseif($string_x == "8"){
            $tl += 0.5;
        } elseif($string_x == "9"){
            $tl += 0.5;
        } elseif($string_x == "a"){
            $tl += 0.5;
        } elseif($string_x == "b"){
            $tl += 0.5;
        } elseif($string_x == "c"){
            $tl += 0.5;
        } elseif($string_x == "d"){
            $tl += 0.5;
        } elseif($string_x == "e"){
            $tl += 0.48;
        } elseif($string_x == "f"){
            $tl += 0.35;
        } elseif($string_x == "g"){
            $tl += 0.5;
        } elseif($string_x == "h"){
            $tl += 0.54; 
        } elseif($string_x == "i"){
            $tl += 0.3;
        } elseif($string_x == "j"){
            $tl += 0.3;
        } elseif($string_x == "k"){
            $tl += 0.54;
        } elseif($string_x == "l"){
            $tl += 0.3;
        } elseif($string_x == "m"){
            $tl += 0.82;
        } elseif($string_x == "n"){
            $tl += 0.55;
        } elseif($string_x == "o"){
            $tl += 0.54;
        } elseif($string_x == "p"){
            $tl += 0.49;
        } elseif($string_x == "q"){
            $tl += 0.53;
        } elseif($string_x == "r"){
            $tl += 0.3;
        } elseif($string_x == "s"){
            $tl += 0.4;
        } elseif($string_x == "t"){
            $tl += 0.28;
        } elseif($string_x == "u"){
            $tl += 0.41;
        } elseif($string_x == "v"){
            $tl += 0.5;
        } elseif($string_x == "w"){
            $tl += 0.79;
        } elseif($string_x == "x"){
            $tl += 0.49;
        } elseif($string_x == "y"){
            $tl += 0.5;
        } elseif($string_x == "z"){
            $tl += 0.5;
        } elseif($string_x == "ä"){
            $tl += 0.48;
        } elseif($string_x == "ö"){
            $tl += 0.5;
        } elseif($string_x == "ü"){
            $tl += 0.47;
        } elseif($string_x == "A"){
            $tl += 0.71;
        } elseif($string_x == "B"){
            $tl += 0.6;
        } elseif($string_x == "C"){
            $tl += 0.7;
        } elseif($string_x == "D"){
            $tl += 0.62;
        } elseif($string_x == "E"){
            $tl += 0.5;
        } elseif($string_x == "F"){
            $tl += 0.5;
        } elseif($string_x == "G"){
            $tl += 0.7;
        } elseif($string_x == "H"){
             $tl += 0.6;
        } elseif($string_x == "I"){
            $tl += 0.35;
        } elseif($string_x == "J"){
            $tl += 0.42;
        } elseif($string_x == "K"){
            $tl += 0.6;
        } elseif($string_x == "L"){
            $tl += 0.48;
        } elseif($string_x == "M"){
            $tl += 0.76;
        } elseif($string_x == "N"){
            $tl += 0.6;
        } elseif($string_x == "O"){
            $tl += 0.71;
        } elseif($string_x == "P"){
            $tl += 0.55;
        } elseif($string_x == "Q"){
            $tl += 0.8;
        } elseif($string_x == "R"){
            $tl += 0.7;
        } elseif($string_x == "S"){
            $tl += 0.6;
        } elseif($string_x == "T"){
            $tl += 0.6;
        } elseif($string_x == "U"){
            $tl += 0.6;
        } elseif($string_x == "V"){
            $tl += 0.63;
        } elseif($string_x == "W"){
            $tl += 1;
        } elseif($string_x == "X"){
            $tl += 7;
        } elseif($string_x == "Y"){
            $tl += 0.65;
        } elseif($string_x == "Z"){
            $tl += 0.62;
        } elseif($string_x == "Ä"){
            $tl += 0.7;
        } elseif($string_x == "Ö"){
            $tl += 0.71;
        } elseif($string_x == "Ü"){
            $tl += 0.6;
        } elseif($string_x == "ß"){
            $tl += 0.5;
        } elseif($string_x == ":"){
            $tl += 0.1;
        } elseif($string_x == "'"){
            $tl += 0.1;
        } elseif($string_x == "`" ){
            $tl += 0.1;
        } elseif( $string_x == "´"){
            $tl += 0.1;
        } elseif($string_x == ","){
            $tl += 0.1;
        } elseif($string_x == "-"){
            $tl += 0.3;
        } elseif($string_x == "+"){
            $tl += 0.5;
        } elseif($string_x == "."){
            $tl += 0.1;
        } elseif($string_x == ";"){
            $tl += 0.1;
        } elseif($string_x == "*"){
            $tl += 0.37;
        } elseif($string_x == "_"){
            $tl += 0.6;
        } else {
            $tl += 1;
        }

    }
    return $tl;
} 


	//=== Prüfen ob alle Daten vorliegen
	if(!isset($_GET["img"])){
		echo "Fehler: Keine Bilddatei übergeben<br>\n";
		$img="";
	}
	else{
		$img=urldecode($_GET["img"]);
		//Extract Path and Filename
		$imgfile=imagepath($img);
		$studentnr=0;
		filename2student($imgfile["filepath"], $imgfile["filename"], "studentdata");
	}
	
	//=== Daten einlesen
	if(!isset($_GET["ln"])){
		if(!isset($_SESSION["studentdata"][0]["lastname"])){
			echo "Fehler: Keinen Nachnamen übergeben<br>\n";
		}
		else{
			$last_name=$_SESSION["studentdata"][0]["lastname"];
		}
	}
	else{
		$last_name=urldecode($_GET["ln"]);
	}
	
	if(!isset($_GET["gn"])){
		if(!isset($_SESSION["studentdata"][0]["givenname"])){
			echo "Fehler: Keinen Vornamen übergeben<br>\n";
		}
		else{
			$given_name=$_SESSION["studentdata"][0]["givenname"];
		}
	}
	else{
		$given_name=urldecode($_GET["gn"]);
	}	
	
	 if(!isset($_GET["b"])){
		if(!isset($_SESSION["studentdata"][0]["birthday"])){
			echo "Fehler: Kein Geburtsdatum übergeben<br>\n";
		}
		else{
			$birthday=$_SESSION["studentdata"][0]["birthday"];
		}
	}
	else{
		$birthday=urldecode($_GET["b"]);
	}
	if(!isset($_GET["c"])){
		if(!isset($_SESSION["studentdata"][0]["class"])){
			echo "Fehler: Keine Klasse übergeben<br>\n";
		}
		else{
			$class=$_SESSION["studentdata"][0]["class"];
		}
	}
	else{
		$class=urldecode($_GET["c"]);
	}
	

	
	
	
	
	$name=$given_name."<br>".$last_name;

	//echo "lastname:".strwidth($last_name)."<br>";
	//echo "givenname:".strwidth($given_name)."<br>";

	$name_width=max(strwidth($last_name),strwidth($given_name));

	//Bei langen Namen die Schiftart verkleinern
	if(  $name_width > 16  ) {
		$css_font_mod=" very_long_entry ";
	}
	else if(  $name_width >10  ) {
		$css_font_mod=" long_entry ";
	}
	else{
		$css_font_mod="pass_field_value";
	}
	
	if(isset($_SESSION["ablauf"])){
		$ablauf=$_SESSION["ablauf"];
	}
	else{
		$ablauf=ausweisAblauf();
	}
	$output="
	<div style='float: break; margin-bottom: 30px; '>
		<table border='0' style='border-collapse:collapse;'>
			
			<tr>
				<th colspan='2' class='topline'>Cisco Akademietage Lingen 2015</th>
			</tr>

			<tr>
				<td rowspan='6' class='imgpass_td'><img src='$img' class='imgpass'></td>
				<td  class='pass_field_description'>Vorname, Name:</td>
			</tr>
			<tr>
				<td  class='pass_field_value $css_font_mod'>$name</td>
			</tr>
			<tr>
				<td class='pass_field_description'>Geburtsdatum:</td>
			</tr>
			<tr>
				<td class='pass_field_value'>$birthday</td>
			</tr>
			<tr>
				<td class='pass_field_description'>Klasse:</td>
			</tr>
			<tr>
				<td class='pass_field_value'>$class</td>
			</tr>
<!--
			<tr>
				<td class='pass_field_description'>Schuljahr</td>
				<td class='pass_field_value'>2014/2015</td>
			</tr>
-->
			<tr>
				<td class='pass_field_description' colspan='2'><center><h2>Print: LiSA by reimers@heye-tammo.de</h2></center></td>
				<!--<td class='pass_field_description' colspan='2'>Nur gültig in Verbindung mit einem Personalausweis. Gültig bis zum $ablauf</td> -->
			</tr>
		</table>
    </div>";

	echo $output;	

?>
