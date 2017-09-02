<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout(false); //area = false => auto = folder name

	header("Content-Type: text/html; charset=UTF-8");

	$UserTextAllClasses="Alle Klassen"; //Erkennung ob eine oder alle Klassen selektiert worden sind. Wird als Platzhalter der Klassenliste hinzugefügt

	$zipencoding= ( isset($_POST["zipencoding"]) ? $_POST["zipencoding"] : "CP437");
	
	
	//Prüfen ob maximal Alter Ã¼bertragen wurde
	$imgDateNewer=null;
	if( isset($_POST["imgfrom"])){
		if(strlen($_POST["imgfrom"])>=6){
			$date_tmp=explode(".",$_POST["imgfrom"]);
			if(sizeof($date_tmp==3)){
				$imgDateNewer = mktime(0,0,0,$date_tmp[1],$date_tmp[0],$date_tmp[2]);
			}
		}
	}	
	$imgDateOlder=null;
	if( isset($_POST["imguntil"])){
		if(strlen($_POST["imguntil"])>=6){
			$date_tmp=explode(".",$_POST["imguntil"]);
			if(sizeof($date_tmp==3)){
				$imgDateOlder= mktime(0,0,0,$date_tmp[1],$date_tmp[0],$date_tmp[2])+( 60*60*24 );// mktime(0,0,0,0,1,0); //+1 Tag
			}
		}
	}		
	

	
	//Daten der Schüler einlesen
	ob_start();
	getDir("../".$_SESSION["settings"]["images_matching_lisa"],"MAINDIRECTORY", "lisa");
	$student_data=lisaDirToStudentData($_SESSION["lisa"],$_SESSION["settings"]["images_matching_lisa"], $imgDateNewer, $imgDateOlder);
	ob_end_clean(); 
		
	function lisaDirToStudentData(&$dirData,$imgbasepath,$imgDateNewer=null, $imgDateOlder=null){
		$student_data=array();
		if(is_array($dirData)){
			foreach($dirData AS $data){
				$student=array();
				$student["createTime"] = ( isset($data["createTime"]) ? $data["createTime"] : "");
				$student["given_name"] = ( isset($data["givenname"]) ? $data["givenname"] : "");
				$student["last_name"] = ( isset($data["lastname"]) ? $data["lastname"] : "");
				$student["birthday"] = ( (isset($data["birthday_year"]) AND isset($data["birthday_month"]) AND isset($data["birthday_day"]) ) ? $data["birthday_day"].".".$data["birthday_month"].".".$data["birthday_year"] : "");
				$student["class"] = ( isset($data["class"]) ? $data["class"] : "");
				$student["pic"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");
				//$student["pic_small"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");
				//$student["pic_big"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");

				if($imgDateNewer==null  AND $imgDateOlder==null) $addStudent=true;
				else if($imgDateNewer==null  AND $student["createTime"]<=$imgDateOlder) $addStudent=true;
				else if($student["createTime"]>=$imgDateNewer AND $imgDateOlder==null) $addStudent=true;
				else if($student["createTime"]>=$imgDateNewer AND $student["createTime"]<=$imgDateOlder) $addStudent=true;
				else $addStudent=false;
				
				if($addStudent) $student_data[$student["class"]][]=$student;

//====
				//Dateialter prüfen
				if( isset($s["createTime"] )  AND $imgDateNewer!=null ){
					if($s["createTime"]>$imgDateNewer) $csv_data.=$s["last_name"].";".$s["given_name"].";".$s["birthday"].";".$s["class"].";\n";
				}
				else{ //Keine Dateialterprüfung
					$csv_data.=$s["last_name"].";".$s["given_name"].";".$s["birthday"].";".$s["class"].";\n";
				}


//=======



				
			}
		}
		else{
			$student_data="";
		}
		return $student_data;
	
	}
	
	if( is_array($student_data)){
		$classes=array_keys($student_data);
		sort($classes);
	}

	$classForEditLink=""; //Link zu den Klassem im Dateimanager (leer: Klassenübersicht)
	//Zu zeigende Schüler auswählen (i.d.R. eine Klasse)
	$nr_student_in_class=0;
	if(isset($_POST["selected_class"])){
		//Prüfe ob eine bestimme Klasse ausgewählt wurde
		if($_POST["selected_class"] != $UserTextAllClasses){
			//Suche Schüler der Klasse	
			$selected_class=$_POST["selected_class"];
			if(isset($student_data[$selected_class])){
				$class=$student_data[$selected_class];
				$nr_student_in_class=sizeof($class);
				$classForEditLink= ( is_array($selected_class) ? $selected_class[0] : $selected_class); //Link zu den Klassem im Dateimanager
			}
			else{
				echo "no $selected_class in student_data<br>\n";
			}
		}
		else{ //Alle Klassen (Sonderfall)
			$class=array();
			foreach($student_data AS $c){
				foreach($c AS $student){
					$class[]=$student;
				}
			}
			$nr_student_in_class=sizeof($class);
		}
	}
	
	//Edit Link je nach loginarea anpassen
	if($_SESSION["LOGINAREA"]=="admin"){
		$classForEditLink= $_SESSION["lisa_web_base_path"]."/admin/fileManager.php?path=/$classForEditLink/";
	}
	else{
		$classForEditLink= $_SESSION["lisa_web_base_path"]."/edit/fileManager.php?path=/$classForEditLink/";
	}
	
	function compare_student($a, $b) {
		
		return strnatcasecmp($a["pic"],$b["pic"]);
	}
	
	if( isset($class) ) usort($class, 'compare_student');

	function folderToZip($folder, &$zipFile, $exclusiveLength,$encoding) {
    		$handle = opendir($folder); 
    		while (false !== $f = readdir($handle)) {
			if ($f != '.' && $f != '..') {
				$filePath = "$folder/$f";
				// Remove prefix from file path before add to zip.
				$localPath = substr($filePath, $exclusiveLength);
				if (is_file($filePath)) {
					$localPath=convert_string($localPath, null , $encoding);
					$zipFile->addFile($filePath, basename($localPath));

	       		}
/*
				elseif (is_dir($filePath)) {
	          			// Add sub-directory.
					//$localPath=convert_string($localPath, null , $encoding);
	          			$zipFile->addEmptyDir($localPath);
	          			folderToZip($filePath, $zipFile, $exclusiveLength);
       			 }
				echo $localPath."<br>";
*/
      			}
   		 }
    		closedir($handle);
  	} 


	// === Zip-Download? ===

	if( isset($_POST["selected_class"]) AND isset($_POST["download_class"]) ){
		$class=$_POST["selected_class"];
		$dir=realpath("../".$_SESSION["settings"]["target_image_file_path"]);
		$exclusiveLength=strlen($dir);
		$dir.="/$class";

		if( !file_exists($dir) OR $dir==""){
			echo "Kann Klassenverzeichnis $dir nicht finden";
			exit(0);
		}

		//Dateinamen für Temp-Zip-Datei

		$filename = session_id().".zip";
		$filepath = "../".$_SESSION["settings"]["temp_image_file_path"];
		$filename_zip=$filepath ."/".$filename;

		if( file_exists($filename_zip) ) unlink($filename_zip);

		$zip = new ZipArchive();

		if ($zip->open($filename_zip, ZIPARCHIVE::CREATE)!==TRUE) {
			exit("cannot open <$filename_zip>\n");
		}

		//Verzeichnis hinzufügen
		folderToZip($dir,$zip,$exclusiveLength,$zipencoding);

		$zip->close();
		$size = filesize($filename_zip);

		if($size<=1){
			echo "Konnte Zip-Datei nicht erstellen!";
		}
		else{
			// http headers for zip downloads
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$class.".zip\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($filepath.$filename));
			ob_end_flush();
			@readfile($filename_zip);

			
		}
		exit(0);

	}

	//=== HTML-Seite ===





	$lastpage="index.php";
	$nextpage="unknown.php";

	$debug=0;
	if($debug!=0){
		echo "<pre>POST:\n";
		print_r($_POST);
		echo "GET:\n";
		print_r($_GET);
		echo "SESSION:\n";
		print_r($_SESSION);
		echo "</pre>";
	}
	$self=$_SERVER['PHP_SELF'];
	
	$selected_class="";
	if( isset($_POST["selected_class"]) ) $selected_class=$_POST["selected_class"];

	$zeilen=10;
        if( isset($_POST["zeilen"]) ) $zeilen=$_POST["zeilen"];

        $spalten=3;
        if( isset($_POST["spalten"]) ) $spalten=$_POST["spalten"];
	

	$jahr=date("Y");
	$monat=date("m");
	$tag=date("d");
	
	$advancedMode="";
	if( isset($_POST["advancedMode"]) ){
		$advancedMode="checked";
	}

	$modusDateLimit="";
	if( isset($_POST["modusDateLimit"]) ){
		$modusDateLimit="checked";
	}
	
	$ablaufdatum_nicht_automatik="";
	$ablaufdatum="31.07.".($jahr+1);
	if( isset($_POST["ablaufdatum_nicht_automatik"]) ){
		$ablaufdatum_nicht_automatik="checked";
		$ablaufdatum_msg="Das Ablaufdatum wurde nicht automatisch bestimmt!";
		if( isset($_POST["ablaufdatum"]) ){
			$ablaufdatum=$_POST["ablaufdatum"];
		}
	}
	else{
		$classes_ablauf=read_classes_from_csv("../".$_SESSION["settings"]["classes.csv"], true);
		$ablauf=ausweisAblauf($selected_class,$classes_ablauf);		
		$validity_years=class_validity_years($selected_class, $classes_ablauf);
		if($validity_years>0){
			$ablaufdatum=ausweisAblauf($validity_years);
			$ablaufdatum_msg="Der Ausweis ist ".$validity_years."Jahr/e bis zum ".$ablaufdatum." g&uuml;ltig";
		}
		else{
			$ablaufdatum_msg="Fehler: Das Ablaufdatum ist nicht bei den vorgegebenen Klassen angegeben!";
		}
	}
	
	$schuljahr=$jahr."/".($jahr+1);
       if( isset($_POST["schuljahr"]) ) $schuljahr=$_POST["schuljahr"];


	$imgfrom="$tag.$monat.".($jahr-4);
       if( isset($_POST["imgfrom"]) ) $imgfrom=$_POST["imgfrom"];

	$imguntil="$tag.$monat.$jahr";
       if( isset($_POST["imguntil"]) ) $imguntil=$_POST["imguntil"];



	//Layout?
	$layout="klasse";
	$layout_ausweis="";
	$layout_klasse="checked";
	if ( isset($_POST["layout"]) ){
		$layout=$_POST["layout"];
		if($layout =="ausweis"){
			$zeilen=1;
			$spalten=1;
			$layout_ausweis="checked";
			$layout_klasse="";
		}
	}



	//Layout einlesen
	$filename="layout_".$layout.".html";
	$layouthead="";
	$layoutbody="";
	if( file_exists($filename) ){
		$filecontent=file_get_contents($filename);
		//Head ermitteln
                preg_match("~<head.*?>(.*?)<\/head>~is", $filecontent, $layouthead);
                if( isset($layouthead[1]) ){
                        $layouthead=$layouthead[1];
                }
		//Body ermitteln
		preg_match("~<body.*?>(.*?)<\/body>~is", $filecontent, $layoutbody);
		if( isset($layoutbody[1]) ){
			$layoutbody=$layoutbody[1];
		} 
	}
	

	if($debug!=0){
		echo "<pre>CLASSES:\n";
		print_r($classes);
		echo "student_data:\n";
		print_r($student_data);
		echo "</pre>";
	}		
	
	$menu_visibility="";
	if( isset($_POST["show_class"]) ){
/*		$menu_visibility="
			visibility:hidden;
			height: 0px;";*/
			$menu_visibility="";
	}

?>
<!DOCTYPE html>
<html lang='en'>
	<head>
	<title>BBS2Leer</title>
	<link rel="stylesheet" type="text/css"; media="print" href="druck.css">
	<?php echo $layouthead ?>
    <style>
    #main
        {
			<?php echo $menu_visibility; ?>
            margin-left: 2em auto 0;
            width: 700px;
            padding-left: 2em;
            background: white;
            -webkit-box-shadow: 0 1px 10px #D9D9D9;
            -moz-box-shadow: 0 1px 10px #D9D9D9;
            -ms-box-shadow: 0 1px 10px #D9D9D9;
            -o-box-shadow: 0 1px 10px #D9D9D9;
            box-shadow: 0 1px 10px #D9D9D9;
        }
	.menuEntry
	{
	margin-top: 0px;
	margin-bottom: 0px;
	}
    </style>
				<script language='JavaScript'>
				<!--
				function switch_2_expert(){

					//document.getElementById('ModusSimpleLink').style.display = 'none'; //Link - ModusSimple verbergen
					//document.getElementById('ModusExpertLink').style.display = 'block'; //Link - ModusExpert anzeigen
					document.getElementById('advmodus0').style.display = 'block'; // anzeigen
					document.getElementById('advmodus1').style.display = 'block'; // anzeigen
					document.getElementById('advmodus2').style.display = 'block'; // anzeigen
					document.getElementById('advmodus3').style.display = 'block'; // anzeigen
					//document.getElementById('normmodus0').style.display = 'none'; // anzeigen

				}

				function switch_2_simple(){
					//document.getElementById('ModusSimpleLink').style.display = 'block'; //ModusSimple anzeigen
					//document.getElementById('ModusExpertLink').style.display = 'none'; //Link - ModusExpert verbergen

					document.getElementById('advmodus0').style.display = 'none'; //verbergen
					document.getElementById('advmodus1').style.display = 'none'; //verbergen
					document.getElementById('advmodus2').style.display = 'none'; //verbergen
					document.getElementById('advmodus3').style.display = 'none'; //verbergen
					//document.getElementById('normmodus0').style.display = 'block'; // anzeigen
				}


				function switch_dateLimitOn(){

					//document.getElementById('ModusDateLimitOnLink').style.display = 'none'; //Link - ModusSimple verbergen
					//document.getElementById('ModusDateLimitOffLink').style.display = 'block'; //Link - ModusExpert anzeigen
					document.getElementById('datelimit').style.display = 'block'; // anzeigen



				}

				function switch_dateLimitOff(){
					//document.getElementById('ModusDateLimitOnLink').style.display = 'block'; //ModusSimple anzeigen
					//document.getElementById('ModusDateLimitOffLink').style.display = 'none'; //Link - ModusExpert verbergen

					document.getElementById('datelimit').style.display = 'none'; //verbergen


				}
				
				
				function toggleAdvancedMode(){
					var chkBox = document.getElementById('modusSwitch');
					if (chkBox.checked){
						//automatic off -> enable inputfields
						switch_2_expert();
					}
					else{
						//automatic on -> disable inputfields
						switch_2_simple();
					}
				}
				
				function toggleModusDateLimit(){
					var chkBox = document.getElementById('modusDateLimit');
					if (chkBox.checked){
						//automatic off -> enable inputfields
						switch_dateLimitOn();
					}
					else{
						//automatic on -> disable inputfields
						switch_dateLimitOff();
					}				
				}
				
				
 
				
				
				function toggleTextfieldOnOff(){
					var chkBox = document.getElementById('ablaufdatum_automatik_aus');
					if (chkBox.checked){
						//alert("checkbox on!");
						//automatic off -> enable inputfields
						document.getElementById('ablaufdatum_textfield').disabled = false;
						document.getElementById('ablaufdatum_textfield').readOnly = false;
						document.getElementById('schuljahr_textfield').disabled = false;
						document.getElementById('schuljahr_textfield').readOnly = false;
					}
					else{
						//alert("checkbox onff");
						//automatic on -> disable inputfields
						document.getElementById('ablaufdatum_textfield').disabled = true;
						document.getElementById('ablaufdatum_textfield').readOnly = true;
						document.getElementById('schuljahr_textfield').disabled = true;
						document.getElementById('schuljahr_textfield').readOnly = true;
					}
					
					
				}
				//-->
				</script>











</head>
<body onload="toggleAdvancedMode();toggleModusDateLimit();toggleTextfieldOnOff();"> 

		<div align='center' id='main' class='hideforprint'>
			<h3>Klasse auswählen</h3>
				<table border='0'>
					<tr>
						<td>
                                                        <?php print_button($lastpage, "show_class", "zurück") ?>
                                                </td>
						<td colspan='2'>
								<form action="<?php echo $self ?>" method="post" >

								Klasse/Gruppe:
								<select name="selected_class" size="1" id="selected_class" onchange="this.form.submit()">
									
									<?php 	$classes[]=$UserTextAllClasses; echo option_list_from_array($classes, $selected_class); ?>
								</select>
								
								<input type="submit" name="show_class" value="anzeigen">
								<?php if( isset($_POST["selected_class"]) ) if($_POST["selected_class"] != $UserTextAllClasses) echo "<input type='submit' name='download_class' value='Zip-Datei'>"; ?>
							
						</td>
					</tr>
                                        <tr>
                                                <td colspan='1'>
                                                	Layout:
						</td>
                                                <td colspan='2'>
                                                       <input type="radio" name="layout" value="klasse" <?php echo $layout_klasse ?> > Klassenliste / Übersicht
                                                </td>
                                        </tr>
                                        <tr>
						<td colspan='1'>
                                                        &nbsp;
                                                </td>
                                                <td colspan='2'>
                                                       <input type="radio" name="layout" value="ausweis" <?php echo $layout_ausweis ?> > Schülerausweis
                                                </td>
                                        </tr>
                                        <tr>
						<td colspan='1'>
                                                        &nbsp;
                                                </td>
                                                <td colspan='1'>
                                                        Spalten: <input type="text" name="spalten" value="<?php echo $spalten ?>" size='3'> 
                                                </td>
                                                <td colspan='1'>
                                                        Zeilen: <input type="text" name="zeilen" value="<?php echo $zeilen ?>" size='3'>
                                                </td>

                                        </tr>
<!--					<tr>
						<td colspan='3'>
							<p id='normmodus0' >
								Ablaufdatum: <input type="text" name="ablaufdatum" value="<?php echo $ablaufdatum ?>" size='10' disabled>
								Schuljahr: <input type="text" name="schuljahr" value="<?php echo $schuljahr?>" size='10' disabled>
							</p>
						</td>
					</tr>

					<tr>
						<td colspan='2'>
							 <input type='button' value='advanced mode' id='ModusSimpleLink' onclick='switch_2_expert();return false'> 
							 <input type='button' value='normal mode' id='ModusExpertLink' onclick='switch_2_simple();return false' style='display:none;'>
						</td>
						<td colspan='1'>
							 <input type='button' value='set date limit' id='ModusDateLimitOnLink' onclick='switch_dateLimitOn();return false'> 
							 <input type='button' value='no date limit' id='ModusDateLimitOffLink' onclick='switch_dateLimitOff();return false' style='display:none;'>
						</td>
					</tr>
-->
					<tr>
						<td colspan='3'>
							<input id="modusSwitch" type="checkbox" name="advancedMode"<?php echo $advancedMode ?> onclick = "toggleAdvancedMode()"> Erweiterte Ansicht (Experten Modus)
							&nbsp;&nbsp;&nbsp;&nbsp; <a href='<?php  echo "$classForEditLink"?>' target='_blanc'> Daten bearbeiten</a>
							
						</td>
					</tr>
					<tr>  
						<td colspan='3'>
							<p id='advmodus1' style='display:none;' class='menuEntry'>
								<input id="modusDateLimit" type="checkbox" name="modusDateLimit"<?php echo $modusDateLimit ?> onclick = "toggleModusDateLimit()"> Nur Aufnahmen mit einem bestimmten <b>Ausnahmedatum</b> anzeigen
							</p>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<p id='datelimit' style='display:none;' class='menuEntry'>
								Zeige alle Ausweise mit Aufnahmedatum:<br>
								von: <input type="text" name="imgfrom" value="<?php echo $imgfrom ?>" size='10'>
								bis: <input type="text" name="imguntil" value="<?php echo $imguntil?>" size='10'>
							</p>
						</td>
					</tr>

					<tr>
						<td colspan='3'>
							<p id='advmodus0' style='display:none;' class='menuEntry'>
								Encoding in Zip-Datei <input type="text" name="encoding" value="<?php echo $zipencoding?>" size='8'> (CP437,UTF-8,ISO-8859-1)
							</p>
						</td>
					</tr>

					<tr>
						<td colspan='3'>
							<p id='advmodus3' style='display:none;' class='menuEntry'>
								<input id="ablaufdatum_automatik_aus" type="checkbox" name="ablaufdatum_nicht_automatik"<?php echo $ablaufdatum_nicht_automatik ?> onclick = "toggleTextfieldOnOff()"> Ablaufdatum <b> nicht</b> automatisch ermitteln
							</p>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<p id='advmodus2' style='display:none;' class='menuEntry'>
								<?php echo $ablaufdatum_msg ?> 
							</p>
						</td>
					</tr>

					<tr>
						<td colspan='3'>
								Ablaufdatum: <input id="ablaufdatum_textfield" type="text" name="ablaufdatum" value="<?php echo $ablaufdatum ?>" size='10' disabled>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Schuljahr: <input id="schuljahr_textfield" type="text" name="schuljahr" value="<?php echo $schuljahr?>" size='10' disabled>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
								Um die Daten zu &uuml;bernehmen auf <b>'anzeigen'</b> klicken
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<small>Dieser Bereich wird nicht gedruckt.</small>
						</td>
					</tr>
						
						
				</table>
			</form>
			<br>
			<br>
		</div>	

		<?php
			if($nr_student_in_class>0){ 
				$i=1;
				$j=1;
				$nr=0;
				$len=sizeof($class);
	//			echo "<table border='1'><tr>";
				for($nr=0; $nr<$len; $nr++){
					$student=$class[$nr];
				//foreach($class AS $student){
					$float="";
					$break_print="";

					//Mehrere Ausdrucke nebeneinander
					if($i%$spalten==0){
						$float="break";
                                        //Seitenumbruch für Ausdruck 
					//[($len-$nr)>1 => Nicht bei der letzten Seite] 
                                        if( $j%$zeilen==0   AND ($len-$nr)>1 ) $break_print="page-break-after:always;";
						$j++; //neue Seite
					}
					else{
				 		$float="left";
					}
			
	//				echo "<td>";
					echo "\n    <div style='width=8cm ;margin-left: auto; margin-right: auto; text-align: center; float: $float;  $break_print'>\n";
					echo print_pass($student, $layoutbody,$_SESSION["lisa_web_base_path"]);
					echo "\n    </div>";
	//				echo "</td>";
	//				if($float=="break") echo "</tr style='page-break-after:always;'><tr>";
					$i++;
				}
	//			echo "</tr></table>";
			}
		
		?>

			
			</p>	

	
	</body>
</html>





