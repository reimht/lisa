<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/

	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	

	//System auf UTF-8 einstellen
	if (function_exists('iconv') && PHP_VERSION_ID < 50600){
		//Older PHP Version
		iconv_set_encoding("input_encoding", $_SESSION["settings"]["character_encoding"]);
		iconv_set_encoding("internal_encoding", $_SESSION["settings"]["character_encoding"]);
		iconv_set_encoding("output_encoding", $_SESSION["settings"]["character_encoding"]);
	}
	elseif (PHP_VERSION_ID >= 50600){
		ini_set("default_charset", $_SESSION["settings"]["character_encoding"]);
	}	
	//header("Content-Type: text/html; charset=UTF-8");
	$debug=0;

	//$size="big";

	$withfolder=false;
	if( isset($_POST["option_folder"])){
		$withfolder=true;
	}


	$imgDateNewer=null;
	if( isset($_POST["imgdate"])){
		if(strlen($_POST["imgdate"])>=6){
			$date_tmp=explode(".",$_POST["imgdate"]);
			if(sizeof($date_tmp==3)){
				$imgDateNewer = mktime(0,0,0,$date_tmp[1],$date_tmp[0],$date_tmp[2]);
			}
			
		}
	}




	$zipencoding= ( isset($_POST["zipencoding"]) ? $_POST["zipencoding"] : "CP437");
	
	function folderToZip($folder, &$zipFile, $exclusiveLength,$encoding, $imgDateNewer, $withfolder, $debug=0) {
    		$handle = opendir($folder); 
    		while (false !== $f = readdir($handle)) {
			if ($f != '.' && $f != '..' && $f != '') {
				$filePath = realpath("$folder/$f");

				// Remove prefix from file path before add to zip.
				$localPath = substr($filePath, $exclusiveLength);
				if($localPath{0}=="/") $localPath=substr($localPath, 1);


				if (is_file($filePath)) {
					$lastchange=filemtime($filePath);

					if( $lastchange==null OR $lastchange>=$imgDateNewer ){
						$localPath=convert_string($localPath, null , $encoding);
						$filenamezip=basename($localPath);
						//if($withfolder) $filenamezip="$localPath\\".$filenamezip;
						if($withfolder) $filenamezip="$localPath";
						$zipFile->addFile($filePath, $filenamezip);
						//$zipFile->addFile($filePath, $localPath);
					}

	       		}
//Subfolder
				elseif (is_dir($filePath)) {
	          			// Add sub-directory.
					//$localPath=convert_string($localPath, null , $encoding);
	          			//$zipFile->addEmptyDir($localPath);
	          			folderToZip($filePath, $zipFile, $exclusiveLength,$encoding, $imgDateNewer, $withfolder);
       			 }
				if($debug>=2) echo $localPath."<br>";
//*/
      			}
   		 }
    		closedir($handle);
  	} 


	// === Zip-Download? ===
		if( isset($_POST["selected_class"]) ) {
			$class=$_POST["selected_class"];
		}
		else{
			$class="";
		}
		$dir=realpath($_SESSION["lisa_path"]."/".$_SESSION["settings"]["target_image_file_path"]);
		$exclusiveLength=strlen($dir);
		$dir.="/$class";

		if( !file_exists($dir) OR $dir==""){
			echo "Kann Klassenverzeichnis $dir nicht finden";
			exit(0);
		}

		//Dateinamen für Temp-Zip-Datei

		$filename = session_id().".zip";
		$filepath = $_SESSION["lisa_path"]."/".$_SESSION["settings"]["temp_image_file_path"];
		$filename_zip=$filepath .$filename;

		if( file_exists($filename_zip) ) unlink($filename_zip);

		if($debug>=1) echo "filepath $filepath-filename_zip:$filename_zip-dir:$dir";

		$zip = new ZipArchive();

		if ($zip->open($filename_zip, ZIPARCHIVE::CREATE)!==TRUE) {
			exit("cannot open <$filename_zip>\n");
		}

		//Verzeichnis hinzufügen
		folderToZip($dir,$zip,$exclusiveLength,$zipencoding,$imgDateNewer, $withfolder);
		if($debug>=1) echo "status:".$zip->getStatusString();
		$zip->close();
		$size = filesize($filename_zip);

		if($size<=1){
			echo "Konnte Zip-Datei nicht erstellen!";
			echo "filepath $filepath-filename_zip:$filename_zip-dir:$dir";
		}
		else{
			// http headers for zip downloads
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"lisa".$class.".zip\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($filepath.$filename));
			ob_end_flush();
			@readfile($filename_zip);

			
		}
		exit(0);



