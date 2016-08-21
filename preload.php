<?php
	//Create Session and load settings
	session_start();
	//Search the LiSA base folder on WebServer
	if(!isset($_SESSION["base_folder"])){
		$base_folder=__DIR__."/";
		if( !file_exists($base_folder."/functions.php") ){
			echo "Can't find base folder<br>\n";
			exit(1);
		}
		$_SESSION["base_folder"]=$base_folder;
	}
	


	//Load Funktions
	require_once($_SESSION["base_folder"]."functions.php"); 
	
	//Check main config file
	check_config_file_templates("config/settings.ini");
	//Load main config
	if( !isset($_SESSION["settings"]) ){
		//require_once($_SERVER['DOCUMENT_ROOT']."/config/settings.php"); 
		$_SESSION["settings"]=parse_ini_file($_SESSION["base_folder"]."config/settings.ini", FALSE);
		$_SESSION["lisa_path"]=realpath($_SESSION["base_folder"]);//.$_SESSION["settings"]["domainSubFolder"]);
		$_SESSION["lisa_web_base_path"]=substr(__DIR__,strlen($_SERVER["DOCUMENT_ROOT"]));
	
		//Check other config files
		check_config_file_templates($_SESSION["settings"]["classes.csv"]); //"config/classes.csv"); 
		check_config_file_templates($_SESSION["settings"]["passwd_list.txt"]); //"config/passwd_list.txt");	
		check_config_file_templates($_SESSION["settings"]["tan_config.txt"]); //"config/tan_config.txt");
		check_config_file_templates($_SESSION["settings"]["tan_list.txt"]); //"config/tan_list.txt");
		check_config_file_templates($_SESSION["settings"]["tan_used.txt"]); //"config/tan_used.txt");
		check_config_file_templates("admin/layout_ausweis.html");
		check_config_file_templates("admin/layout_klasse.html");	
		
		check_path("images_school_classes");
		check_path("temp_image_file_path");
		check_path("target_image_file_path");
		check_path("images_matching_lisa");
	
	}
	
