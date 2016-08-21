<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	$lastpage="picture_load_file.php";
	$nextpage="picture_rotage.php";

	//Test if Session is valid ($step)
	test_session(1);



	$max_width=$_SESSION["settings"]["show_picture_max_width"];
	$max_height=$_SESSION["settings"]["show_picture_max_height"];	
	
	$error=1; //zunächst wird von einem Fehler ausgegangen, wenn Upload OK-> $error=0;
	
	//Teste ob eine Datei hochgeladen wurde
	if(isset($_FILES["file"]["error"]) AND isset($_FILES["file"]["name"]) ){
		if ($_FILES["file"]["error"] == 0) {
			//Teste Dateiendung
			$imgtype=imagetype($_FILES["file"]["name"]);
			if($imgtype!="UNKNOWN"){
				$_SESSION["image_type"]=$imgtype;
				$filename=$_SESSION["temp_image_file_path"].".$imgtype";
				$_SESSION["image_temp_filename"]=$filename;
				$_SESSION["image_temp_modified_filename"]=$_SESSION["temp_image_file_path"].".modified.$imgtype";
				//Kopiere Datei
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $_SESSION["image_temp_filename"])) {
					$msg="Datei ist valide und wurde erfolgreich hochgeladen.\n";
					//Zoomfaktor für Anzeige bestimmen
					$retval=getzoomfactor($filename,  $max_width, $max_height, $debug);
					$_SESSION["zoom_factor"]=$retval["zoom_factor"];
					$_SESSION["image_width_org"]=$retval["image_width_org"];
					$_SESSION["image_height_org"]=$retval["image_height_org"];
					$_SESSION["image_width_show"]=$retval["image_width_new"];
					$_SESSION["image_height_show"]=$retval["image_height_new"];
					$error=0;
				} else {
					$msg="Möglicherweise eine Dateiupload-Attacke!\n";
				}
			}
			else{
				$msg="Fehler3 nicht unterstützter Dateityp<br>";
			}
		}
		else{
			$msg="Fehler1: Keine Datei hochgeladen!<br>";
		}
	
	}
	else{
		$msg="Fehler2 keine Datei hochgeladen<br>";
	}

	if($error==0){
		$pagetitle="Lade Datei";
		$meta_refresh= "<meta http-equiv='refresh' content='0; URL=$nextpage'>";
	}
	else{
		$pagetitle="Fehler";
		$meta_refresh="<meta http-equiv='refresh' content='10; URL=$lastpage'>";
	}


	if($debug!=0) $meta_refresh="";
	//create_header($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], "","",$meta_refresh, "","logolisa.svg",false);	
?>
		<div align='center' id='main'>
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
		</div>
	
	</body>
</html>





