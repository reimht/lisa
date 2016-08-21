<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin"); //area = false => auto = folder name
	
	header("Content-Type: text/html; charset=utf-8");
	
	session_start();


	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");		
	echo "<br><a href='matching_index.php'> Zur&uuml;ck </a><br>";	
	if( isset($_POST["lisa"]) ){
		echo "Setze die verbleibenen Daten von LiSA zur&uuml;ck<br>";
		$_SESSION["lisa"]=array();
	}
	else if( isset($_POST["bbsplan"]) ){
		echo "Setze die verbleibenen Daten von BBSPlan zur&uuml;ck<br>";
		$_SESSION["bbsplan"]=array();
	}
	else if( isset($_POST["matches"]) ){
		echo "Setze die gefundenen &Uuml;bereinstimmungen zur&uuml;ck<br>";
		$_SESSION["matches"]=array();
	}
	else if( isset($_POST["all"]) ){
		echo "Setze alle Daten zur&uuml;ck<br>";
		$_SESSION["lisa"]=array();
		$_SESSION["bbsplan"]=array();
		$_SESSION["matches"]=array();
		$_SESSION=array();
	}
	else {
		echo "Unbekannte Anweisung<br>";
	}

	$lisa_max=0;
	if( isset( $_SESSION["lisa"] ) ){
		$lisa_max=sizeof($_SESSION["lisa"]);
	}
	$bbsplan_max=0;
	if( isset( $_SESSION["bbsplan"] ) ){
		$bbsplan_max=sizeof($_SESSION["bbsplan"]);
	}
	$matches_akt=0;
	if( isset( $_SESSION["matches"] ) ){
		$matches_akt=sizeof($_SESSION["matches"]);
	}

	echo "LiSA: $lisa_max Eintraege - BBS Plan: $bbsplan_max Eintraege :&Uuml;bereinstimmungen: $matches_akt<br>";


	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>


