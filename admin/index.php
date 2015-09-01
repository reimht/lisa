<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start();
	session_regenerate_id(true); 
	require_once('../functions.php'); 
	$_SESSION=array();
	
	require_once('../settings.php'); 
	$_SESSION["settings"]=$settings;	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");		
?>
			<h3>LiSA - Admin!</h3>
			<p>

				<table border='1'>
					<tr>
						<td>
							Daten-Management							
							<form action='show_class.php' method='POST' >
								<input type='submit' value='Zeige eine Klasse'>
							</form>
							<form action='../upload/index.php' method='POST' >
								<input type='submit' value='Klassen Fotos hochladen'>
							</form>
						</td>
					</tr>
					<tr>
						<td>
							System:<br>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="classes">
								<input type='submit' value='Vorgegebene Klassen &auml;ndern'>
							</form>
							<form action='erzeuge_verzeichnisse.php' method='POST' >
								<input type='submit' value='Daten/Bilder Verzeichnisse erstellen'>
							</form>
							<form action='info.php' method='POST' >
								<input type='submit' value='System Informationen'>
							</form>
						</td>
					</tr>
					<tr>
						<td>
							Tan - Management:<br>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="tan_config_file">
								<input type='submit' value='TAN-Konfigurationsdatei &auml;ndern'>
							</form>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="tan_list">
								<input type='submit' value='Bereits erzeugte TANs &auml;ndern'>
							</form>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="tan_used">
								<input type='submit' value='Bereits verwendete TANss &auml;ndern'>
							</form>
							<form action='erzeuge_tans.php' method='POST' >
								<input type='submit' value='TANs erzeugen und hinzufügen'>
							</form>
						</td>
					</tr>
					<tr>
						<td>		
							Datenübernahme<br>
							<form action='matching_index.php' method='POST' >
								<input type='submit' value='Bilder mit BBS Planung vergleichen'>
							</form>
						</td>
					</tr>
				</table>

			</p>	

<?php 
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>





