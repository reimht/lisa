<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	

	$lastpage="index.php";

	//Test if Session is valid ($step)
	test_session(0);

	$debug=0;

	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header("BBS2Leer", "","","","","logolisa.svg",false);

?>
			<h3>1a. Bildquelle auswählen</h3>
			<p>
			<table>
				<tr>
					<td>
<?php
						print_button("picture_from_webcam.php", "takepicture", "WebCam");
						echo "</td><td>";
						print_button("picture_load_file.php", "loadpicturefile", "Datei hochladen");
						echo "</td><td>";
						print_button("picture_load_from_server.php", "loadpicturefile", "Server");
						echo "</td><td>";
						print_button("$lastpage", "picturesource", "Abbrechen");							
?>
					</td>
				</tr>
			</table>
			</p>	
<?php echo create_footer(""); ?>





