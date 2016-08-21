<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	

	$lastpage="index.php";

	//Test if Session is valid ($step)
	test_session(0);

	$debug=0;

	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg",false);


	if( !isset($_POST["part1"]) OR !isset($_POST["part2"]) OR !isset($_POST["part3"])){
		error_msg("Es fehlen Daten");
	}
	else{
		$part1=trim( $_POST["part1"] );
		$part2=trim( $_POST["part2"] );
		$part3=trim( $_POST["part3"] );
	}

	$error_msg="";
	//Prüfe allgemeinen Aufbau (Quersumme muss %10=0 sein
	if(  (quersumme($part2)%10)!=0 ){
		$error_msg.="Der Block 2 ist fehlerhaft<br>";
	}
	//Prüfe allgemeinen Aufbau (Quersumme muss %10=0 sein)
	if(  (quersumme($part3)%10)!=0 ){
		$error_msg.= "Der Block 3 ist fehlerhaft<br>";
	}

	if($error_msg!=""){
		echo "
			<h3>Bitte geben Sie die TAN ein!</h3>
			<p>
				<form action='check_tan.php' method='POST' >
					<input type='text' name='part1' size='5' value='$part1'> -
					<input type='text' name='part2' size='5' value='$part2'> -
					<input type='text' name='part3' size='5' value='$part3'> -
					<input type='submit' value='weiter'>
				</form>
			</p>	";
			error_msg($error_msg);
	}


	$tan="$part1 - $part2 - $part3";
	$tan = str_replace(" ", "", $tan);



	//TANs einlesen

	//Prüfen ob die TAN vorhanden ist
	$filename=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["tan_list.txt"];
	$handle = fopen($filename, "r");
	$tan_exists=false;
	while(!feof($handle)){
		$zeile = trim( fgets($handle,1024) );
		$zeile=str_replace(" ", "", $zeile);
		if($zeile==$tan) $tan_exists=true;
	}
	fclose($handle);
	if(  !$tan_exists  ){

		$msg="Fehler: TAN ist unbekannt<br>
				<form action='index.php' method='POST' >
						<input type='submit' value='zurück'>
				</form>";

		error_msg($msg);
	}



	//Prüfen ob die TAN bereits verwendet wurde
	$filename=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["tan_used.txt"];
	$handle = fopen($filename, "r");
	$tan_used=false;
	while(!feof($handle)){
		$zeile = trim( fgets($handle,1024) );
		$zeile=str_replace(" ", "", $zeile);
		if($zeile==$tan) $tan_used=true;
	}
	fclose($handle);
	if(  $tan_used  ){
		$msg="Fehler: TAN ist bereits verwendet worden<br>
				<form action='index.php' method='POST' >
						<input type='submit' value='zurück'>
				</form>";

		error_msg($msg);
	}
	
	$_SESSION["tan"]=$tan;

?>
			<p>
			<table>
				<tr>
					<td>
							OK: Die TAN ist korrekt und wurde noch nicht verwendet.
					</td>
				</tr>
				<tr>
					<td align='center'>&nbsp;
						<form action='picture_source.php' method='POST' >
							<input type='submit' value='weiter'>
						</form>
					</td>
				</tr>
			</table>
			</p>	
<?php echo create_footer(""); ?>