//preecho($_SESSION["settings"]);	

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
	
	
	//Anzeige von PHP-Fehlern
	if( isset($_SESSION["settings"]["php_error_reporting"] )){
		if($_SESSION["settings"]["php_error_reporting"]==true){
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set('error_reporting', E_ALL);
		}
	}
	else{
		error_reporting(0);
		ini_set('display_errors', 0);
		ini_set('error_reporting', 0);
	}

	function in_ip_range($range, $ip=false){
		
		//Get Client IP
		if( $ip==false AND isset($_SERVER["REMOTE_ADDR"]) ){
			$ip=$_SERVER["REMOTE_ADDR"];
		}
		
		//Check Client IP
		if(cidr_match($ip, $range)){
			return true;
		}
	
		
		return false;
	}

	//See http://php.net/manual/de/function.ip2long.php
	function cidr_match($ip, $cidr){
		$cidr_parts=explode('/', $cidr);
		if( sizeof($cidr_parts)==1 ){ //Keine Subnetzmakse angegeben
			$subnet=$cidr;
			$mask=32;
		}
		else {
			$subnet=$cidr_parts[0];
			$mask=$cidr_parts[1];
		}
		if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet)) { 
			return true;
		}
		return false;
	}
	
	function check_path($name_settings){
		$settings=$_SESSION["settings"];
		if(!isset($settings[$name_settings])){
			echo "Error: Setting with name '$name_settings' not found!<br>\n";
		}
		else if(!file_exists(__DIR__.DIRECTORY_SEPARATOR.$settings[$name_settings])){
			echo "Error: Directory '".__DIR__.DIRECTORY_SEPARATOR.$settings[$name_settings]."' for '$name_settings' not exists.<br>\n";
			echo "Go to the admin web-interface and choose 'create folders'!<br>\n";
		}
	}


	function check_config_file_templates($file){
		
		if($file{0}!="/"){
			$file=__DIR__."/".$file;
		}
		
		$path_info=pathinfo($file);
		if( !file_exists($file) ){
			echo "Warning '$file' not exists!<br>";
			$template_file=$path_info["dirname"]."/".$path_info["filename"]."_template.".$path_info["extension"];
			if( !file_exists($template_file) ){
				echo "Error template file '$template_file' not exists!<br>";
			}
			else{
				copy ($template_file, $file);
			}
		}
		if( !file_exists($file) ){
			echo "Error can't create '$file'!<br>";
		}
		
	}
	


	function check_passwd($area, $password){
		if(!isset($_SESSION["settings"]["passwd_list.txt"])){
			echo "Fehler: Passwortdatei ist nicht gesetzt. Bitte überprüfen Sie die Konfiguration";
			return false;
		}
		$filename="../".$_SESSION["settings"]["passwd_list.txt"];
		if(!file_exists($filename)){
			echo "Fehler: Die angegebene Passwortdatei '$filename' existiert nicht. Bitte überprüfen Sie die Konfiguration";
			return false;		
		}
		
		$maxRows = 100;
		$fp= fopen("$filename", "r");
		$counter=0;
		while(($row = fgetcsv($fp, 999, ";")) && ($counter < $maxRows)) {
			if( sizeof($row) >= 2){
				$entry_area=trim($row[0]);
				$entry_unblocking_mode=trim($row[1]);
				$entry_pwd=trim($row[2]);
				//Grober Check ob überhaupt Daten in den Spalten stehen
				if( strlen($entry_area)>2 AND  strlen($entry_unblocking_mode)>2 AND  strlen($entry_pwd)>2) {
					//Prüfen ob es (k)ein Kommentar ist
					if( $entry_area{0} != "#" ){
						//Prüfe ob passende Zeile gefunden wurde
						if ($entry_area == $area ){
							if( strtolower($entry_unblocking_mode) == "ipv4" ){
								$iprange=trim($row[2]);
								if(in_ip_range($iprange)){
									//Übereinstimmung gefunden
									return true;
								}
							}
							else if($password!==false){
								if( strtolower($entry_unblocking_mode)=="plain" ){
									if($entry_pwd == $password){
										return true;
									}
								}
								else{
									//Prüfen ob der Hashwert gebildet werden kann
									if( !$password_hash=hash ( $entry_unblocking_mode , $password) ){
										echo "Hashalgorithmus '$entry_unblocking_mode' wird nicht unterstützt";
									}
									//Passwort Übereinstimmung prüfen
									if($password_hash==$entry_pwd) {
										return true;
									}
								}
							}
						}
					}
				}
			}
			
		}	
	

		return false;
	}

	function remove_files($path, $depth=1, $delte_basefolder=false) {
		$depth--;
		$path=realpath($path);
		//echo "check '$path' <br>\n";
		if (is_dir($path) === true) {
			$files = array_diff(scandir($path), array('.', '..')); 
			foreach($files AS $file) {
				$f=realpath($path."/".$file);
				if($depth>=0){
					remove_files($f,$depth,true);
				}
				else{
					echo "Warning - path $f is to deep<br>\n";
				}
			}
			if($delte_basefolder AND $depth>=0){
				rmdir($path);
			}
		}
		else if(is_file($path)){
			echo "Delete file: '$path'<br>\n";
			unlink($path);

		}
	} 

	function check_login_logout($area=false){
	
		if($area==false){
			//Get folder name = area (admin, print, upload, ...)
			$path=pathinfo($_SERVER["SCRIPT_FILENAME"]);
			$path=explode("/",$path["dirname"]);
			$area=trim($path[sizeof($path)-1]);		
		}
	
		if( isset($_POST["logout"]) OR isset($_GET["logout"]) ){
			if( isset($_SESSION["LOGINAREA"]) ){
				$_SESSION["LOGINAREA"]=false;
				unset($_SESSION['LOGINAREA']);
			}
		}		
		else if( isset($_SESSION["LOGINAREA"]) ){
			//Logout ???	
			//Bereits in dem bereich eingeloggt?
			if($_SESSION["LOGINAREA"]==$area){
				return true;
			}
		}
		
		//if( check_ip() ) return true;
		if( isset($_POST["password"]) ){
			if(check_passwd($area, $_POST["password"])){
				$_SESSION["LOGINAREA"]=$area; //Anmeldung in Session speichern
				return true;
			}
		}
		else {
			if(check_passwd($area, false)){
				$_SESSION["LOGINAREA"]=$area; //Anmeldung in Session speichern
				return true;
			}
		}

		
		echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");
		echo "<form action='' method='POST'>
			".ucfirst($area)."-Passwort: <input type='password' name='password'>
			<input type='submit' value='anmelden'>
			</form>
		";
	
		echo create_footer(); 
		exit(0);
	}

?>
