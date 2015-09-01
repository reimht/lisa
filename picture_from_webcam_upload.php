<?php

	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	session_start();
	$debug=0;
	$filedebug=0;
	require_once('functions.php'); 
	//=========================================================================
	//Load Params
	$settings=$_SESSION["settings"];
	$max_width=$settings["show_picture_max_width"];
	$max_height=$settings["show_picture_max_height"];	
	
	//=========================================================================
	//Test if Session is valid
	$error_abort=false;
	$error_msg="none";
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

	$_SESSION["image_type"]="PNG";
	$_SESSION["filename"]="error.png";
	if(isset($_GET["filename"])){
		$_SESSION["filename"]=$_GET["filename"];
	}
	else{
		$filename=$_SESSION["temp_image_file_path"].".".$_SESSION["image_type"];
		$_SESSION["filename"]=$filename;
	}
	
	function write_log(){
		global $error_msg;
		$daten = array();
		$daten[0]=date("Y-m-s_H:i:s");
		$daten[1]=$_SESSION["class"];
		$daten[2]=$_SESSION["last_name"];	
		$daten[3]=$_SESSION["given_name"];
		$daten[4]=$_SESSION["birth_day"].".".$_SESSION["birth_month"].".".$_SESSION["birth_year"];
		$daten[5]=$_SESSION["REMOTE_ADDR"];
		if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) $daten[6]="HTTP_RAW_POST_DATA - true";
		else $daten[6]="HTTP_RAW_POST_DATA - false";
		$daten[7]="image size:". strlen($GLOBALS['HTTP_RAW_POST_DATA']);
		$daten[8]="Error $error_msg";
		$daten[9]=session_id();
		$daten[10]=$_SESSION["filename"];
		$daten[11]="dim_org_x:".$_SESSION["dim_org_x"];
		$daten[12]="zoom_factor:".$_SESSION["zoom_factor"];
		$daten[13]="image_width_org:".$_SESSION["image_width_org"];
		$daten[14]="image_height_org:".$_SESSION["image_height_org"];
		$daten[15]="image_width_show:".$_SESSION["image_width_show"];
		$daten[16]="image_height_show:".$_SESSION["image_height_show"];

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
	
if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))
{
// Get the data
$imageData=$GLOBALS['HTTP_RAW_POST_DATA'];

// Remove the headers (data:,) part.
// A real application should use them according to needs such as to check image type
$filteredData=substr($imageData, strpos($imageData, ",")+1);

// Need to decode before saving since the data we received is already base64 encoded
$unencodedData=base64_decode($filteredData);

//echo "unencodedData".$unencodedData;

// Save file. This example uses a hard coded filename for testing,
// but a real application can specify filename in POST variable
$fp = fopen( $_SESSION["filename"], 'wb' );
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
write_log();

}
?>