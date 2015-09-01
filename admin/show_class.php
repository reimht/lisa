<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	session_start(); 

	//System auf UTF-8 einstellen
	iconv_set_encoding("input_encoding", "UTF-8");
	iconv_set_encoding("internal_encoding", "UTF-8");
	iconv_set_encoding("output_encoding", "UTF-8");
	header("Content-Type: text/html; charset=UTF-8");

	//
	require_once('../functions.php'); 
	
	//Settings laden sofern nicht bereits geladen		
	if( !isset($_SESSION["settings"]) ){
		require_once('../settings.php'); 
		$_SESSION["settings"]=$settings;
	}

	$zipencoding= ( isset($_POST["zipencoding"]) ? $_POST["zipencoding"] : "CP437");
	
	//Daten der Schüler einlesen
	getDir("../".$_SESSION["settings"]["images_matching_lisa"],"MAINDIRECTORY", "lisa");
	$student_data=lisaDirToStudentData($_SESSION["lisa"],$_SESSION["settings"]["images_matching_lisa"]);

	
	function lisaDirToStudentData(&$dirData,$imgbasepath){
		$student_data=array();
		if(is_array($dirData)){
			foreach($dirData AS $data){
				$student=array();
				$student["given_name"] = ( isset($data["givenname"]) ? $data["givenname"] : "");
				$student["last_name"] = ( isset($data["lastname"]) ? $data["lastname"] : "");
				$student["birthday"] = ( (isset($data["birthday_year"]) AND isset($data["birthday_month"]) AND isset($data["birthday_day"]) ) ? $data["birthday_day"].".".$data["birthday_month"].".".$data["birthday_year"] : "");
				$student["class"] = ( isset($data["class"]) ? $data["class"] : "");
				$student["pic_small"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");
				$student["pic_big"] = ( isset($data["picfile"]) ? $imgbasepath.$data["picfile"] : "");

				$student_data[$student["class"]][]=$student;
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

	
	$nr_student_in_class=0;
	if(isset($_POST["selected_class"])){
		$selected_class=$_POST["selected_class"];
		if(isset($student_data[$selected_class])){
			$class=$student_data[$selected_class];
			$nr_student_in_class=sizeof($class);
		}
		else{
			echo "no $selected_class in student_data<br>\n";
		}
	}

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
		$dir=realpath("../".$_SESSION["settings"]["target_image_file_path"]."/small/");
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
	.imgpass_td
		{
			width:200px;
			height:200px;			
		}
	.imgpass
		{
			display: block;
			max-width:180px;
			max-height:180px;
			margin-left: auto;
			margin-right: auto;
		} 
	.pass_description_long
		{
			font-size:70%;
			height:19px;
			background: white;
			width:200px;
		} 
	.pass_value_long
		{
			padding-left:10px;
			font-size:110%;
			height:19px;
			background: white;
			width:260px;
			border-bottom:2px solid #000000; //Line unterhalb
		} 
	.pass_description_short
		{
			font-size:70%;
			height:19px;
			background: white;
			max-width:40px;
			border-bottom:2px solid #000000; //Line unterhalb
		} 
	.pass_value_short
		{
			padding-left:10px;
			font-size:110%;
			height:19px;
			background: white;
			border-bottom:2px solid #000000; //Line unterhalb
		} 
    </style>

	
</head>
<body> 
<?php 

		function print_pass($data){
			$schuljahr="2014/2015";
			$given_name = convert_string( $data["given_name"] );
			$last_name = convert_string( $data["last_name"] );
			$name=   $given_name." ".$last_name;
			$birthday=$data["birthday"];
			$class=$data["class"];
			$img="../".$data["pic_small"];
		
			$last_name_url=urlencode(    $data["last_name"]    );
			$last_name_url=str_replace( "+"," ",$last_name_url);

			$given_name_url=urlencode(    $data["given_name"]);
			$given_name_url=str_replace( "+"," ",$given_name_url);


			$img = str_replace($data["last_name"]   ,$last_name_url , $img);
			$img = str_replace($data["given_name"], $given_name_url , $img);
			//$img = str_replace($data["last_name"], urlencode($data["last_name"]), $img);

			$s="";
			$s.="<table border='0' style='border-collapse:collapse;'>
<!--			
			<tr>
				<th colspan='3'>Berufsbildende Schulen II Leer</th>
			</tr>
-->
			<tr>
				<td rowspan='9' class='imgpass_td'><a href='show_one_student.php?img=".urlencode($img)."&gn=".urlencode($given_name)."&ln=".urlencode($last_name)."&b=".urlencode($birthday)."&c=".urlencode($class)."'><img src='$img' class='imgpass'></a></td>
				<td colspan='2' class='pass_description_long'>Vorname, Name:</td>
			</tr>
			<tr>
				<td colspan='2' class='pass_value_long'>$name</td>
			</tr>
			<tr>
				<td class='pass_description_short'>Geburtsdatum:</td>
				<td class='pass_value_short'>$birthday</td>
			</tr>
			<tr>
				<td class='pass_description_short'>Klasse:</td>
				<td class='pass_value_short'>$class</td>
			</tr>
<!--
			<tr>
				<td class='pass_description_short'>Schuljahr</td>
				<td class='pass_value_short'>$schuljahr</td>
			</tr>
-->
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>";
			return $s;
		}
?>

		<div align='center' id='main' class='hideforprint'>
			<h3>Klasse auswählen</h3>
			<form action="<?php echo $self ?>" method="post" targetNO='_blank'>
				<table>
					<tr>
						<td colspan='2'>
							
								Klasse/Gruppe:
								<select name="selected_class" size="1" id="selected_class" onchange="this.form.submit()">
									<?php 	echo option_list_from_array($classes, $selected_class); ?>
								</select>
								
								<input type="submit" name="show_class" value="anzeigen">
								<input type="submit" name="download_class" value="Zip-Datei">
							
						</td>
						<td>
							<?php print_button($lastpage, "show_class", "zurück") ?>
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							Encoding in Zip-Datei <input type="text" name="encoding" value="<?php echo $zipencoding?>" size='8'> (CP437,UTF-8,ISO-8859-1)
						</td>
					</tr>
					<tr>
						<td colspan='3'>
							<small>Dieser Bereich wird nicht gedruckt.</small>
						</td>
					</tr>
						
						
				</table>
			</form>
		</div>	
		<br>

		<?php
			if($nr_student_in_class>0){ 
				$i=0;
				foreach($class AS $student){
					//Zwei Ausdrucke nebeneinander
					if($i%2==0) $float="left";
					else $float="break";
					
					//Seitenumbruch für Ausdruck
					$break_print="";
					if($i>0 AND $i%10==0) $break_print="page-break-before:always;";
					
					$i++;
					echo "\n    <div style='float: $float; margin-bottom: 30px; $break_print'>\n";
					echo print_pass($student);
					echo "\n    </div>";
				}
			}
		
		?>

			
			</p>	

	
	</body>
</html>





