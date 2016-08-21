<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 


	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");	

	//Prüfe on LiSA und BBSPlan Daten eingelesen wurden
	if( isset($_SESSION["bbsplan"]) AND isset($_SESSION["lisa"]) ){
		// Erstelle eine Kopie der Daten-Arrays um in den Arrays gefundene Einträge löschen zu können.
		$_SESSION["bbsplan_tmp"]=$_SESSION["bbsplan"];
		$_SESSION["lisa_tmp"]=$_SESSION["lisa"];	
	}
	else{
		echo "LiSA oder BBSPlan Daten fehlen!<br>";
		echo "<br><a href='matching_index.php'> Zurück </a><br>";
		exit(0);
	}

	echo "<br><a href='matching_index.php'> Zurück </a><br>";

	//======================================================

	//Bestätigte Daten übernehmen und Übereinstimmungen aus Quell-Daten löschen
	if( isset($_POST["ok"])){

		echo "<h1>&Uuml;bernehme Daten</h1>";

		$i=0;

		foreach($_POST["ok"] AS $nr){
			$lisa_key=$_SESSION["matches_tmp"][$nr]["lisa"]["nr"];
			$bbsplan_key=$_SESSION["matches_tmp"][$nr]["bbsplan"]["nr"];
			unset( $_SESSION["bbsplan"][$bbsplan_key]);
			unset( $_SESSION["lisa"][$lisa_key]);
			$_SESSION["matches_tmp"][$nr]["checked"]=1;
			$_SESSION["matches"][]=$_SESSION["matches_tmp"][$nr];
			$i++;
		}

		echo "$i Datens&auml;tze &uuml;bernommen<br>";
		
		exit(0);

	}

