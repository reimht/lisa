<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	

	echo create_header("BBS2Leer", "","","","","logolisa.svg");
	
?>
			<h3>LiSA - Admin!</h3>
			<p>

				<table border='1'>
					<tr>
						<td>
							<b>Daten-Management</b>							
							<form action='show_class.php' method='POST' >
								<input type='submit' value='Zeige eine Klasse'>
							</form>
							<form action='../upload/index.php' method='POST' >
								<input type='submit' value='Klassen Fotos hochladen'>
							</form>
							<form action='fileManager.php' method='POST' >
								<input type='submit' value='Dateimanager / Sch&uuml;lernamen &auml;ndern'>
							</form>
                                                        <form action='getZipImageForUntis.php' method='POST' >
								<input type='submit' value='Bilder-Zip-Download mit Ldap Kuerzel'>
							</form>
						</td>
					</tr>
					<tr>
						<td>
							<b>System:</b><br>
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
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="settings">
								<input type='submit' value='Grundeinstellungen &auml;ndern'>
							</form>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="passwd_list_file">
								<input type='submit' value='Passw&ouml;rter &auml;ndern'>
							</form>
							<form action='clean_folder.php' method='POST' >
								<input type="hidden" name="folder" value="temp_image_file_path">
								<input type='submit' value='Tempor&auml;re Dateien l&ouml;schen'>
							</form>
                                                        <form action='clean_folder.php' method='POST' >
                                                                <input type="hidden" name="folder" value="images_school_classes">
                                                                <input type='submit' value='Vorauswahl Bilder l&ouml;schen'>
                                                        </form>
                                                        <form action='clean_folder.php' method='POST' >
                                                                <input type="hidden" name="folder" value="images_matching">
                                                                <input type='submit' value='Bilder aus Matching mit BBS Plan l&ouml;schen'>
                                                        </form>
                                                        <form action='clean_folder.php' method='POST' >
                                                                <input type="hidden" name="folder" value="target_image_file_path">
                                                                <input type='submit' value='Sch&uuml;lerbilder  l&ouml;schen - VORSICHT!'>
                                                        </form>

							
						</td>
					</tr>
					<tr>
						<td>
							<b>Tan - Management:</b><br>
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
							<b>Druck</b><br>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="layout_ausweis">
								<input type='submit' value='Layout Sch&uuml;lerausweis &auml;ndern'>
							</form>
							<form action='edit_config_file.php' method='POST' >
								<input type="hidden" name="file" value="layout_klasse">
								<input type='submit' value='Layout Klassen&uuml;bersicht &auml;ndern'>
							</form>
						</td>
					</tr>
					<tr>
						<td>		
							<b>Datenexport</b><br>
							<form action='matching_index.php' method='POST' >
								<input type='submit' value='Bilder mit BBS Planung vergleichen'>
							</form>
							<hr>
                                                        CSV-Download<br>
                                                        <form action='csv.php' method='POST' >
								&nbsp;&nbsp;nur Dateien neuer als: <input type='text' size='10' name='imgdate' value='01.10.2015'><br>
                                                                &nbsp;&nbsp;<input type='submit' value='CSV-Datei aller Sch&uuml;ler exportieren'>
                                                        </form>							
							<hr>
                                                        Bilder-Download<br>
                                                        <form action='download.php' method='POST' >
								&nbsp;&nbsp;mit Verzeichnisstruktur: <input type='checkbox' name='option_folder' value='folder'> <br>
                                                                &nbsp;&nbsp;gro&szlig;e Bilder: <input type='checkbox' name='option_big' value='big'> <br>
								&nbsp;&nbsp;ZipEncoding: <input type='text' size='7' name='zipencoding' value='CP437'><br>
								&nbsp;&nbsp;nur Dateien neuer als: <input type='text' size='10' name='imgdate' value='01.10.2015'><br>
                                                                &nbsp;&nbsp;<input type='submit' value='Download'>
                                                        </form>
						</td>

					</tr>
				</table>

			</p>	

<?php 
	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 
?>





