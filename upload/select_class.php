<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 
	
	if(!isset($_SESSION["settings"])){
		require_once('../settings.php'); 
		$_SESSION["settings"]=$settings;
	}
		

	echo create_header("BBS2Leer", "","","","","logolisa.svg");		
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
