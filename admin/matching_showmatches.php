<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 

	echo create_header("BBS2Leer", "","","","","logolisa.svg");	
	
	echo "<h1> Zeige gefundene Übereinstimmungen</h1>";



	//==========================================================
	//Übernehme Aktivierungen
	if( isset( $_POST["donotuse"] ) ){
		foreach( $_POST["donotuse"] AS $nr ){
			if(isset($_SESSION["matches"][$nr]["checked"] )){
				$_SESSION["matches"][$nr]["checked"]=0;
			}
			else{
				echo "Fehler: Finde den checked Eintrag von Match Nummer $nr nicht<br>";
			}
		}

	}


	if(isset($_SESSION["matches"])){
	
		echo "<a href='matching_index.php'> Zur&uuml;ck </a>";	
		//==========================================================
		//Zeige Tabelle
		echo "<table border='1'>\n";
		echo "<form action='".$_SERVER["PHP_SELF"]."' method='POST'>";
		echo "<tr><td>Fehler</td><td>Lisa Bilddatei</td><td>BBSPlan Bilddatei</td><td>Ignorieren</td><td>lastname</td><td>givenname</td><td>birthday_day </td><td>birthday_month </td><td>birthday_year </td><td>class<td></tr>\n";
		foreach($_SESSION["matches"] AS $key => $match){	


			$different=0;

			if( isset($match["checked"]) ) $checked=$match["checked"];
			if($checked==1){
				$checked="<td> <input type=checkbox name='donotuse[]' value='$key' ></td>";
			}
			else{
				$checked="<td> <input type=checkbox name='donotuse[]' value='$key' checked></td>";
			}

			//Teste Übereinstimmungen			
			if(  strtolower($match["lisa"]["lastname"]) ==  strtolower($match["bbsplan"]["lastname"])) $lastname="<td bgcolor='#00FF00'>OK</td>";
			else  { $lastname="<td bgcolor='#FF0000'>Error</td>"; $different++;}

			if(  strtolower($match["lisa"]["givenname"]) ==  strtolower($match["bbsplan"]["givenname"]))  $givenname="<td bgcolor='#00FF00'>OK</td>";
			else  { $givenname="<td bgcolor='#FF0000'>Error</td>"; $different++;}


			if(  $match["lisa"]["birthday_day"] ==  $match["bbsplan"]["birthday_day"])  $birthday_day="<td bgcolor='#00FF00'>OK</td>";
			else  { $birthday_day="<td bgcolor='#FF0000'>Error</td>"; $different++;}


			if(  $match["lisa"]["birthday_month"] ==  $match["bbsplan"]["birthday_month"])  $birthday_month="<td bgcolor='#00FF00'>OK</td>";
			else  { $birthday_month="<td bgcolor='#FF0000'>Error</td>"; $different++;}


			if(  $match["lisa"]["birthday_year"] ==  $match["bbsplan"]["birthday_year"])  $birthday_year="<td bgcolor='#00FF00'>OK</td>";
			else  { $birthday_year="<td bgcolor='#FF0000'>Error</td>"; $different++;}


			if(  $match["lisa"]["class"] ==  $match["bbsplan"]["class"])  $class="<td bgcolor='#00FF00'>OK</td>";
			else  { $class="<td bgcolor='#FF0000'>Error</td>"; $different++;}



			echo "<tr><td>$different</td><td>".enc_html($match["lisa"]["picfile"])."</td><td>".enc_html($match["bbsplan"]["picfile"])."</td>$checked $lastname $givenname $birthday_day  $birthday_month  $birthday_year  $class</tr>\n";


		 }
		echo "<tr><td colspan='10'> <input type='submit' name='checked' value='Speichere Aktivierungen'></td></tr>";
		echo "</form>";
		echo "</table>";
	}
	else{
		echo "<br>Keine Matches gefunden!<br>\n";	
	}
	echo "<a href='matching_index.php'> Zur&uuml;ck </a>";

?>



