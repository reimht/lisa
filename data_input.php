<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start(); 
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];
	$lastpage="picture_crop.php";	
	$nextpage="data_save.php";
	require_once('functions.php'); 
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
	$filename=$_SESSION["filename"];
	$settings=$_SESSION["settings"];
	
	//Klassen einlesen
	$classes=read_classes_from_csv($settings["classes.csv"]);

	if($debug!=0){
		echo "<pre>CLASSES:\n";
		print_r($classes);
		echo "</pre>";
	}
	
	//Bilddaten bestimmen, ist für die größe der CSS-Container wichtig
	$image_size=getimagesize($filename);
	//$image_width = $image_size[0];
	//$image_height = $image_size[1];
	
	$image_width=400;
	$image_height=400;
	
	if(isset($settings["picture_target_width"]) AND isset($settings["picture_target_height"]) ){
		$image_width = $settings["picture_target_width"];
		$image_height = $settings["picture_target_height"];
		/*
		$rotage=0;
		if(	isset($_SESSION["rotage"])	){
			$rotage=$_SESSION["rotage"];
		}
		if($rotage==0 OR $rotage==180 OR $rotage==360){
			$image_width = $_SESSION["image_width_show"];
			$image_height = $_SESSION["image_height_show"];
		}
		else{
			$image_width = $_SESSION["image_height_show"];
			$image_height = $_SESSION["image_width_show"];
		}
		*/
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
       		 }";


	$autocorrection=false;
	if(isset($_POST["autocorrection"])){
		if($_POST["autocorrection"]=="on"){
			$autocorrection=true;
		}
	}

	$check=check_student_data($autocorrection);


	if(isset($_POST["back"])){
		$meta="<meta http-equiv='refresh' content='0; URL=$lastpage'>";
		//Start Page ($tilte, $style,$script,$meta,$body)
		echo create_header("BBS2Leer", $style,"",$meta,"");
		print_button($lastpage, "data_input", "zurück");
		echo create_footer("");
		exit(0);
		
	}
	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header("BBS2Leer", $style,"","","");
?>

<?php

	

		if(!isset($_SESSION["settings"]) OR isset($_SESSION["data_written"])) {
				echo "<h3> Bitte beginnen Sie von vorne! </h3>";
				print_button("index.php", "button_check_data", "zurück");
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
			print_button($_SERVER['PHP_SELF'], "button_change_data", "ändern");
			echo "</td><td>";
			print_button($nextpage, "button_check_data", "Daten sind vom Lehrer &uuml;berpr&uuml;ft");
			echo "</td><td>";
			print_button($_SERVER['PHP_SELF'], "data_input", "zurück");
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
				<img src="getimage.php?write_picture=1&rand=<?php echo rand(0, 100000)?>" />
			</div>
			

<?php echo create_footer(""); ?>




