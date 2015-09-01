<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start(); 
	require_once('functions.php'); 
	$lastpage="picture_source.php";
	$nextpage="picture_load_file2.php";

	//Test if Session is valid ($step)
	test_session(1);

	$_SESSION["picture_source"]="picture_load_file.php";

	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header("BBS2Leer", "","","","");
?>

			<h3>1b. Bilddatei auswählen</h3>
			<p>
			<table>
				<tr>
					<td>
						<form action="<?php echo $nextpage ?>" method="post" enctype="multipart/form-data">
							<label for="file">Datei:</label>
							<input type="file" name="file" id="file">
							<input type="submit" name="submit" value="weiter">
						</form>
					</td>
					<td>
						<?php print_button($lastpage, "picture_from_webcam", "zurück") ?>
					</td>
				</tr>
			</table>
			</p>	

<?php echo create_footer(""); ?>




