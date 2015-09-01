<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	require_once('../functions.php'); 

	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");	


		$b1="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='picfile'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum und Klasse'> -->
								<input type='submit' value='Start'>
							</form>";
		$b2="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthday_class'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b3="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthday_classshort'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum und Klasse (erste 3 Buchstaben) (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b4="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_geb'>
								<!-- <input type='submit' value='Vergleiche Name und GebDatum  (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";			
		$b5="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayDM_class'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Tag und Monat) und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b6="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayDY_class'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Tag und Jahr) und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b7="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayMY_class'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Monat und Jahr) und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b8="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='lastname_birthday_class'>
								<!-- <input type='submit' value='Vergleiche Nachname (ignoriere Vorname),GebDatum und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";

		$b9="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='givenname_birthday_class'>
								<!-- <input type='submit' value='Vergleiche Vorname (ignoriere Nachname),GebDatum und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		
		$b10="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayDM'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Tag und Monat) (ignoriere: Klasse) (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";

		$b11="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayDY'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Tag und Jahr) (ignoriere: Klasse) (nur Kleinbuchstaben, keine Satzzeichen)'> -->
								<input type='submit' value='Start'>
							</form>";
		$b12="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='name_birthdayMY'>
								<!-- <input type='submit' value='Vergleiche Name,GebDatum (nur Monat und Jahr) (ignoriere: Klasse) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Start'>
							</form>";
		$b13="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='nameshort_geb_classshort'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen), GebDatum und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Vorsichtig!'>
							</form>";
		$b14="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='nameshort_geb'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und GebDatum (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Vorsichtig!'>
							</form>";
		$b15="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='nameshort_class'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Vorsichtig!'>
							</form>";
		$b16="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='nameshort_classshort'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Vorsichtig!'>
							</form>";
		$b17="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='givenname_birthdayDM'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b18="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='givenname_birthdayDY'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b19="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='givenname_birthdayMY'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b20="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='lastname_birthdayDM'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b21="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='lastname_birthdayDY'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b22="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='lastname_birthdayMY'>
								<!-- <input type='submit' value='Name(4Zeichen), Vorname(4Zeichen) und Klasse(3Zeichen) (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b23="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='birthday_class'>
								<!-- <input type='submit' value='GebDatum und Klasse (nur Kleinbuchstaben, keine Satzzeichen)'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b24="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='birthday_classshort'>
								<!-- <input type='submit' value='GebDatum Vorsicht!'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";
		$b25="				<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='birthday'>
								<!-- <input type='submit' value='GebDatum Vorsicht!'> --> 
								<input type='submit' value='Sehr Vorsichtig!'>
							</form>";


		$b_some1="		<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='picfile'> <!-- Schritt: 01-->
								<input type='hidden' name='compare_fields[]' value='name_birthday_class'> <!-- Schritt: 02-->
								<input type='hidden' name='compare_fields[]' value='name_birthday_classshort'> <!-- Schritt: 03-->
								<input type='hidden' name='compare_fields[]' value='name_geb'> <!-- Schritt: 04-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayDM_class'> <!-- Schritt: 05-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayDY_class'> <!-- Schritt: 06-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayMY_class'> <!-- Schritt: 07-->
								<input type='hidden' name='compare_fields[]' value='lastname_birthday_class'> <!-- Schritt: 08-->
								<input type='hidden' name='compare_fields[]' value='givenname_birthday_class'> <!-- Schritt: 09-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayDM'> <!-- Schritt: 10-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayDY'> <!-- Schritt: 11-->
								<input type='hidden' name='compare_fields[]' value='name_birthdayMY'> <!-- Schritt: 12-->
								<input type='submit' value='start!'>
							</form>";

		$b_some2="		<form action='matching_matching1.php' method='POST' >
								<input type='hidden' name='compare_fields[]' value='nameshort_geb_classshort'> <!-- Schritt: 13-->
								<input type='hidden' name='compare_fields[]' value='nameshort_geb'> <!-- Schritt: 14-->
								<input type='hidden' name='compare_fields[]' value='nameshort_class'> <!-- Schritt: 15-->
								<input type='hidden' name='compare_fields[]' value='nameshort_classshort'> <!-- Schritt: 16-->
								<input type='submit' value='start!'>
							</form>";

		if( !isset($_SESSION["zeichensatz"]) )   $_SESSION["zeichensatz"]="CP437";


		if(isset($_SESSION["bbsplan_source"])) $bbsplan_source=$_SESSION["bbsplan_source"];
		else $bbsplan_source="nicht festgelegt";


		if(   !isset($_SESSION["lisa_source"])  ) {
			$_SESSION["lisa_source"]="../".$_SESSION["settings"]["images_matching_lisa"];

		}
		
		$lisa_source=$_SESSION["lisa_source"];

		//=== Anzahl der geladenen Datensätze einlesen
		$lisa_no_matches=0;
		if( isset( $_SESSION["lisa"] ) ){
			$lisa_no_matches=sizeof($_SESSION["lisa"]);
		}
		$bbsplan_no_matches=0;
		if( isset( $_SESSION["bbsplan"] ) ){
			$bbsplan_no_matches=sizeof($_SESSION["bbsplan"]);
		}
		
		$lisa_nr_read="unknown";
		if( isset($_SESSION["lisa_size"]) ){
			$lisa_nr_read=$_SESSION["lisa_size"];
		}

		$bbsplan_nr_read="unknown";
		if( isset($_SESSION["bbsplan_size"]) ){
			$bbsplan_nr_read=$_SESSION["bbsplan_size"];
		}

		$matches_nr_read="unknown";
		if( isset($_SESSION["matches"]) ){
			$matches_nr_read=sizeof($_SESSION["matches"]);
		}

