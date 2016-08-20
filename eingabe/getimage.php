<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	//check_login_logout("eingabe");	
	
	$debug=0;
	if(isset($_GET["debug"])) $debug=$_GET["debug"];
	if($debug==0){
		header('Content-type: image/jpeg');
	}
	else{
		echo "<pre>POST:\n";
		print_r($_POST);
		echo "GET:\n";
		print_r($_GET);
		echo "SESSION:\n";
		print_r($_SESSION);
	}
	
	$error=1; //Zunächst wird von einem Fehler ausgegangen
	$msg="Unbekannter Fehler!<br>";
	if(  isset($_SESSION["image_temp_filename"]) AND isset($_SESSION["settings"]) ) {
		$filename=$_SESSION["image_temp_filename"];
		$settings=$_SESSION["settings"];
	}
	else{
		echo "Error";
		exit(0);
	}
	
	
	/*
	//Zoom Faktor bestimmen
	$max_width=$settings["show_picture_max_width"];
	$max_height=$settings["show_picture_max_height"];		
	if(isset($_SESSION["zoom_factor"])){
		$zoom_factor=$_SESSION["zoom_factor"];
	}
	else{
		$retval=getzoomfactor($filename,  $max_width, $max_height, $debug);
		$zoom_factor=$retval["zoom_factor"];
	}
	
	if($debug!=0) echo "zoom_factor:$zoom_factor<br>";
	
	$retval = imagesetresolution($filename,  $zoom_factor);
	$img_small=$retval["image"];
	*/
	
	if(isset($_SESSION["zoom_factor"])) $zoom_factor=$_SESSION["zoom_factor"];
	else $zoom_factor=1;
	
	
	$retval=imagecreatefromfile($filename);
	$img=$retval["img"];
	$image_width = $retval["image_width"];
	$image_height = $retval["image_height"];
	
	if($debug!=0) print_r($retval);
	
	//Bild drehen?
	if(isset($_SESSION["rotage"]) AND !isset($_GET["norotage"]) ){
		if($_SESSION["rotage"]>0 AND $_SESSION["rotage"]<360){
			$rotage=$_SESSION["rotage"];
			if($debug!=0) echo "Rotage: $rotage"."°<br>";
			$img=imagerotate($img, (360-$_SESSION["rotage"]),0);
		}
		else{
			$rotage=0;
		}
	}
	else{
		$rotage=0;
	}

	//Bild beschneiden?
	if(!isset($_GET["nocrop"]) AND isset($_SESSION["crop_x"]) AND isset($_SESSION["crop_y"]) AND isset($_SESSION["crop_w"]) AND isset($_SESSION["crop_h"])){
		if(isset($settings["picture_target_width"]) AND isset($settings["picture_target_height"])){
			$image_width_new=$settings["picture_target_width"];
			$image_height_new=$settings["picture_target_height"];
		}
		else{
			$image_width_new=$_SESSION["crop_w"];
			$image_height_new=$_SESSION["crop_h"];	
		}
		$img=croppicture($img, $_SESSION["crop_x"] ,$_SESSION["crop_y"] ,$_SESSION["crop_w"] ,$_SESSION["crop_h"], $image_width_new,  $image_height_new, $zoom_factor, $debug);
	}
	else{
		if($rotage==90 OR $rotage==270){
			$tmp=$image_width;
			$image_width=$image_height;
			$image_height=$tmp;
		}
		$img= imagechangeresolution($img, $image_width, $image_height,  $zoom_factor, $debug);
	}

	//Bild vor dem eventuellen speichern und umrechnen ausgeben
	if($debug==0) imagejpeg($img, null, 100);
	
	//Bild speichern?
	if( isset($_GET["write_picture"]) ) $write_picture=$_GET["write_picture"];
	else if ( isset($_SESSION["write_picture"]) ) $write_picture=$_SESSION["write_picture"];
	else $write_picture=0;
	
	if($write_picture==1){
		if(!isset($_SESSION["image_temp_modified_filename"])) $_SESSION["image_temp_modified_filename"]=$filename.".modified";
		//writefile
		imagetofile($_SESSION["image_temp_modified_filename"], $settings["target_image_type"], $img, $settings["target_filesize"], 100, $debug);
	}
	



	
?>




