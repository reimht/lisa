<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("upload");	
		

	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");		
?>
			<h3>LiSA - Upload!</h3>

<?php 
	
	//Klassen einlesen
	$classes=read_classes_from_csv("../".$_SESSION["settings"]["classes.csv"]);


	echo"<form action='select_pictures.php' method='POST'>
		 <p>
		    <select name='class' size='20'>";
		      
	foreach($classes AS $class){
		echo "<option>$class</option>";
	}
	echo "
		    </select>
		    <br><input type='submit' name='upload' value='Bilder der Klasse hochladen'>
		    <br><br><br><br><br>Nur wenn Klasse <b>nicht</b> vorgegeben ist!<br>Klassenname: <input type='text' name='new_class' value='neu' size='10'> 
		   <br><input type='submit' name='upload_new_class' value='Neue Klasse hinzuf&uuml;gen'>
		  </p>
		</form>";

	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>