//	preecho($_SESSION);
?>
			<h3>LiSA - Admin!</h3>
			<p>
				<table border='1'>
					<tr>
						<td colspan='8'><b>Daten einlesen</b></td>
					</tr>
					<tr>
						<td>
							1.
						</td>
						<td colspan='3'>
							<form action='matching_source.php' method='POST' >
								<input type="hidden" name="source" value="bbsplan">
								<input type='submit' value='BBSPLan Verzeichnis setzen'>
							</form>
						</td>
						<td>
							2.
						</td>
						<td colspan='3'>

							<form action='matching_read.php' method='POST' >
								<input type="hidden" name="source" value="bbsplan">
								<input type='submit' value='Fotos aus BBSPlan einlesen'>
							</form>
						</td>
					</tr>
					<tr>
					<tr>
						<td colspan='2'>BBSPlan Verzeichnis:</td>
						<td colspan='6'><?php echo $bbsplan_source ?> - Anzahl geladener Einträge: <?php echo $bbsplan_nr_read;?></td>
					</tr>
					<tr>
						<td>
							3.
						</td>
						<td colspan='3'>
							<form action='matching_source.php' method='POST' >
								<input type='radio' name='standart' value='on' checked>Standart Verzeichnis<br>
								<input type='radio' name='standart' value='off'>Verzeichnis frei wählen
								<br>
								<input type="hidden" name="source" value="lisa">
								<input type='submit' value='LiSA Verzeichnis setzen'>
							</form>
						</td>
						<td>
							4.
						</td>
						<td colspan='3'>
							<form action='matching_read.php' method='POST' >
								<input type="hidden" name="source" value="lisa">
								<input type='submit' value='Fotos aus LiSA einlesen'>
							</form>
						</td>
					</tr>
					<tr>
						<td colspan='2'>Lisa Verzeichnis:</td>
						<td colspan='6'><?php echo $lisa_source ?> - Anzahl geladener Einträge: <?php echo $lisa_nr_read;?></td>
					</tr>
					<tr>
						<td colspan='8'><b>Daten anzeigen</b></td>
					</tr>
					<tr>
						<td colspan='4'>
							<form action='matching_show.php' method='POST' >
								<input type="hidden" name="source" value="none">
								<input type='submit' value='Daten ohne &Uuml;bereinstimmungen anzeigen'>
							</form>
						</td>
						<td colspan='4'>
							<form action='matching_showmatches.php' method='POST' >
								<input type="hidden" name="source" value="none">
								<input type='submit' value='Daten mit &Uuml;bereinstimmungen anzeigen'>
							</form>
						</td>
					</tr>
					<tr>
						<td colspan='8'>
							<form action='matching_reset.php' method='POST' >
								<input type='submit' name='matches' value='Reset Matches Data'>
								<input type='submit' name='lisa' value='Reset Lisa Data'>
								<input type='submit' name='bbsplan' value='Reset BBSPlan Data'>
								<input type='submit' name='all' value='Reset all Data'>
							</form>
						</td>
					<tr>
					<!-- VERGLEICHE -->
					<tr>
						<td colspan='8'><b>Vergleiche</b></td>
					</tr>
					<tr>
						<td>Check Nr</td><td>Nachname</td>						<td>Vorname</td>							<td colspan='3'>Geburtstag</td>                          			<td>Klasse</td> <td>Start</td>
					</tr>
					<tr>
						<td>&nbsp;	</td><td>&nbsp;</td>							<td>&nbsp;</td>								<td>Tag</td>							<td>Monat</td>						<td>Jahr</td>	<td>&nbsp;</td><td>&nbsp;</td>
					</tr>
					<tr>
						<td>1   		</td><td bgcolor="#00FF00">voll</td>			<td bgcolor="#00FF00">voll</td>			<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td><td><?php echo $b1 ?></td>
					</tr>
					<tr>
						<td>2 			</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td><td><?php echo $b2 ?></td>
					</tr>
					<tr>
						<td>3      		</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">3 Zeichen</td><td><?php echo $b3 ?></td>
					</tr>
					<tr>
						<td>4      		</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b4 ?></td>
					</tr>
					<tr>
						<td>5                	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b5 ?></td>
					</tr>
					<tr>
						<td>6                	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b6 ?></td>
					</tr>
					<tr>
						<td>7               	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#F00F00">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b7 ?></td>
					</tr>
					<tr>
						<td>8                	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">Nein</td>			<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b8 ?></td>
					</tr>
					<tr>
						<td>9                	</td><td bgcolor="#FF0000">Nein</td>		<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b9 ?></td>
					</tr>
					<tr>
						<td>10                	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b10 ?></td>
					</tr>
					<tr>
						<td>11 		</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b11 ?></td>
					</tr>
					<tr>
						<td>12			</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b12 ?></td>
					</tr>
					<tr>
						<td>1-12  		</td>												<td colspan='6'>  Mehrere Schritte nacheinander  </td>																																						<td><?php echo $b_some1 ?></td>
					</tr>
					<tr>
						<td>13			</td><td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">3 Zeichen</td><td><?php echo $b13 ?></td>
					</tr>
					<tr>
						<td>14			</td><td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b14 ?></td>
					</tr>
					<tr>
						<td>15			</td><td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b15 ?></td>
					</tr>
					<tr>
						<td>16			</td><td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">4 Zeichen</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">3 Zeichen</td><td><?php echo $b16 ?></td>
					</tr>
					<tr>
						<td>13-16  	</td>												<td colspan='6'>  Mehrere Schritte nacheinander  </td>																																						<td><?php echo $b_some2 ?></td>
					</tr>

					<tr>
						<td>17                	</td><td bgcolor="#FF0000">nein</td>		<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b17 ?></td>
					</tr>
					<tr>
						<td>18 			</td><td bgcolor="#FF0000">nein</td>		<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b18 ?></td>
					</tr>
					<tr>
						<td>19			</td><td bgcolor="#FF0000">nein</td>		<td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b19 ?></td>
					</tr>
					<tr>
						<td>20                	</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">nein</td>		<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b20 ?></td>
					</tr>
					<tr>
						<td>21 			</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">nein</td>		<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b21 ?></td>
					</tr>
					<tr>
						<td>22			</td><td bgcolor="#00FF00">a-z und 0-9</td>	<td bgcolor="#FF0000">nein</td>		<td bgcolor="#FF0000">Nein</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b22 ?></td>
					</tr>

					<tr>
						<td>23			</td><td bgcolor="#FF0000">Nein</td>		<td bgcolor="#FF0000">Nein</td>			<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>		<td><?php echo $b23 ?></td>
					</tr>
					<tr>
						<td>24			</td><td bgcolor="#FF0000">Nein</td>		<td bgcolor="#FF0000">Nein</td>			<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">3 Zeichen</td><td><?php echo $b24 ?></td>
					</tr>
					<tr>
						<td>25			</td><td bgcolor="#FF0000">Nein</td>		<td bgcolor="#FF0000">Nein</td>			<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#00FF00">Ja</td>	<td bgcolor="#FF0000">Nein</td>		<td><?php echo $b25 ?></td>
					</tr>
					<tr>
						<td colspan='8'><b>ZIP-Datei f&uuml;r Download erstellen und speichern</b></td>
					</tr>
					<tr>
						<td colspan='8'>
							<form action='matching_write.php' method='POST' >
								Zeichensatz:	 <input type='text' name='encoding' value='<?php echo $_SESSION["zeichensatz"] ?>' size='8'> 
								<input type='checkbox' name='delete_zip_file[]' value='yes'>Vorhandene Zip-Datei löschen
								<input type='submit' name='write' value='Übernehme Übereinstimmungen in BBSPlan Bilder Verzeichnis'>
							</form>
						</td>
					</tr>
					<tr>
						<td colspan='8'>
								Windows: CP437  / Linux: UTF-8, ISO-8859-1
						</td>						
					</tr>
				</table>
			</p>	


<?php 



	echo "LiSA: ". ($lisa_nr_read - $lisa_no_matches) ." von $lisa_nr_read Eintraege gefunden - BBS Plan: ".($bbsplan_nr_read - $bbsplan_no_matches)." von $bbsplan_nr_read Eintraege gefunden<br>";
	echo "Gesamt $matches_nr_read &Uuml;bereinstimmungen gefunden<br>";

	$body="<form action='index.php' method='POST' >
				<input type='submit' name='write' value='Hauptmenü'>
			</form>
			<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 

	//preecho($_SESSION);
?>