<?php

	/* ======================================================================================
	 ======================================================================================
	  ======================================================================================
	   ======================================================================================
	    ======================================================================================
	     ======================================================================================
	      ======================================================================================
	       ======================================================================================
	require_once('../preload.php'); 	//Create Session an load Config
	//check_login_logout("eingabe");	
	
	$debug=0;
	if(isset($_GET["debug"])) $debug=$_GET["debug"];
	if($debug==0){
		header('Content-type: image/jpeg');
	}
	else{
		echo "<pre>POST:\n";
		print_r($_POST);
		echo "GET:\n";
		print_r($_GET);
		echo "SESSION:\n";
		print_r($_SESSION);
	}
	
	$error=1; //Zunächst wird von einem Fehler ausgegangen
	$msg="Unbekannter Fehler!<br>";
	if(  isset($_SESSION["filename"]) AND isset($_SESSION["settings"]) ) {
		$filename=$_SESSION["filename"];
		$settings=$_SESSION["settings"];
	}
	else{
		echo "Error";
		exit(0);
	}
	
	

	
	if(isset($_SESSION["zoom_factor"])) $zoom_factor=$_SESSION["zoom_factor"];
	else $zoom_factor=1;
	
	
	$retval=imagecreatefromfile($filename);
	$img_small=$retval["img"];
	$image_width = $retval["image_width"];
	$image_height = $retval["image_height"];
	
	if($debug!=0) print_r($retval);
	
	//Bild drehen?
	if(isset($_SESSION["rotage"]) AND !isset($_GET["norotage"]) ){
		if($_SESSION["rotage"]>0 AND $_SESSION["rotage"]<360){
			$rotage=$_SESSION["rotage"];
			if($debug!=0) echo "Rotage: $rotage"."°<br>";
			$img_small=imagerotate($img_small, (360-$_SESSION["rotage"]),0);
		}
		else{
			$rotage=0;
		}
	}
	else{
		$rotage=0;
	}

	//Bild beschneiden?
	if(!isset($_GET["nocrop"]) AND isset($_SESSION["crop_x"]) AND isset($_SESSION["crop_y"]) AND isset($_SESSION["crop_w"]) AND isset($_SESSION["crop_h"])){
		if(isset($settings["picture_target_width"]) AND isset($settings["picture_target_height"])){
			$image_width_new=$settings["picture_target_width"];
			$image_height_new=$settings["picture_target_height"];
		}
		else{
			$image_width_new=$_SESSION["crop_w"];
			$image_height_new=$_SESSION["crop_h"];	
		}
		$img_big=croppicture($img_small, $_SESSION["crop_x"] ,$_SESSION["crop_y"] ,$_SESSION["crop_w"] ,$_SESSION["crop_h"], null,null, $zoom_factor, $debug);
		$img_small=croppicture($img_small, $_SESSION["crop_x"] ,$_SESSION["crop_y"] ,$_SESSION["crop_w"] ,$_SESSION["crop_h"], $image_width_new,  $image_height_new, $zoom_factor, $debug);
	}
	else{
		if($rotage==90 OR $rotage==270){
			$tmp=$image_width;
			$image_width=$image_height;
			$image_height=$tmp;
		}
		$img_big=$img_small;
		$img_small = imagechangeresolution($img_small, $image_width, $image_height,  $zoom_factor, $debug);
	}

	//Bild vor dem eventuellen speichern und umrechnen ausgeben
	if($debug==0) imagejpeg($img_small, null, 100);
	
	//Bild speichern?
	if( isset($_GET["write_picture"]) ) $write_picture=$_GET["write_picture"];
	else if ( isset($_SESSION["write_picture"]) ) $write_picture=$_SESSION["write_picture"];
	else $write_picture=0;
	
	if($write_picture==1){
		$_SESSION["target_filename_small"]=$settings["temp_image_file_path"].session_id()."."."small.".$settings["target_image_type"];
		$_SESSION["target_filename_big"]=$settings["temp_image_file_path"].session_id()."."."big.".$settings["target_image_type"];
		//writefile
		imagetofile($_SESSION["target_filename_small"], $settings["target_image_type"], $img_small, $settings["target_filesize"], 100, $debug);
		imagetofile($_SESSION["target_filename_big"], $settings["target_image_type"], $img_big, 10000000, 100, $debug);
	}
	
*/

?>




