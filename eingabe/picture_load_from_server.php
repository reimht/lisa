<?php

	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	

	
	$debug=0;
	if( isset($_SESSION["debug"]) )  $debug=$_SESSION["debug"];

	$lastpage="picture_source.php";

	//Test if Session is valid ($step)
	test_session(1);


	$_SESSION["picture_source"]="picture_load_from_server.php";
	$nextpage="picture_load_from_server2.php";


	$self=$_SERVER['PHP_SELF'];
	//Lese Unter-Verzeichnisse ein
	$basepath=$_SESSION["lisa_path"];
	$picture_path="/".$_SESSION["settings"]["domainSubFolder"]."/".$_SESSION["settings"]["images_school_classes"];
	$picture_path=str_replace(" ", "", $picture_path);
	
	$subdirs=read_subdirs($basepath.$picture_path);
	
	//Lese Bilder ein
	$selected_path="";
	$nr_pics=0;

	if( isset($_POST["selected_path"]) ){
		$_SESSION["selected_path_old"]=$_POST["selected_path"];
		$selected_path=$_POST["selected_path"];
	}
	else if( isset($_SESSION["selected_path_old"]) ){
		$selected_path=$_SESSION["selected_path_old"];
	}

	$path=$basepath."/".$picture_path.$selected_path."/";
	$path=str_replace("//", "/", str_replace("//", "/", $path));

	$pictures=read_pictures_in_dir($path);
	$nr_pics=sizeof($pictures);
	
	
	if($debug!=0){
		echo "<pre>subdirs:\n";
		print_r($subdirs);
		echo "pictures: ($nr_pics)\n";
		print_r($pictures);
		echo "</pre>";
	}
	
	
	
	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg",false);

?>
			<h3>1b. Bilddatei auswählen</h3>
			<p>
			<table>
				<tr>
					<td colspan='2'>
						<form action="<?php echo $self ?>" method="post" >
							Klasse/Gruppe:
							<select name="selected_path" size="1"  id="selected_path" onchange="this.form.submit()">
								<?php 	echo option_list_from_array($subdirs, $selected_path); ?>
							</select>
							<input type="submit" name="changeclass" value="auswählen">
						</form>
					</td>
					<td>
						<?php print_button($lastpage, "picture_from_webcam", "zurück") ?>
					</td>
				</tr>
				<tr>
					<?php
						if($nr_pics>0){ 
							$i=1;
							if( !file_exists( $path."thumb" ) ) mkdir($path."thumb", 0700); //Event. Verzeichnis für Voransicht erstellen
							foreach($pictures AS $pic){
								$filename=$path.$pic;
								$thumbfilename=$path."thumb".DIRECTORY_SEPARATOR.$pic;
						
								$filenameWWW=str_replace($_SESSION["lisa_path"],"",$filename);
								$thumbfilenameWWW=$_SESSION["lisa_web_base_path"].str_replace($_SESSION["lisa_path"],"",$thumbfilename);
								
								if(  !file_exists ( $thumbfilename )  ) create_thumb($filename,$thumbfilename); //Event.  Voransicht erstellen
								echo "	<td><a href='$nextpage?picfile=$filenameWWW'><img src='$thumbfilenameWWW'> </a></td>";
								if( ($i%3)==0 ) echo "\n</tr>\n<tr>";
								$i++;								
	
							}
						}
					
					?>
				</tr>
			</table>
			</p>	

<?php echo create_footer(""); ?>





