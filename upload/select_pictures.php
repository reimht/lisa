<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 
	
	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");


	//Prüfen wie viele Datein gleichzeitig hochgeladen wurden
	$max_files=ini_get('max_file_uploads');
	$post_max_size = ini_get('post_max_size');

	$class="";
	if( isset( $_POST["class"] ) AND isset( $_POST["upload"] )){
		$class=$_POST["class"];
		//echo "Klasse: $class<br>";
	}
	else if( isset( $_POST["new_class"] ) AND isset( $_POST["upload_new_class"] )){
		$class=$_POST["new_class"];
	}

	if($class=="neu"){
		echo create_footer("<h3>LiSA - Upload!</h3>Fehler: die Kasse darf nicht <b>neu</b> heißen!<br><a href='select_class.php'>zurück</a>"); 
		exit(0);
	}
	else if($class==""){
		echo create_footer("<h3>LiSA - Upload!</h3>Fehler: Keine Klasse angegeben!<br> <a href='select_class.php'>zurück</a>"); 
		exit(0);
	}

	$_SESSION["class"]=$class;
	if( !isset($_SESSION["settings"]["images_school_classes"]) ){
		echo create_footer("<h3>LiSA - Upload!</h3>Fehler: Settings: images_school_classes nicht gesetzt!<br>"); 
		exit(0);
	}
	$path="../".$_SESSION["settings"]["images_school_classes"]."$class/";

	$classes=read_classes_from_csv("../".$settings["classes.csv"]);

/*
	if( !in_array($class,$classes) ){
		//echo "Klasse nicht als Vorgabe  vorhanden<br>";
	}
*/

	if( !file_exists($path) ){
		//echo "Erstelle Klassenverzeichnis!<br>";
		umask(0002);
		if ( !mkdir ( $path, 0770 ) ){
			echo create_footer("<h3>LiSA - Upload!</h3>Fehler: Konnte Verzeichnis $path nicht erstellen!<br>"); 
			exit(0);
		}
	}

?>
			<h3>LiSA - Upload!</h3>
			<p>
				<form action="upload_pictures.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="class" value="<?php echo $class?>">

<?php
		  if( !in_array($class,$classes) ) echo"<br>Die Klasse <b>$class</b> den Vorgaben hinzuf&uuml;gen <input type='checkbox' name='add_class' value='yes' checked> ja<br><br>";
?>


					<input type="file" multiple="multiple" name="files[]" id="files"   /> 
					<input type="submit" value='Bilder auf den Server laden'><br>
					<br>Maximal <?php echo $max_files ?> Dateien ausw&auml;hlen!
					<br>Maximal <?php echo $post_max_size ?>Byte ausw&auml;hlen!
				</form>
			</p>	

<?php 
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>

