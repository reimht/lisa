<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/;
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	//echo create_header("BBS2Leer", "","","","","logolisa.svg");
	
	/** Info
	Icon: http://commons.wikimedia.org/wiki/User:Seahen/gallery
	**/


	


	//Create new Session Data
	$_SESSION["step"]=0;
	$_SESSION["temp_image_file_path"]=realpath($_SESSION["lisa_path"]."/".$_SESSION["settings"]["temp_image_file_path"])."/".session_id();
	$_SESSION["image_type"]="";
	//Schülerdaten
	$_SESSION["given_name"]="";
	$_SESSION["given_name_errno"]=1; //not set
	$_SESSION["last_name"]="";
	$_SESSION["last_name_errno"]=1; //not set
	$_SESSION["birth_day"]="";
	$_SESSION["birth_day_errno"]=1; //not set
	$_SESSION["birth_month"]="";
	$_SESSION["birth_month_errno"]=1; //not set
	$_SESSION["birth_year"]="";
	$_SESSION["birth_year_errno"]=1; //not set
	$_SESSION["class"]="";
	$_SESSION["class_errno"]=1; //not set


	//Tan Data
	$part1="";
	if( isset( $_POST["part1"])  ){
		$part1=$_POST["part1"];	
	}
	$part2="";
	if( isset( $_POST["part2"])  ){
		$part1=$_POST["part2"];	
	}
	$part3="";
	if( isset( $_POST["part3"])  ){
		$part1=$_POST["part3"];	
	}

	//Test if Session is valid ($step)
	test_session(0);
	
	//Prüfe ob die IP kein TAN benötigt
	if( in_array( $_SESSION["REMOTE_ADDR"] , $_SESSION["settings"]["ip_notan"] ) OR $_SESSION["settings"]["tan_active"]==false ){
		$meta="<meta http-equiv='refresh' content='0; URL=picture_source.php'>";
		$body="			
			<h3>Erstellen Sie Ihren Schülerausweis!</h3>
			<p>
				1. Schritt <br>
				<form action='picture_source.php' method='POST' >
					<input type='submit' value='Foto einfügen'>
				</form>
			</p>	";
		header("Location: picture_source.php");
	}
	else{
		$meta="";
		$body="
			<h3>Bitte geben Sie die TAN ein!</h3>
			<p>
				<form action='check_tan.php' method='POST' >
					<input type='text' name='part1' size='5'> -
					<input type='text' name='part2' size='5'> -
					<input type='text' name='part3' size='5'> -
					<input type='submit' value='weiter'>
				</form>
			</p>	";
		
	}

        //Start Page ($tilte, $style,$script,$meta,$body)

        echo create_header("BBS2Leer", "","",$meta,$body,"logolisa.svg",false);
	
	echo create_footer("");

?>










