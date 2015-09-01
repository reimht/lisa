<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start(); 
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	require_once('functions.php'); 
	$settings=$_SESSION["settings"];
	$lastpage="picture_load_from_server.php";
	$nextpage="picture_crop.php";

	//Test if Session is valid ($step)
	test_session(1);

	$picfile="";
	$msg="";
	$error_msg="";
	if(isset($_GET["picfile"])){
		$picfile=$_GET["picfile"];
		if( file_exists($picfile) ){
			$imgtype=imagetype($picfile);
			if($imgtype!="UNKNOWN"){
				$_SESSION["image_type"]=$imgtype;
				$filename_new=$_SESSION["temp_image_file_path"].".$imgtype";
				$_SESSION["filename"]=$filename_new;
				if(@copy($picfile,$filename_new)){
					//Bilddaten wie Auflösung und notwengigen Zoomfaktor bestimmen
					$max_width=$settings["show_picture_max_width"];
					$max_height=$settings["show_picture_max_height"];	
					$retval=getzoomfactor($filename_new,  $max_width, $max_height, $debug);
					if($retval["error"]==0){
						$_SESSION["zoom_factor"]=$retval["zoom_factor"];
						$_SESSION["image_width_org"]=$retval["image_width_org"];
						$_SESSION["image_height_org"]=$retval["image_height_org"];
						$_SESSION["image_width_show"]=$retval["image_width_new"];
						$_SESSION["image_height_show"]=$retval["image_height_new"];
						$error=0;
					}
					else{
						$error_msg="Ungültige Bilddatei ausgewählt<br>";
						$error=16;
					}					
				}
				else{
				    $errors= error_get_last();
					$error_msg="Kann Bilddatei nicht kopieren".$errors['type']."-".$errors['message']."<br>";
					$error=8;
				}
			}
			else{
				$error_msg="Keine oder ungültige Bilddatei ausgewählt<br>";
				$error=4;
			}
		
		}	
		else{
			$error_msg="Bilddatei nicht verfügbar<br>";
			$error=2;
		}
	}
	else{
		$error_msg="Kein Bild ausgewählt<br>";
		$error=1;
	}
	if($error==0){
		$pagetitle="Lade Datei";
		$meta_refresh= "<meta http-equiv='refresh' content='0; URL=$nextpage'>";
	}
	else{
		$pagetitle="Fehler";
		$meta_refresh="<meta http-equiv='refresh' content='10; URL=$lastpage'>";
		$msg=$error_msg;
	}	
	

	if($debug!=0) $meta_refresh="";
	//create_header($tilte, $style,$script,$meta,$body)
	echo create_header("BBS2Leer", "","",$meta_refresh, "");	
?>
			<h3><?php echo $pagetitle?></h3>
			<p>
			<table>
				<tr>
					<td colspan='2'>
						<?php 	
							echo $msg; 
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?php 	
							print_button($lastpage, "button-back-loadpicture2", "zurück");
							echo "</td><td>";
							if($error==0)print_button($nextpage, "button-back-loadpicture2", "weiter");
						?>
					</td>
				</tr>
			</table>
			</p>	

<?php echo create_footer(""); ?>




