<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de
	
	Info:
		Icons: http://commons.wikimedia.org/wiki/User:Seahen/gallery
		Images Demodate http://openclipart.org
	**/
/*
	$path=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].pathinfo($_SERVER["PHP_SELF"])["dirname"]."/eingabe/index.php";
	header("Location: $path");
	exit;
*/
	require_once('preload.php'); 	//Create Session an load Config
	$meta_refresh= "<meta http-equiv='refresh' content='10; URL=eingabe/index.php'>";
	$body="<h2>Welcome to LiSA</h2>
			<a href='eingabe/'>Eingabe neuer Daten</a> &nbsp;&nbsp;-&nbsp;&nbsp;
			<a href='print/'>Drucken/Download</a> &nbsp;&nbsp;-&nbsp;&nbsp;
			<a href='upload/'>Upload</a> &nbsp;&nbsp;-&nbsp;&nbsp;			
			<a href='edit/'>Sch&uuml;lerdaten &auml;ndern</a> &nbsp;&nbsp;-&nbsp;&nbsp;
			<a href='admin/'>Einstellungen &auml;ndern</a> &nbsp;&nbsp;&nbsp;&nbsp;<br><br>
			";
	echo create_header($_SESSION["settings"]["html_title"], "","",$meta_refresh,$body,"logolisa.svg",false);
	echo create_footer("",$_SESSION["lisa_web_base_path"]); 
?> 

