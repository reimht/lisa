<?php

	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	

	$debug=0;
	$filedebug=0;
	//=========================================================================
	//Load Params
	$max_width=$_SESSION["settings"]["show_picture_max_width"];
	$max_height=$_SESSION["settings"]["show_picture_max_height"];	
	
	//=========================================================================
	//Test if Session is valid
	$error_abort=false;
	$error_msg="none";
	
	//Hole Bilddaten
	$postdata = file_get_contents("php://input");
/*
	if(isset($_SERVER["REMOTE_ADDR"])){
		//Kontrolle ob Session von einer anderen IP gekapert wurde.
		if(isset($_SESSION["REMOTE_ADDR"])){
			if($_SESSION["REMOTE_ADDR"]!=$_SERVER["REMOTE_ADDR"]){
				$error_msg="</head><body>Fehler, Client-IP der Session wurde verändert!<br>Abbruch!</body></html>";
				$error_abort=true;
			}
		}
		else{
			$_SESSION["REMOTE_ADDR"]=$_SERVER["REMOTE_ADDR"];
		}
	}
	else{
		$error_msg= "</head><body>Interner Fehler, kann Client-IP nicht ermitteln<br>Abbruch!</body></html>";
		$error_abort=true;
	}
*/	

	$imgtype="PNG";
	$_SESSION["image_type"]=$imgtype;
	$_SESSION["filename"]="error.png";
	
	if(isset($_SESSION["base_folder"])){
		$base_folder=$_SESSION["base_folder"];
	}
	else{
		$base_folder="";
	}
	
	if(isset($_GET["filename"])){
		$filename=$_GET["filename"];
		$_SESSION["image_temp_filename"]=$filename;
		$_SESSION["image_temp_modified_filename"]=$_SESSION["temp_image_file_path"].".modified.$imgtype";
	}
	else{
		$filename=$_SESSION["temp_image_file_path"].".$imgtype";
		$_SESSION["image_temp_filename"]=$filename;
		$_SESSION["image_temp_modified_filename"]=$_SESSION["temp_image_file_path"].".modified.$imgtype";
	}

/*
			$imgtype=imagetype($_FILES["file"]["name"]);
			if($imgtype!="UNKNOWN"){
				$_SESSION["image_type"]=$imgtype;
				$filename=$_SESSION["temp_image_file_path"].".$imgtype";
				$_SESSION["image_temp_filename"]=$filename;
				$_SESSION["image_temp_modified_filename"]=$_SESSION["temp_image_file_path"].".modified.$imgtype";
*/

	function write_log(){
		global $error_msg,$postdata;
		$daten = array();
		$daten[0]=date("Y-m-s_H:i:s");
		$daten[1]=$_SESSION["class"];
		$daten[2]=$_SESSION["last_name"];	
		$daten[3]=$_SESSION["given_name"];
		$daten[4]=$_SESSION["birth_day"].".".$_SESSION["birth_month"].".".$_SESSION["birth_year"];
		$daten[5]=$_SESSION["REMOTE_ADDR"];
		if ($postdata !== false){
			$daten[6]="php://input - true";
			$daten[7]="image size:". strlen($postdata);
		}
		else{
			$daten[6]="php://input - false";
			$daten[7]="image size: -none-";
		}
		$daten[8]="Error $error_msg";
		$daten[9]=session_id();
		$daten[10]=$_SESSION["image_temp_filename"];
		$daten[11]="dim_org_x:".$_SESSION["dim_org_x"];
		$daten[12]="zoom_factor:".$_SESSION["zoom_factor"];
		$daten[13]="image_width_org:".$_SESSION["image_width_org"];
		$daten[14]="image_height_org:".$_SESSION["image_height_org"];
		$daten[15]="image_width_show:".$_SESSION["image_width_show"];
		$daten[16]="image_height_show:".$_SESSION["image_height_show"];
		if(isset($_SESSION["base_folder"]))  $daten[17]="base_folder".$_SESSION["base_folder"];
		else $daten[17]="none";

		

		$fp = fopen('webcam_log.csv', 'a');
		foreach($daten as $key => $value){
			fwrite($fp, $key.":".$value.",");
		}
		fwrite($fp,"\r\n");
		fclose($fp);
	}


	
if($error_abort) {
	write_log();
	exit(0);
}
	
if ( $postdata !== false ){
	// Get the data
	$imageData=$postdata;

	// Remove the headers (data:,) part.
	// A real application should use them according to needs such as to check image type
	$filteredData=substr($imageData, strpos($imageData, ",")+1);

	// Need to decode before saving since the data we received is already base64 encoded
	$unencodedData=base64_decode($filteredData);

	//echo "unencodedData".$unencodedData;

	// Save file. This example uses a hard coded filename for testing,
	// but a real application can specify filename in POST variable
	$fp = fopen( $_SESSION["image_temp_filename"], 'wb' );
	fwrite( $fp, $unencodedData);
	fclose( $fp );


	$retval=getzoomfactor($_SESSION["filename"],  $max_width, $max_height, $debug);
	$_SESSION["zoom_factor"]=$retval["zoom_factor"];
	$_SESSION["image_width_org"]=$retval["image_width_org"];
	$_SESSION["image_height_org"]=$retval["image_height_org"];
	$_SESSION["image_width_show"]=$retval["image_width_new"];
	$_SESSION["image_height_show"]=$retval["image_height_new"];


	//$size = getimagesize('images/'.$_SESSION["filename"]);

	/*
	$_SESSION["dim_org_x"]=$size[0];
	$_SESSION["dim_org_y"]=$size[1];
	if ($size[0]>320 || $size[1]>240) {
		$src_img = imagecreatefrompng('images/'.$_SESSION["filename"]);
		$dst_img = imagecreatetruecolor(320,240);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, 320, 240, $size[0], $size[1]);
		//$lastpoint=strrpos($_SESSION["filename"],".");
		//$newfilename='images/small'.substr ( $_SESSION["filename"] , 0 , $lastpoint ).".png";
		$newfilename=$_SESSION["filename"];
		imagepng($dst_img, 'images/'.$newfilename);
		imagedestroy($src_img);
		imagedestroy($dst_img);
	}
	*/
	//write_log();

}
write_log();
?>