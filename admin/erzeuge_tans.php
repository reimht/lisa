<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	session_start();
	session_regenerate_id(true); 
	require_once('../functions.php'); 
	$_SESSION=array();

	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	



	$filename_config="../".$_SESSION["settings"]["tan_config.txt"];
	$msg="TAN Liste";
	$filename_list="../".$_SESSION["settings"]["tan_list.txt"];


	if(isset($_POST["save"]) and isset($_POST["config"]) ){
		$handle = fopen($filename, "w");
		$content = fwrite($handle, $_POST["config"]);
		fclose($handle);
	}


	$config=array();
	$config["k"]=array();
	$config["l"]=array();

	//Konfigurationsdatei einlesen und prüfen
	echo "Lese Datei ein ... <br>";
	$handle = fopen($filename_config, "r");
	while(!feof($handle)){
		$zeile = trim( fgets($handle,1024) );
		if( substr($zeile, 0, 1) != "#"){ //Erstes Zeichen darf keine # sein => Kommentar
			$spalte = explode(",", $zeile);
			if( sizeof($spalte) >= 3 ){
				if( $spalte[0] == "k"  OR $spalte[0] == "l"){
					$typ=$spalte[0];
					$name=$spalte[1];
					$anzahl=$spalte[2];
					$config[$typ][$name]["anzahl"]=$anzahl;
				}
			}
		}
	}
	fclose($handle);

	echo "Anzahl Klassen: ".sizeof($config["k"])."<br>";
	echo "Anzahl Lehrer: ".sizeof($config["l"])."<br>";
	echo "Erstelle TANs ... <br>";

	$tans="";

	//alle Type durchlaufen
	foreach($config AS $typ){
		//einen Type (Klasse/Lehrer) durchlaufen
		foreach($typ AS $key => $entry){
			$anzahl=$entry["anzahl"];

			//einen Type (Klasse/Lehrer) durchlaufen
			for($i=0;$i<$anzahl;$i++){

				//Zweiten Teil der TAN berechnen
				$anzstellen=4-strlen($i);
				$min=pow(10, $anzstellen-1);
				$max=pow(10, $anzstellen)-1;
				$part2=rand($min,$max)."$i";
				$q=10-(quersumme($part2) % 10); //
				if($q==10) $q=0;
				$part2.=$q;
	
				//Dritten Teil der TAN berechnen
				$part3=rand(1000,9999);
				$q=10-(quersumme($part3) % 10); //
				if($q==10) $q=0;
				$part3.=$q;
	
				//echo "a:$anzahl-b:$anzstellen-min:$min-max:$max-part2:$part2-q:$q-q:($q%10)";
	
	
				$tans.="$key - $part2 - $part3\n";
			}


		}

	}

//	echo "<pre>";
//	echo $tans;
//	echo "</pre>";

		$filename="../".$_SESSION["settings"]["tan_list.txt"];
		$handle = fopen($filename, "a");
		$content = fwrite($handle, "\n# ====================================================================\n# Neue Tans:".date("Y-m-d d:i")."\n");
		$content = fwrite($handle, $tans);
		fclose($handle);

//preecho($config);

?>
			<h3>LiSA - Admin!</h3>
			<?php echo "$msg<br>"; ?>
			<p>
<!--
				<form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST' >
					<input type="hidden" name="file" value="<?php echo $_POST["file"] ?>">
					<input type='submit' name='save' value='speichern'>
				</form>
-->
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php echo create_footer(""); ?>
