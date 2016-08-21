<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	

	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	
	$lastpage="picture_crop.php";	
	$nextpage="data_save.php";

	//Test if Session is valid ($step)
	test_session(3);

	//preecho($_SESSION);

	if(isset($_POST["x"]) AND isset($_POST["y"]) AND isset($_POST["w"]) AND isset($_POST["h"]) ){
		$_SESSION["crop_x"]=round($_POST["x"]);
		$_SESSION["crop_y"]=round($_POST["y"]);
		$_SESSION["crop_w"]=round($_POST["w"]);
		$_SESSION["crop_h"]=round($_POST["h"]);
	}	
	$error=1; //Zunächst wird von einem Fehler ausgegangen
	$msg="Unbekannter Fehler!<br>";
	$filename=$_SESSION["image_temp_filename"];
	//$settings=$_SESSION["settings"];
	$configfile=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["classes.csv"];

	//Klassen einlesen
	$classes=read_classes_from_csv($configfile);

	if($debug!=0){
		echo "<pre>CLASSES:\n";
		print_r($classes);
		echo "</pre>";
	}
	
	//Bilddaten bestimmen, ist für die größe der CSS-Container wichtig
	$image_size=getimagesize($filename);
	//$image_width = $image_size[0];
	//$image_height = $image_size[1];
	

	
	if(isset($_SESSION["settings"]["picture_target_width"]) AND isset($_SESSION["settings"]["picture_target_height"]) ){
		$image_width = $_SESSION["settings"]["show_picture_max_width"];
		$image_height = $_SESSION["settings"]["show_picture_max_height"];
	}
	else{
		//Set to somethink
		$image_width=400;
		$image_height=400;	
	}

	$style="
		#main
	        {
			width: ".($image_width +600)."px;
			//height: ".($image_height+100)."px;
	        }
			
				
		#imagediv
	        {
				float: left;
	            		width: ".($image_width+10)."px;
				
				padding: 10px;
       		 }
		 #userpicture
		 {
			max-width: ".($image_width+10)."px;
		 }";


	$autocorrection=false;
	if(isset($_POST["autocorrection"])){
		if($_POST["autocorrection"]=="on"){
			$autocorrection=true;
		}
	}

	$check=check_student_data($autocorrection);

	//Gültigkeit/Ablaufdatum ermitteln
	if(isset($_SESSION["class"])){
		//Klassengültigkeit einlesen
		$classes_ablauf=read_classes_from_csv($configfile, true);
		$ablauf=ausweisAblauf($_SESSION["class"],$classes_ablauf);		
		$validity_years=class_validity_years($_SESSION["class"], $classes_ablauf);
		if($validity_years>0){
			$ablaufdatum=ausweisAblauf($validity_years);
			$ablaufdatum_msg="Der Ausweis ist ".$validity_years."Jahr/e bis zum ".$ablaufdatum." g&uuml;ltig";
		}
		else{
			$ablaufdatum_msg="Fehler: Das Ablaufdatum ist nicht bei den vorgegebenen Klassen angegeben!";
			$ablaufdatum=ausweisAblauf($validity_years);
		}
		//debug//echo $_SESSION["class"]."-".$ablaufdatum_msg:
		$_SESSION["ablaufdatum"]=$ablaufdatum;
	}



	if(isset($_POST["back"])){
		$meta="<meta http-equiv='refresh' content='0; URL=$lastpage'>";
		//Start Page ($tilte, $style,$script,$meta,$body)
		echo create_header($_SESSION["settings"]["html_title"], $style,"",$meta,"","logolisa.svg",false);
		print_button($lastpage, "data_input", "zur&uuml;ck");
		echo create_footer("");
		exit(0);
		
	}
	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], $style,"","","","logolisa.svg",false);
?>

<?php

	

		if(!isset($_SESSION["settings"]) OR isset($_SESSION["data_written"])) {
				echo "<h3> Bitte beginnen Sie von vorne! </h3>";
				print_button("index.php", "button_check_data", "zur&uuml;ck");
				echo create_footer(""); 
				exit(0);

		}	

?>

			<h3>2. Schritt: Daten eingeben</h3>
			<div align='left' id='datainputdiv'>

<?php

	

	if(isset($_POST["button_check_data"])){
		if($check["value"]==0 AND !isset($_POST["button_change_data"])){
			show_data();
			echo "<table border='0'><tr><td>";
			print_button($_SERVER['PHP_SELF'], "button_change_data", "&Auml;ndern");
			echo "</td><td>";
			print_button($nextpage, "button_check_data", "Daten sind vom Lehrer &uuml;berpr&uuml;ft");
			echo "</td><td>";
			print_button($_SERVER['PHP_SELF'], "data_input", "zur&uuml;ck");
			echo "</td></tr></table>";
		}
		else{
			echo $check["msg"];
			print_data_input_formular($_SERVER['PHP_SELF'],$classes,$lastpage);
			//print_button($lastpage, "data_input", "zurück");
		}
	}
	else{
		print_data_input_formular($_SERVER['PHP_SELF'],$classes,$lastpage);
		//print_button($lastpage, "data_input", "zurück");
	}
	
?>


			</div>
			<div align='center' id='imagediv'>
				<img id='userpicture' src="getimage.php?write_picture=1&rand=<?php echo rand(0, 100000)?>" />
			</div>
			

<?php echo create_footer(""); ?>