//preecho($_SESSION["bbsplan"]);
//preecho($_SESSION["lisa"]);
//givenname_birthday_class

	//======================================================

	$compare_fields=array();
	if( isset( $_POST["compare_fields"] ) ){

		if( !is_array($_POST["compare_fields"]) ){
			$compare_fields[0]=$_POST["compare_fields"];
		}
		else{
			$compare_fields=$_POST["compare_fields"];
		}

		
	}
	else{
		$compare_fields[0]="picfile";
	}

	function compare_picfile($a, $b){
		global $compare_field;
		return strcmp( strtolower($a[$compare_field]),strtolower($b[$compare_field]));
	}

	
	echo "<h1> Direkter Vergleich </h1>";

	$lisa_nr=0;
	$lisa_max=0;
	if( isset( $_SESSION["lisa_tmp"] ) ){
		$lisa_max=sizeof($_SESSION["lisa_tmp"]);
	}
	$bbsplan_nr=0;
	$bbsplan_max=0;
	if( isset( $_SESSION["bbsplan_tmp"] ) ){
		$bbsplan_max=sizeof($_SESSION["bbsplan_tmp"]);
	}

	echo "LiSA: $lisa_max Eintraege - BBS Plan: $bbsplan_max Eintraege<br>";

	

	
	//Init
	$_SESSION["matches_tmp"]=array();

	$i=0;
	$matches_found=array();
	echo "<form action='".$_SERVER["PHP_SELF"]."' method='POST'>\n";
	echo "<table border='1'>\n";
	echo "<tr><td colspan='4'> <input type='submit' name='checked' value='&Uuml;bernehme Daten'></td></tr>";
	echo "<tr><td>Nr</td><td>Lisa</td><td>BBSPlan</td><td>Korrekt</td></tr>";	

	//=== Vergleichen beginnen 
	foreach($compare_fields AS $compare_field){
		
		//Zeiger auf den ersten Eintrag
		reset($_SESSION["lisa_tmp"]);
		//Zeiger auf den ersten Eintrag
		reset($_SESSION["bbsplan_tmp"]);

		uasort($_SESSION["bbsplan_tmp"], "compare_picfile");  
		uasort($_SESSION["lisa_tmp"], "compare_picfile");  

		$tbl="";	
		
		$tbl.="<tr><td colspan='4'> <b>Internes Vergleichsfeld:</b> $compare_field</td></tr>";


		
		/*//Debug Püfen ob Sortierung korrekt
		$ta="";
		foreach ($_SESSION["lisa_tmp"] AS $f  ){
			$ta.=$f[$compare_field]."<br>";
		}
		$tb="";
		foreach ($_SESSION["bbsplan_tmp"] AS $f  ){
			$tb.=$f[$compare_field]."<br>";
		}
		$tbl.="<tr><td colspan='2'>$ta</td><td colspan='2'>$tb</td></tr>"; //*/
		

		//Zeiger auf den ersten Eintrag
		reset($_SESSION["lisa_tmp"]);
		$lisa_val = current($_SESSION["lisa_tmp"]);
		//Zeiger auf den ersten Eintrag
		reset($_SESSION["bbsplan_tmp"]);
		$bbsplan_val = current($_SESSION["bbsplan_tmp"]);

		
		$timetolive=100000; //Zähler für Notfall-Abbruch um Endlosschleife zuvermeiden

		$lisa_key_delete=array();		//Speicher für Einträge mit gefundenem "Partner-Eintrag"
		$bbsplan_key_delete=array(); 	//Speicher für Einträge mit gefundenem "Partner-Eintrag"

		$run=true;	//Suche Matche solange run=true;
		do {
			$lisa_compare_field=$lisa_val[$compare_field];
			$bbsplan_compare_field=$bbsplan_val [$compare_field];

			//echo "<td>CP:$compare_field: $lisa_compare_field with $bbsplan_compare_field<br></td>\n";

			if(  strcmp( strtolower( $lisa_compare_field ),strtolower( $bbsplan_compare_field ) ) > 0  ){
				do{
					$bbsplan_val = next($_SESSION["bbsplan_tmp"]);
					$bbsplan_compare_field=$bbsplan_val[$compare_field];
					//echo "<td>Vergleiche: $lisa_compare_field  mit $bbsplan_compare_field</td>";
					if( $bbsplan_compare_field != ""){
						$cmp=strcmp( strtolower( $lisa_compare_field ),strtolower( $bbsplan_compare_field ) );
					}
					else{
						$cmp = -1;
					}
					if($timetolive-- <=0 ) {echo "Error 12"; exit(0);} //Notfall-Abbruch um Endlosschleife zuvermeiden
				}while( $cmp > 0 );
			}
			//echo "<td>FOUND: $lisa_compare_field with $bbsplan_compare_field</td>";


			if( strtolower( $lisa_compare_field ) == strtolower( $bbsplan_compare_field ) ){
				//Feld darf nicht leer sein
				if( $lisa_compare_field != "" ){
					//$tbl.="<tr><td>$i</td><td>$lisa_compare_field </td><td>$bbsplan_compare_field</td></tr>";
					$tbl.="<tr><td>$i</td><td>".enc_html($lisa_val["picfile"])."</td><td>".enc_html($bbsplan_val["picfile"])."</td><td><input type='checkbox' name='ok[]' value='$i' checked></td></tr>\n";
					//Array Keys der übereinstimmenden speichern
					$match=array();
					$match["lisa"]=$lisa_val;
					$match["bbsplan"]=$bbsplan_val;
					$match["checked"]=0;	//Übernehmen aber als nicht geprüft markieren
					$matches_found[$i]=$match;
					$i++;	//Anzahl Matches erhöhen
					//Gefundene Einträge merken um diese später zu entfernen
					$lisa_key_delete[]=$lisa_val["nr"];
					$bbsplan_key_delete[]=$bbsplan_val["nr"];
				}
			}
			//else{
				$lisa_val = next($_SESSION["lisa_tmp"]);
				if( $lisa_val===false ) $run=false;
			//}
			if($timetolive-- <=0 ) {echo "Error 11"; exit(0);} //Notfall-Abbruch um Endlosschleife zuvermeiden
		}while($run);
		
		//Entferne Einträge mit gefundenem "Partner-Eintrag"
		foreach($lisa_key_delete AS $key){
			unset( $_SESSION["lisa_tmp"][$key]);
		}
		//Entferne Einträge mit gefundenem "Partner-Eintrag"
		foreach($bbsplan_key_delete AS $key){
			unset( $_SESSION["bbsplan_tmp"][$key]);
		}


		echo $tbl;
	
	}
	//=== Vergleichende


	//Tabelle schließen
	echo "<tr><td colspan='4'> <input type='submit' name='checked' value='&Uuml;bernehme Daten'></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	echo "<br> $i gleiche Eintraege gefunden <br><br>";

	

	if( !isset($_SESSION["matches"]) ) $_SESSION["matches"]=array();

	//Gefundene Einträge temporär speichern
	foreach($matches_found AS $match){
		if( isset( $match["lisa"]["nr"] ) AND isset( $match["bbsplan"]["nr"] ) ){
			$_SESSION["matches_tmp"][]=$match;
		}
	}

	echo "<br><a href='matching_index.php'> Zurück </a><br>";


?>