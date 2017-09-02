<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de
	
	Info:
		Icons: http://commons.wikimedia.org/wiki/User:Seahen/gallery
		Images Demodate http://openclipart.org
	**/

	session_start();
	session_destroy();
	
	require_once('../preload.php'); 	//Create Session an load Config
	
	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg",false);

	//Check libraries and stopp in case of errors
	$error=check_libs(true); //check the necessary libraries true=stopp_on_error
	
	if(isset($settings["demo_mode"])){
		if($settings["demo_mode"]==true){
		
			echo "
				<h2> Demo-Modus </h2>
				<h3> Funktionsumfang</h3>
				<table>
					<tr>
						<td colspan='2'>
							<b>Zugangsregelung:</b>
							<ul>
								<li>Freigabe über TANs</li>
								<li>Freigabe von IP-Adressen</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<b>Bildquellen:</b>
							<ul>
								<li>WebCam</li>
								<li>Einzelner Dateiupload</li>
								<li>Klassensatz Upload</li>
							</ul>
						</td>
						<td>
							<b>Druck:</b>
							<ul>
								<li>Direkt Druck</li>
								<li>Klassenübersicht</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<b>Schnittstellen:</b>
							<ul>
								<li>Fehlertoleranter Abgleich mit BBS Planung</li>
								<li>Bilderdownload als ZIP-Datei</li>
							</ul>
						</td>
					</tr>
				</table>
			
				<h3> Weitere Funktionen </h3>
				<table>
					<tr>
						<td>Druck und Download</td>
						<td><a href='print/'>start</a></td>
						<td>User/Passwort: print/print</td>
					</tr>
					<tr>
						<td>Klassensatz Bilder aufspielen</td>
						<td><a href='upload/'>start</a></td>
						<td>User/Passwort: upload/upload</td>
					</tr>
					<tr>
						<td>Administration und Matching mit 'BBS Planung'</td>
						<td><a href='admin/'>start</a></td>
						<td>User/Passwort: admin/admin</td>
					</tr>
				</table>
				<small>Hinweis: Die Daten werden regelmäßig gelöscht!<br></small>
			
				";
		}
	}
	
	
	echo "
		<h3>Erstellen Sie Ihren Schülerausweis!</h3>
			<p>
				<form action='start.php' method='POST' >
						<input type='submit' value='los gehts'>
				</form>
			</p>";



	echo create_footer(""); 
	
?>





