<?php

	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/


	//Funktion um Sonderzeichen im Browser korrekt darzustellen
	function enc_html($string){
		//$z=array("\""		,"&"		,"<"		,">"		,"ß"		,"Ä"		,"ä"		,"Ü"		,"ü"		,"Ö"		,"ö"		,"€"		,"È"		,"É"			,"Ê"		,"è"		,"é"			,"ê"		,"À"			,"Á"		,"Â"		,"Ã"		,"Å"		,"à"		,"á"			,"â"		,"ã"		,"å"		,"æ");
		//$h=array("&quot;","&amp;"	,"&lt;"		,"&gt;"		,"&szlig;"	,"&Auml;"	,"&auml;"	,"&Uuml;"	,"&uuml;"	,"&Ouml;"	,"&ouml;"	,"&euro;"	,"&Egrave;","&Eacute;"	,"&Ecirc;"	,"&egrave;","&eacute;"	,"&ecirc;"	,"&Agrave;"	,"&Aacute;","&Acirc;"	,"&Atilde;"	,"&Aring;"	,"&agrave;","&aacute;"	,"&acirc;"	,"&atilde;"	,"&aring;"	,"&aelig;");
		//$u=array("&#34;"	,"&#38;"	,"&#60;"	,"&#62;"	,"&#223"	,"&#196;"	,"&#228;"	,"&#220;"	,"&#252;"	,"&#214;"	,"&#246;"	,"&#8364;","&#200"	,"&#201"		,"&#202"	,"&#232"	,"&#233"		,"&#234"	,"&#192"		,"&#193"	,"&#194"	,"&#195"	,"&#197"	,"&#224"	,"&#225"		,"&#226"	,"&#227"	,"&#229"	,"&#230");

		$z=array("ß"		,"Ä"		,"ä"		,"Ü"		,"ü"		,"Ö"		,"ö"		,"€"		,"È"		,"É"			,"Ê"		,"è"		,"é"			,"ê"		,"À"			,"Á"		,"Â"		,"Ã"		,"Å"		,"à"		,"á"			,"â"		,"ã"		,"å"		,"æ");
		$h=array("&szlig;"	,"&Auml;"	,"&auml;"	,"&Uuml;"	,"&uuml;"	,"&Ouml;"	,"&ouml;"	,"&euro;"	,"&Egrave;","&Eacute;"	,"&Ecirc;"	,"&egrave;","&eacute;"	,"&ecirc;"	,"&Agrave;"	,"&Aacute;","&Acirc;"	,"&Atilde;"	,"&Aring;"	,"&agrave;","&aacute;"	,"&acirc;"	,"&atilde;"	,"&aring;"	,"&aelig;");
		$u=array("&#223"	,"&#196;"	,"&#228;"	,"&#220;"	,"&#252;"	,"&#214;"	,"&#246;"	,"&#8364;","&#200"	,"&#201"		,"&#202"	,"&#232"	,"&#233"		,"&#234"	,"&#192"		,"&#193"	,"&#194"	,"&#195"	,"&#197"	,"&#224"	,"&#225"		,"&#226"	,"&#227"	,"&#229"	,"&#230");

		$encoding=mb_detect_encoding($string , "auto", true);
		if( $encoding == "ASCII"){
				return $string;//."aaa";
		}
		else if( strtoupper($encoding) == "UTF-8"){
			return str_replace ($u,$h,$string);//."bbb"; 
		}
		else{
			return str_replace ($z,$h,$string);//."ccc"; 
		}		
		

		return $string;
	}

	//Funktion um Sonderzeichen im Browser korrekt darzustellen
	function encode_to_html($text){
		return utf8_decode($text);
	} 


/*
root@machine:/tmp# echo -n Ä | hexdump 
0000000 84c3                                   
0000002
*/







	//Macht aus te Vehn - Heiner -> Vehn - Heiner te
	function namenszusatz_hinter_vornamen(&$s, $givenname, $lastname, $debug=false){
		$namenszusatz=array("de la", "de",  "ter", "te", "van der",  "van", "von",  "der");
		$nr=sizeof($namenszusatz);
		$i=0;
		$lastname_len=strlen($s[$lastname]);
		$compare=" ".strtolower($s[$lastname])." ";
		do{
			$zusatz=trim($namenszusatz[$i]);
			$zusatzlen=strlen($zusatz);
			$pos= strpos($compare, " ".$zusatz." ");
			//Überprüfe ob der Namenszusatz gefunden wurde
			if($pos!==false){
				if($debug!=null) echo "Ändere Namen: vorher: NN: ".$s[$lastname]." VN:".$s[$givenname]; //Debugausgabe

				//Überprüfe ob der Namenszusatz am Anfang des Nachnames steht
				if($pos<=1){
					//Entferne den Namenszusatz beim Nachnamen
					$s[$lastname]=trim(substr ( $s[$lastname] , $zusatzlen ));
					//Füge den Namenszusatz dem Vornamen hinzu
					$s[$givenname]=trim($s[$givenname]." ".$zusatz);
					$i=$nr; //Abbruch	
				}
				//Überprüfe ob der Namenszusatz am Ende des Nachnames steht
				if( ($lastname_len-$pos-$zusatzlen)<=1  AND  ($lastname_len-$pos-$zusatzlen)>=-1){
					//Entferne den Namenszusatz beim Nachnamen
					$s[$lastname]=trim(substr ( $s[$lastname] , 0, $lastname_len-$zusatzlen ));
					//Füge den Namenszusatz dem Vornamen hinzu
					$s[$givenname]=trim($s[$givenname]." ".$zusatz);
					$i=$nr; //Abbruch	
				}				
				if($debug!=null) echo " nachher: NN: ".$s[$lastname]." VN:".$s[$givenname]."<br>";		//Debugausgabe	
			}
			$i++;

		}while($i<$nr);

		//return $s;
	}

		function print_pass($data, $layoutbody, $web_base_path="../"){
			global $ablaufdatum, $schuljahr;
			$given_name = convert_string( $data["given_name"] );
			$last_name = convert_string( $data["last_name"] );
			$name=   $given_name." ".$last_name;
			$birthday=$data["birthday"];
			$class=$data["class"];
			$img=$data["pic"];
			
			$last_name_url=urlencode(    $data["last_name"]    );
			$last_name_url=str_replace( "+"," ",$last_name_url);

			$given_name_url=urlencode(    $data["given_name"]);
			$given_name_url=str_replace( "+"," ",$given_name_url);


			$img = str_replace($data["last_name"]   ,$last_name_url , $img);
			$img = str_replace($data["given_name"], $given_name_url , $img);
			//$img = str_replace($data["last_name"], urlencode($data["last_name"]), $img);

			//Prüfe ob Body der Basis html-Datei wahrscheinlich korrekt ist
			if(strlen($layoutbody)>100){
				$layoutbody=str_replace("LISA_LINK_AUSWEIS", "show_one_student.php?img=".urlencode($img)."&gn=".urlencode($given_name)."&ln=".urlencode($last_name)."&b=".urlencode($birthday)."&c=".urlencode($class)."&a=".urlencode($ablaufdatum), $layoutbody);
				$layoutbody=str_replace("LISA_BILD", $web_base_path."/".$img, $layoutbody);
				$layoutbody=str_replace("LISA_VORNAME", $given_name, $layoutbody);
				$layoutbody=str_replace("LISA_NACHNAME", $last_name, $layoutbody);
				$layoutbody=str_replace("LISA_GEBURTSDATUM", $birthday, $layoutbody);
				$layoutbody=str_replace("LISA_KLASSE", $class, $layoutbody);
				$layoutbody=str_replace("LISA_SCHULJAHR", $schuljahr, $layoutbody);
				$layoutbody=str_replace("LISA_ABLAUFDATUM", $ablaufdatum, $layoutbody);
					
				return $layoutbody;
			}

			$s="";
			$s.="<table border='0' style='border-collapse:collapse;'>
<!--			
			<tr>
				<th colspan='3'>Schulname</th>
			</tr>
-->
			<tr>
				<td rowspan='9' class='imgpass_td'><a href='show_one_student.php?img=".urlencode($img)."&gn=".urlencode($given_name)."&ln=".urlencode($last_name)."&b=".urlencode($birthday)."&c=".urlencode($class)."&a=".urlencode($ablaufdatum)."'><img src='$img' class='imgpass'></a></td>
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


	//Ermittelt den Zeichensatz eines Strings
	function get_encoding($string, $enc=null, $ret=null) {
	        
	        $enclist = array(
	             'ASCII','UTF-8',
	            'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
	            'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
	            'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
	            'Windows-1251', 'Windows-1252', 'Windows-1254','CP437','CP850'
	            );

		//Test ob eine Kodierungbevorzugt überprüft werden soll
		$enc=strtoupper($enc);
		if( ($enc!=null OR strtolower($enc)=="auto") AND $enc!="" ){
	       		if($enc == "CP437") array_unshift($enclist,'CP437'); //Kann nicht direkt zugewiesen werden (PHP interna)
	       		else if($enc == "CP850") array_unshift($enclist,'CP850'); //Kann nicht direkt zugewiesen werden (PHP interna)
	       		else if($enc == "ISO-8859-1") array_unshift($enclist,'ISO-8859-1'); //Kann nicht direkt zugewiesen werden (PHP interna)
	       		else if($enc == "UTF-8") array_unshift($enclist,'UTF-8'); //Kann nicht direkt zugewiesen werden (PHP interna)
	       		array_unshift($enclist,'ASCII');  //ASCII ist immer bevorzugt
		}

	        $result = false;
	       
	        foreach ($enclist as $item) {
	            $sample = @iconv($item, $item, $string);
	            if (md5($sample) == md5($string)) {
	                if ($ret === NULL) { $result = $item; } else { $result = true; }
	                break;
	            }
	        }
	       
	    return $result;
	} 

	//Konvertiert eine Zeichenketten von einem Zeichensatz in einen anderen
	function convert_string($string, $source_encoding = null, $target_encoding=null){

		if($source_encoding==null OR strtolower($source_encoding)=="auto") $source_encoding=get_encoding($string);

		if($target_encoding==null OR strtolower($target_encoding)=="auto") $target_encoding=iconv_get_encoding("internal_encoding");

		//echo "$source_encoding - $target_encoding -  ". iconv($source_encoding, $target_encoding , $string)."<br>\n";

		if($source_encoding != $target_encoding){
			return iconv($source_encoding, $target_encoding , $string);
		}
		else{
			//Keine Änderung des Zeichensatz
			return $string;
		}
	}

	//Gibt die Quersumme einer Zahl zurück
	function quersumme ( $digits ){
	// Typcast falls Integer uebergeben
		$strDigits = ( string ) $digits;

		for( $intCrossfoot = $i = 0; $i < strlen ( $strDigits ); $i++ ){
			$intCrossfoot += $strDigits{$i};
		}

		return $intCrossfoot;
  	} 

	//
	function error_msg($msg){
		echo $msg;
		echo create_footer("");
		exit(0);
	}


	function create_header($tilte, $style,$script,$meta,$body,$logo="logo_small.png",$headermenu=true, $character_encoding="UTF-8"){
		global $debug;

		//System auf UTF-8 einstellen
		header("Content-Type: text/html; charset=".$character_encoding);

		$prepath=(!file_exists("layout.css") ? "../" : "");

		//Soll das Menü im Seitenkopf angezeigt werden?
		if($headermenu){
			$headermenu="<a href='index.php' title='Goto home'><img class='img_header_menu' src='".$prepath."lisaicons/home.svg'></a>";
			if( isset($_SESSION["LOGINAREA"]) ) $headermenu.="<a href='index.php?logout=1' title='logout'><img class='img_header_menu' src='".$prepath."lisaicons/logout.svg'></a>";
		}
		else{
			$headermenu="";
		}
		
		$header= "<!DOCTYPE html>
			<html lang='en'>
			<head>
				<title>$tilte</title>
				<meta charset='utf-8'>
				$meta
				<link rel='stylesheet' href='".$prepath."layout.css' type='text/css' media='all'>
				 <style>
						$style
				</style>
				 <script>
						$script
				</script>
				
			
			</head>
			<body>
				<div align='center' id='main'>
					<div id='header_menu'>
						$headermenu
					</div>	
					<div id='header'>
						<img class='img_logo' src='".$prepath."lisaicons/".$logo."'>
					</div>
				
					<div id='body'>
					
					
					
				$body";

		if($debug!=0){
				echo "<pre>";
				echo "Session:\n";
				print_r($_SESSION);
				echo "Post:\n";
				print_r($_POST);
				echo "Get:\n";
				print_r($_GET);
				echo "</pre>";		
		} 

		return $header;

	}

	function create_footer($body=""){
		return "$body
					<div id='footer' align='left'>
						
						<a href='impressum.php'  target='_blank'>Impressum</a> 
					</div>
				</div>
			</body>
		</html>"; 
	}

	function test_session($step){

		//Schutz vor Sessionübernahme durch andere IPs
		if(isset($_SERVER["REMOTE_ADDR"])){
			//Kontrolle ob Session von einer anderen IP gekapert wurde.
			if(isset($_SESSION["REMOTE_ADDR"])){
				if($_SESSION["REMOTE_ADDR"]!=$_SERVER["REMOTE_ADDR"]){
					echo create_header("Fehler", "","","","");
					$msg="Fehler, Client-IP der Session wurde verändert!<br>Abbruch!";
					create_footer($msg);
					exit(0);
				}
			}
			else{
				$_SESSION["REMOTE_ADDR"]=$_SERVER["REMOTE_ADDR"];
			}
		}
		else{
			echo create_header("Fehler", "","","","");
			$msg="Interner Fehler, kann Client-IP nicht ermitteln<br>Abbruch!";
			create_footer($msg);
			exit(0);
		}

		//Test ob die allgemeinen Einstellungen geladen wurden
		if(!isset($_SESSION["settings"])){
				echo create_header("Fehler", "","","","");
				echo "<div align='center' id='main'>
						<h3> Bitte beginnen Sie von vorne! </h3>";
				print_button("index.php", "button_check_data", "zurück");
				echo "		</div>";
				create_footer("");

				exit(0);

		}

		$back2start=false;
		if(isset($_SESSION["step"])){
			if($_SESSION["step"]>=($step-1)){
				if($_SESSION["step"]==($step-1)) $_SESSION["step"]++;
			}
			else{
				echo "Fehlerhafte Reihenfolge<br>";
				$back2start=true;
			}
		}
		else{
			echo "Datenfehler<br>";
			$back2start=true;
		}
		
		if($back2start==true){
			echo "<a href='index.php'>Neue Eingabe</a><br>";
			exit(0);
		}

	}


	function create_thumb($filename,$thumbfilename){
		//Lade Bild
		$loaded_image=imagecreatefromfile($filename);



				//echo "a:  ".imagetype($filename) ."<br>";
				//preecho($loaded_image);

		$img=$loaded_image["img"];

		//Berechne Zoomfaktor
		$zoom_data=getzoomfactor($filename,  200, 200, 0);
				//preecho($zoom_data);
		//Zoom
		$new_picture=zoompicture($img,$zoom_data);

		//Speicher Bild
		imagetofile($thumbfilename,  "jpg" , $new_picture, 10000, 60, 0);
	}

	function imagepath($filename){
		$ret=array();
		$lastslash=strrpos($filename,DIRECTORY_SEPARATOR);
		$ret["filepath"]=substr ( $filename ,  0, ($lastslash+1) );
		$ret["filename"]=substr ( $filename ,  ($lastslash+1) );
		return $ret;
	}


	function print_png($im){
		header("Content-type: image/png");
		imagepng($im);
		imagedestroy($im);
	}


	function print_message_picture($width, $height, $message){
		Header("Content-Type: image/png");
		# Hier wird der Header gesendet, der später die Bilder "rendert" ausser png kann auch jpeg dastehen
		$img = ImageCreate($width, $height); # Hier wird das Bild einer Variable zu gewiesen
		$red = ImageColorAllocate($img, 255, 0, 0); # Hier wird der Variable $black die Farbe schwarz zugewiesen
		# Die drei nullen bestehen aus den RGB-Parametern. 255, 0, 0 wäre z.B. rot. ($img muss am Anfang stehen)
		ImageFill($img, 0, 0, $red); # Hier wird mit ImageFill() das Bild gefüllt an den Koordinaten 0 und 0 mit der Variable $black, also Schwarz
		$white = ImageColorAllocate($img, 255, 255, 255);
		ImageString($img, 5, floor($width/10), floor($height/2), $message, $white); 
		ImagePNG($img); # Hier wird das Bild PNG zugewiesen 
	}

	function read_classes_from_csv($classes_filename, $expanded=false){
		$data=array();
		$student=array();
		if( $fp = @fopen ($classes_filename, "r") ){
			while (!feof($fp)) {
				$buffer = fgets($fp);
				$d= explode(";", $buffer);
				if(sizeof($d)>=1){
					$class=trim( $d[0] );
					//Nur wenn Klassenbezeichnung mindestens 1 Zeichen hat.
					if(strlen($class)>0){
						//Keine Kommentare als Klasse übernehmen
						if($class{0} != "#"){
							if( strlen($class) >= 2 ){
								if($expanded==true ){
									if(sizeof($d)>=2) $validity=trim($d[1]);
									else $validity=null; //Wenn keine Angabe= 1 Jahr
									$class_data=array();
									$class_data["validity"]=$validity;
									$data["$class"]=$class_data;
								}
								else{
									$data[]=$class;
								}
							}
						}
					}
				}
			}
			fclose ($fp);
		}
		else{
			echo "Can not open file: $classes_filename<br>\n";
		}
		if($expanded==false )sort($data);
		return $data;
	}

	function last_page(){
		//Aufrufende Seite speichern
		$self=$_SERVER['PHP_SELF'];
		if(isset($_SESSION["last_page"]) AND isset($_SESSION["act_page"])){
			if($_SESSION["act_page"]!=$self){
				$_SESSION["last_page"]=$_SESSION["act_page"];
			}
		}
		$_SESSION["act_page"]=$self;
		if( !isset($_SESSION["last_page"]) ){
			$_SESSION["last_page"]="index.php";
		}
		return $_SESSION["last_page"];
	}

	function read_pictures_in_dir($path){
		$handle = opendir($path); 
		$pictures=array(); 
		//Überprüfe ob der Pfad verfügbar ist
		if (false === $handle) {
			return null;
		}
		//Durchlaufe alle Einträge in dem Path
		while (false !== ($file = readdir($handle))) {
		 
			if (  !is_dir($path . $file)  AND !($file == '.' OR $file == '..' )) {
				if(imagetype($file)!= "UNKNOWN") $pictures[]=$file;
			}
		}
		closedir($handle);
		return $pictures;
	}

	function read_subdirs($path){
		$handle = opendir($path); 
		$subdirs=array(); 
		//Überprüfe ob der Pfad verfügbar ist
		if (false === $handle) {
			return null;
		}
		//Durchlaufe alle Einträge in dem Path
		while (false !== ($file = readdir($handle))) {
		 
			if (is_dir($path . $file)  AND !($file == '.' OR $file == '..' )) {
				$subdirs[]=$file;
			}
		}
		closedir($handle);
		sort($subdirs);
		return $subdirs;
	}

	function noconv($s){
		return $s;
	}
	
	
	
	function read_student_csv($csv_file){
		$data=array();
		$student=array();
		if( $fp = @fopen ($csv_file, "r") ){
			while (!feof($fp)) {
				$buffer = fgets($fp);
				$d= explode(",", $buffer);
				if(sizeof($d)>=4){
					$student["given_name"]=$d[0];
					$student["last_name"]=$d[1];
					$student["birthday"]=$d[2];
					$student["class"]=$d[3];
					$student["pic_small"]=$d[4];
					$student["pic_big"]=$d[5];
					$class=$d[3];
					if(!isset($data[$class]))$data[$class]=array();
					$data[$class][]=$student;
				}
			}
			fclose ($fp);
		}
		else{
			echo "Can not open file: $csv_file<br>\n";
		}
		return $data;
	}
	

	function write_bbsplanung_data_csv($given_name, $last_name, $birthday, $class){

		$conv="utf8_decode";
		$conv="noconv";
	
		//$line="$given_name,,$birthday,$class\r\n";
		$line='"73477;"'.$class.'";;;;"'.$last_name.'";"'.$given_name.'";'.$birthday.' 00:00:00;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;'."\r\n";

		$filename=$_SESSION["bbs_plan"]."SIL_EXT.txt";


		$fp = fopen($filename, 'a');
		fwrite($fp,$line);
		fclose($fp);
	}
	
	function write_data_csv($given_name, $last_name, $birthday, $class, $filename_small, $filename_big){

		$conv="utf8_decode";
		$conv="noconv";


		$daten = array();
		$daten[0]=$conv($given_name);
		$daten[1]=$conv($last_name);
		$daten[2]=$conv($birthday);
		$daten[3]=$conv($class);	
		$daten[4]=$conv($filename_small);
		$daten[5]=$conv($filename_big);
		$daten[6]=$conv(date("Y-m-s_H:i:s"));
		$daten[7]=$conv($_SESSION["REMOTE_ADDR"]);

		$line="";
		foreach($daten as $key => $value){
			$line.=$value.",";
		}
		$line.="\r\n";

		//Schreibe in Datei für alle Schüler
		$filename_all='data.csv';
		if( isset($_SESSION["path_all"]) ) $filename_all=$_SESSION["path_all"].$filename_all;
		$fp = fopen($filename_all, 'a');
		fwrite($fp,$line);
		fclose($fp);

		//Schreibe in Datei für eine Klasse (small)
		$filename_small='data.csv';
		if( isset($_SESSION["path_small"]) ) $filename_small=$_SESSION["path_small"].$filename_small;
		$fp = fopen($filename_small, 'a');
		fwrite($fp,$line);
		fclose($fp);

		//Schreibe in Datei für eine Klasse (big)
		$filename_big='data.csv';
		if( isset($_SESSION["path_big"]) ) $filename_big=$_SESSION["path_big"].$filename_big;
		$fp = fopen($filename_big, 'a');
		fwrite($fp,$line);
		fclose($fp);


	}

	function cleanup_filename ($str_filename) {
		//$arr_before = array('Ü',  'Ö',  'Ä', 'ü',  'ö',  'ä',  'ß',  'é',  'ê',  'è');
		//$arr_after  = array('Ue', 'Oe', 'Ae','ue', 'oe', 'ae', 'ss', 'e',  'e',  'e');
		//$str_filename = str_replace($arr_before, $arr_after, $str_filename);
		//$str_filename = preg_replace("/[^a-z0-9-.]/", '-', strtolower ($str_filename));
		
		//Anpassung an BBS Planung
		//Zeichen bleiben erhalten a-z,ä,ö,ü,ß,' '(Leerzeichen), Groß-/Kleinschreibung
		//$arr_before = array( 'é',  'ê',  'è');
		//$arr_after  =    array( 'e',  'e',  'e');
		//$str_filename = str_replace($arr_before, $arr_after, $str_filename);

		return $str_filename;
	}

	function create_birthday_bbs_planung(){
		$day=$_SESSION["birth_day"];
		if( strlen($day)==1 ) $day="0".$day;
		$month=$_SESSION["birth_month"];
		if( strlen($month)==1 ) $month="0".$month;
		$year=$_SESSION["birth_year"];
		if( strlen($year)==4 ) $year=$year%100;
		if($year<10) $year="0".$year;
		return "$day.$month.$year";
	}

	function create_data_path($prefix,$path,$msg=null){

		if( !file_exists($prefix) ){
			echo "Fehler: Basispath '$prefix' existiert nicht!<br>";
		}
		else{
			$paths=explode("/",$path);
			foreach($paths AS $p){
				//Keine Verzeichnisse ohne Namen erstellen
				if(strlen($p)>0){
					$prefix=$prefix."/".$p;
					//Entferne eventuell vorkommende "Doppel Slashes "
					$prefix = preg_replace('/([^:])(\/{2,})/', '$1/', $prefix); 
					if($msg!==null) echo "<b>$msg</b><br>"; 
					echo "Prüfe:  Verzeichnis '$prefix'!<br>"; 
					if( !file_exists($prefix) ){
						@mkdir($prefix, 0770);
					}
					if( !file_exists($prefix) ){
						echo "Fehler: Konnte Verzeichnis '$prefix' nicht erstellen!<br>"; 
					}
				}
			}
		}
	
	}



	function create_data_paths($prefix){
		$prefix.="/";
		$settings=$_SESSION["settings"];
		umask(0002);
		
		//Verzeichnis für die fertigen Klassenfotos
		create_data_path($prefix,$settings["target_image_file_path"], "Verzeichnis für die fertigen Klassenfotos");

		//Verzeichnis für den Upload von Klassenfotos
		create_data_path($prefix,$settings["images_school_classes"], "Verzeichnis für den Upload von Klassenfotos");
		
		//Verzeichnis für temporäre Daten
		create_data_path($prefix,$settings["temp_image_file_path"], "Verzeichnis für temporäre Daten");
		
		//Verzeichnis für Vergleichsbilder (BBS Plan)
		create_data_path($prefix,$settings["images_matching"], "Verzeichnis für Vergleichsbilder (BBS Plan)");
		
		
		/* 
		//Erzeuge BBS-Plan Import Datei
		echo "Erzeuge BBS-Plan Import Datei<br>";
		$line='"SNR";"KL_NAME";"LFD";"STATUS";"NR_SCHÜLER";"NNAME";"VNAME";"GEBDAT";"GEBORT";"STR";"PLZ";"ORT";"TEL";"FAX";"LANDKREIS";"GESCHLECHT";"KONF";"STAAT";"FAMSTAND";"SFO";"TAKURZ";"KLST";"ORG";"DAUER";"TAKLSTORG";"SFOTEXT";"TALANG";"ORG_N";"A";"BG";"BG_SFO";"BG_BFELD";"BG_FREI";"BG_KLST";"BG_ORG";"BG_DAUER";"KO";"EINTR_DAT";"AUSB_BEGDAT";"A_DAUER";"A_ENDEDAT";"ANRECH_BGJ";"WIEDERHOL";"ABSCHLUSS";"HERKUNFT";"FH_Z";"UM";"A_AMT";"BETRAG";"E_ANREDE";"E_NNAME";"E_VNAME";"E_STR";"E_PLZ";"E_ORT";"E_TEL";"E_FAX";"E_ANREDE2";"E_NNAME2";"E_VNAME2";"E_STR2";"E_PLZ2";"E_ORT2";"E_TEL2";"E_FAX2";"BETRIEB_NR";"BETRIEB_NR2";"BEMERK";"KENNUNG1";"KENNUNG2";"KENNUNG3";"DATUM1";"DATUM2";"K1";"K_NR1";"K2";"K_NR2";"K3";"K_NR3";"K4";"K_NR4";"K5";"K_NR5";"K6";"K_NR6";"K7";"K_NR7";"K8";"K_NR8";"K9";"K_NR9";"K10";"K_NR10";"K11";"K_NR11";"K12";"K_NR12";"K13";"K_NR13";"K14";"K_NR14";"K15";"K_NR15";"ZU";"MARKE";"FEHLER";"LDK";"HER_ZUSATZ";"E_LDK";"E_LDK2";"BETRIEB_NR3";"BETRIEB_NR4";"KENNUNG4";"KENNUNG5";"KENNUNG6";"SNR1";"SNR2";"LDK_Z";"BETRAG_G";"AS";"BAFOEG";"LML1";"BUDGET_TH";"BUDGET_FP";"KONF_TEXT";"A_BEZIRK";"P_FAKTOR";"SCHULPFLICHT";"N_DE";"HER_B";"BL_SOLL";"LM_M";"LM_Z";"LM_DAT";"EMAIL";"E_EMAIL";"E_EMAIL2";"IDENT";"TEL_HANDY";"TAKLSTORG_ERST"'."\r\n";
		$filename=$path_all."SIL_EXT.txt";

		$fp = fopen($filename, 'a');
		fwrite($fp,$line);
		fclose($fp);
		chmod($filename, 0660);
		*/
	}	

	function pathSize($dir) {
		$filesize = 0;
		if(is_dir($dir)) {
			if($fp = @opendir($dir)) {
				while( $filename = readdir($fp) ) {
					if( ($filename != '.') AND ($filename != '..')){
						//echo "check: '$dir/$filename'<br>";
						if (is_dir($dir."/".$filename ) === true){
                					$filesize +=  pathSize($dir."/".$filename);
           					}
            					else{
                					$filedata = stat($dir."/".$filename);
							$filesize += $filedata[7];
            					} 

						
					}
				}
				return $filesize; /*Gesamtgr��e des Inhaltes des Verzeichnisses $dir in Bytes*/
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}


	function create_paths($class, $settings, $pathPrefix=""){
		umask(0002);
		
		$path_all=$pathPrefix."/".$settings["target_image_file_path"];
		$path_all = str_replace("//","/",$path_all);
		if( !file_exists($path_all) ){
			if(!@mkdir($path_all, 0770)){
				echo "Error: Can't create folder '$path_all'<br>";
			}
		}

		$path_class=$path_all."/".$class;
		$path_class = str_replace("//","/",$path_class);
		if( !file_exists($path_class) ){
			if(!@mkdir($path_class, 0770)){
				echo "Error: Can't create folder '$path_class'<br>";
			}
		}

		$_SESSION["path_all"]=$path_all;		
		$_SESSION["path_class"]=$path_class;
	}	


	function copy_pic($filename_old, $filename_new, $debug=0 ){

		//Dateikonvertierung für das Dateisystem
		$conv="noconv";

		//Bild kopieren
		if(!copy ( $filename_old , $conv($filename_new))){
			return false;
		}
		umask(0002);
		chmod($conv($filename_new), 0660);			

		if($debug!=0) echo "filename_old:$filename_old---filename_new:$filename_new";
		return true;
	}

/*
	function copy_pic($filename_old, $filename_new, $big_small, $class, $debug ){
		global $settings;
		$img_type=".".imagetype($filename_old);
	
		$path_all = $_SESSION["path_all"];		
		$path_small = $_SESSION["path_small"];
		$path_small_all = $_SESSION["bbs_plan"];
		$path_big = $_SESSION["path_big"];


		//$conv="utf8_decode";
		$conv="noconv";
		//$conv="utf8_encode";



		if($big_small=="SMALL"){

		
			//In einen gemeinsamen Ordnern
//			$filename_together=$path_small_all.cleanup_filename($filename_new)."_".$class.$img_type;
//			if(!copy ( $filename_old ,  $conv($filename_together))){
//				$filename_together="";
//			}
		
		
			//In Klassen-Ordnern
			$filename_together=$path_small.cleanup_filename($filename_new).$img_type;
			if(!copy ( $filename_old , $conv($filename_together))){
				$filename_together="";
			}
			umask(0002);
			chmod($conv($filename_together), 0660);			
			//rename ( $filename_old ,  $settings["target_image_file_path"].$filename_new.$img_type);
		}
		else{
			$filename_together=$path_all.cleanup_filename($filename_new)."_".$class.$img_type;
//			if(!copy ( $filename_old ,  $conv($filename_together))){
//				$filename_together="";
//			}
			//In Klassen-Ordnern
			$filename=$path_big.cleanup_filename($filename_new).$img_type;
			if(!copy ( $filename_old ,  $conv($filename))){
				$filename_new="";
			}
			umask(0002);
			chmod($conv($filename), 0660);
			//rename ( $filename_old ,  $settings["target_image_file_path"].$filename_new.$img_type);
		}
		
		if($debug!=0) echo "filename_old:$filename_old---filename_new:$filename_new";
		return $filename_together;
	}
*/

	function zoompicture($img_source, $zoom_data ){
		$image_width_new=$zoom_data["image_width_new"];
		$image_height_new=$zoom_data["image_height_new"];
		$image_width_org=$zoom_data["image_width_org"];
		$image_height_org=$zoom_data["image_height_org"];
		$img_new = ImageCreateTrueColor( $image_width_new,  $image_height_new );
		imagecopyresampled($img_new , $img_source, 0, 0, 0, 0,  $image_width_new, $image_height_new, $image_width_org, $image_height_org);

		return $img_new;
	}


	function croppicture($img_source, $x ,$y ,$w ,$h,$image_width_new,  $image_height_new, $zoom_factor, $debug){
		if($debug!=0){
			echo "Crop picure: x:$x-y:$y-w:$w-h:$h-zoom:$zoom_factor-image_width_new:$image_width_new-image_height_new:$image_height_new<br>\n";
		}
		$x=floor($x*$zoom_factor);
		$y=floor($y*$zoom_factor);
		$w=floor($w*$zoom_factor);
		$h=floor($h*$zoom_factor);
		if($image_width_new==null) $image_width_new=$w;
		if($image_height_new==null) $image_height_new=$h;	
		if($debug!=0){
			echo "Crop picure: x:$x-y:$y-w:$w-h:$h-zoom:$zoom_factor-image_width_new:$image_width_new-image_height_new:$image_height_new<br>\n";
		}
	

		$img_new = ImageCreateTrueColor( $image_width_new,  $image_height_new );
		imagecopyresampled($img_new,$img_source,0,0,$x,$y,$image_width_new,$image_height_new,$w,$h);
		return $img_new;
	}

	/****	getzoomfactor($filename,  $max_width, $max_height, $debug)
	**
	**		Berechnet den gewünschten Zoomfactor
	**
	**		Rückgabewerte (Array):
	**			zoom_factor => float
	**			image_width_org => int
	**			image_height_org => int
	**			image_width_new => int (round)
	**			image_height_new => int (round)
	**			error => 0: kein Fehler oder 1: Fehler
	***/

	function getzoomfactor($filename,  $max_width, $max_height, $debug){
		
		//Rückgabewert
		$ret=array();
		$ret["zoom_factor"]="1";
		
		//Bild laden
		$imgtype=imagetype($filename);
		if(@$image_size=getimagesize($filename)){

			$image_width = $image_size[0];
			$image_height = $image_size[1];
			$ret["image_width_org"]=$image_width;
			$ret["image_height_org"]=$image_height;
			
			
			//Load image
			$img_source = imagecreatefromfile($filename);
				
			if($debug>0) echo "image_width:$image_width-image_height:$image_height<br>";
			
			if($image_width > $max_width OR $image_height>$max_height){

				//Calc factor
				$fx=round($image_width / $max_width,8);
				$fy=round($image_height / $max_height,8);
				
				$f=max($fx,$fy);

				if($debug>0) echo "image_width:$image_width-image_height:$image_height<br>";
				
				$ret["zoom_factor"]=$f;

			}
			$ret["image_width_new"]=floor($image_width/$ret["zoom_factor"]);
			$ret["image_height_new"]=floor($image_height/$ret["zoom_factor"]);
			$ret["error"]="0";
		}
		else{
			$ret["error"]="1";
		}
		return $ret;
	}

	function imagechangeresolution($img_source, $image_width, $image_height,  $zoom_factor, $debug){
		$image_width_new=floor($image_width/$zoom_factor);
		$image_height_new=floor($image_height/$zoom_factor);
		$img_dst = ImageCreateTrueColor( $image_width_new, $image_height_new );
		if($debug!=0) echo "image_width_org:$image_width, image_width_new:$image_width_new,  image_height_org:$image_height, image_height_new:$image_height_new, zoom_factor:$zoom_factor";
		imagecopyresampled($img_dst,$img_source,0,0,0,0,$image_width_new,$image_height_new,$image_width,$image_height);
		return $img_dst;
	}
	
	function imagesetresolution($filename,  $zoom_factor){
		
		//Rückgabewert
		$ret=array();
		$ret["image"]="";
		$ret["error"]="";
		
		//Bildauflösung bestimmen
		$image_size=getimagesize($filename);
		$image_width = $image_size[0];$img_source = imagecreatefromfile($filename);
		$image_height = $image_size[1];
		
		//Load image
		$img_source = imagecreatefromfile($filename);
			
		//Calc new resolution
		$image_width_new=floor($image_width/$zoom_factor);
		$image_height_new=floor($image_height/$zoom_factor);
			
		if($debug>0) echo "image_width:$image_width-image_height:$image_height<br>";
			
		$img_dst = ImageCreateTrueColor( $image_width_new, $image_height_new );

		//Resample image
		if(!imagecopyresampled($img_dst,$img_source,0,0,0,0,$image_width_new,$image_height_new,$image_width,$image_height)) {
			$ret["error"] = "Error in function imagefilecrop: can not use imagecopyresampled()<br>";
			$ret["image"] = $img_source;
		}
		else{
			$ret["error"]="";
			$ret["image"] = $img_dst;
		}
				
		return $ret;
	}
/*
	function imagesetresolution($filename,  $max_width, $max_height){
		$imgtype=imagetype($filename);
		$image_size=getimagesize($filename);
		$image_width = $image_size[0];
		$image_height = $image_size[1];

		if($image_width > $max_width OR $image_height>$max_height){

			//Calc factor
			$fx=round($image_width / $max_width,6);
			$fy=round($image_height / $max_height,6);
			$f=max($fx,$fy);
			
			//Calc new resolution
			$image_width=floor($image_width/$f);
			$image_height=floor($image_height/$f);
			
			//Load image
			$img_r = imagecreatefromfile($filename);
			$dst_r = ImageCreateTrueColor( $image_width, $image_height );

			//Resample image
			if(!imagecopyresampled($dst_r,$img_r,0,0,0,0,$image_width,$image_height,$image_width,$image_height)) {
				echo "Error in function imagefilecrop: can not use imagecopyresampled()<br>";
				return 1;
			}
			else{
				imagetofile($filename, $imgtype, $dst_r);
			}
				
			return $f;
		}
		else{
			return 1;
		}
		
	}imagetofile
*/

	

	/****	imagetofile($target_filename, $imgtype, $img, $target_filesize, $quality, $debug)
	**
	**	Speichert ein Bild in einer Datei
	** 
	**	params:
	**		target_filename: 	Dateiname der Zieldatei
	** 		imgtype:			jpg,PNG
	** 		img:				Bild Resource
	** 		target_filesize	Maximale Dateigröße
	** 		quality				Maximale Qualität
	** 		debug				Debug-Level
	** 
	**	return: nothing
	**/

	function imagetofile($target_filename, $imgtype, $img, $target_filesize, $quality, $debug){
		if($debug!=0) echo "imagetofile($target_filename, $imgtype, $img, $target_filesize, $quality, $debug)<br>\n";
		if($imgtype=="jpg"){
			$imagefunction="imagejpeg";
		}
		else if($imgtype=="PNG"){
			$imagefunction="imagepng";
		}
		else{
			$imagefunction="imagejpeg";
		}

		do{
			ob_start();
			$imagefunction($img, null, $quality);
			$jpeg_file_contents = ob_get_contents();
			ob_end_clean();
			$filesize=strlen($jpeg_file_contents);
			if($debug!=0) echo "len:$filesize-quality:$quality<br>\n";
			$quality-=5;
		}while($filesize>=$target_filesize AND $quality>=0);



		//Öffne Ziel-Bilddatei
		$fp = fopen("$target_filename","w");
		fwrite($fp, $jpeg_file_contents);
		fclose($fp);
		umask(0002);
		chmod($target_filename, 0660);
	}
	

/*
	function imagefilecrop($filename, $x, $y, $weight, $height){
		$imgtype=imagetype($filename);
		$img_r = imagecreatefromfile($filename);
		$dst_r = ImageCreateTrueColor( $weight, $height );
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		if(!imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$weight,$height,$weight,$height)) {
			echo "Error in function imagefilecrop: can not use imagecopyresampled()<br>";
			return 1;
		}
		else{
			imagetofile($filename, $imgtype, $dst_r);
		}

	}
*/	


	function filter_opacity( &$img, $opacity ) //params: image resource id, opacity in percentage (eg. 80)
        {
            if( !isset( $opacity ) )
                { return false; }
            $opacity /= 100;
           
            //get image width and height
            $w = imagesx( $img );
            $h = imagesy( $img );
           
            //turn alpha blending off
            imagealphablending( $img, false );
           
            //find the most opaque pixel in the image (the one with the smallest alpha value)
            $minalpha = 127;
            for( $x = 0; $x < $w; $x++ )
                for( $y = 0; $y < $h; $y++ )
                    {
                        $alpha = ( imagecolorat( $img, $x, $y ) >> 24 ) & 0xFF;
                        if( $alpha < $minalpha )
                            { $minalpha = $alpha; }
                    }
           
            //loop through image pixels and modify alpha for each
            for( $x = 0; $x < $w; $x++ )
                {
                    for( $y = 0; $y < $h; $y++ )
                        {
                            //get current alpha value (represents the TANSPARENCY!)
                            $colorxy = imagecolorat( $img, $x, $y );
                            $alpha = ( $colorxy >> 24 ) & 0xFF;
                            //calculate new alpha
                            if( $minalpha !== 127 )
                                { $alpha = 127 + 127 * $opacity * ( $alpha - 127 ) / ( 127 - $minalpha ); }
                            else
                                { $alpha += 127 * $opacity; }
                            //get the color index with new alpha
                            $alphacolorxy = imagecolorallocatealpha( $img, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha );
                            //set pixel with the new color + opacity
                            if( !imagesetpixel( $img, $x, $y, $alphacolorxy ) )
                                { return false; }
                        }
                }
            return true;
        }

	/****	imagecreatefromfile
	**		Läd eine Bilddatei (PNG oder jpg)
	**	
	**		Rückgabewert:
	**			imgtype/String:	PNG,jpg
	**			image_width/int: 	0-n
	**			image_height/int: 0-n
	**			error: 0 Kein Fehler/Sonst Fehelrtext
	**			img/Resource: Bildinhalt
	**/

	function imagecreatefromfile($filename){
		$retval=array();
		$imgtype=imagetype($filename);
		$retval["imgtype"]=$imgtype;
		
		//Auflösung ermitteln
		$image_size=getimagesize($filename);
		$retval["image_width"] = $image_size[0];
		$retval["image_height"] = $image_size[1];
		
		$retval["error"]=0;
		if($imgtype=="jpg"){
			$img = imagecreatefromjpeg($filename);
			$retval["img"]=$img;
		}
		else if($imgtype=="PNG"){
			//Problem mit Transparenzen
			$img = imagecreatefrompng($filename); 
			$width = imagesx($img);
			$height = imagesy($img);
			$background = imagecreatetruecolor($width,$height);
			$white = imagecolorallocate($background, 255, 255, 255);
			imagefill($background,0,0,$white);
			imagecopyresampled($background,$img,0,0,0,0,$width,$height,$width,$height); 
			//Ab hier sollten Transparenzen weiß sein -> sonst schwarz
			$retval["img"]=$background;
		}
		else{
			$retval["error"]="Error in function imagecreatefromfile: unknown image file format<br>";
			$retval["img"]="";
		}
		return $retval;
	}

/*
	function imagerot($filename, $degrees){
		//echo "Drehe bild $filename um $degrees °<br>";
		if(imagetype($filename)=="PNG"){
			if($img = imagecreatefrompng($filename)){
				$img = imagerotate($img, (360-$degrees),0);
				imagepng($img, $filename);
				imagedestroy($img);
			}
			else{
				echo "error:a11<br>";
			}
		}
		else if(imagetype($filename)=="jpg"){
			if($img = imagecreatefromjpeg($filename)){
				$img = imagerotate($img, (360-$degrees),0);
				imagejpeg($img, $filename);
				imagedestroy($img);
			}
			else{
				echo "error:a13<br>";
			}
		}		
		else{
			echo "error:a12<br>";
		}
	}
*/



	function imagetype($filename){
		$lastpoint=strrpos($filename,".");
		$filetype=substr ( $filename ,  ($lastpoint+1) );
		$filetype=trim(strtolower($filetype));
		//echo "function imagetype:filetype:$filetype<br>";
		if( $filetype=="png" ){
			return "PNG";
		}
		if( $filetype=="jpg" OR $filetype=="jpeg"){
			return "jpg";
		}
		return "UNKNOWN";
	}

	function session_test($step){
		//Test if Session is valid
		$back2start=false;
		if(isset($_SERVER["REMOTE_ADDR"])){
			//Kontrolle ob Session von einer anderen IP gekapert wurde.
			if(isset($_SESSION["REMOTE_ADDR"])){
				if($_SESSION["REMOTE_ADDR"]!=$_SERVER["REMOTE_ADDR"]){
					echo "Fehler, Client-IP der Session wurde verändert!<br>";
					$back2start=true;
				}
			}
			else{
				$_SESSION["REMOTE_ADDR"]=$_SERVER["REMOTE_ADDR"];
			}
		}
		else{
			echo "Interner Fehler, kann Client-IP nicht ermitteln<br>";
			$back2start=true;
		}
		
		if(isset($_SESSION["step"])){
			if($_SESSION["step"]>=($step-1)){
				if($_SESSION["step"]==($step-1)) $_SESSION["step"]++;
			}
			else{
				echo "Fehlerhafte Reihenfolge<br>";
				$back2start=true;
			}
		}
		else{
			echo "Datenfehler<br>";
			$back2start=true;
		}
		
		if($back2start==true){
			echo "<a href='index.php'>Neue Eingabe</a><br>";
			exit(0);	
		}
	}


	//================================ FUNKTIONEN ============================
	function get_user_data_from_post(){
		if(isset($_POST["given_name"])){
			$_SESSION["given_name"]=clean_string($_POST["given_name"]);
		}
		if(isset($_POST["last_name"])){
			$_SESSION["last_name"]=clean_string($_POST["last_name"]);
		}
		if(isset($_POST["birth_day"])){
			$_SESSION["birth_day"]=clean_string($_POST["birth_day"]);
		}
		if(isset($_POST["birth_month"])){
			$_SESSION["birth_month"]=clean_string($_POST["birth_month"]);
		}
		if(isset($_POST["birth_year"])){
			$_SESSION["birth_year"]=clean_string($_POST["birth_year"]);
		}
		if(isset($_POST["class"])){
			$_SESSION["class"]=clean_string($_POST["class"]);
		}	

		if(isset($_POST["autocorrection"])){
			if($_POST["autocorrection"]=="on"){
				$_SESSION["autocorrection"]=true;
			}
			else{
				$_SESSION["autocorrection"]=false;
			}
		}	
	}
	
	function print_button($target, $name, $value){
		echo create_button($target, $name, $value);
	}

	function create_button($target, $name, $value){
		return "
			<form action='$target' method='post' >
				<input type='submit' name='$name' value='$value' id='$name'>
			</form>";	
	}
	

	function class_validity_years($class, $classes){
		$validity=-1;  //Wert auf Fehler setzen und anschließend suchen
		if( $class!=null AND $classes!=null){
			if(is_array($classes)){
				if(isset($classes[$class])){
					if( isset($classes[$class]["validity"]) ){
						$validity=$classes[$class]["validity"];
					}
				}
			}
		}
		return $validity;
	}



	function ausweisAblauf( $validity=1){
	
		if( $validity<=0 OR $validity>99 ) $validity=1;
		
		$validity_full_years=floor($validity);
		$validiy_rest=$validity-$validity_full_years;
		
		//echo "a: $validity - b:$validity_full_years - $c:$validiy_rest <br>";
		
		if( $validiy_rest == 0 ){
			$monat=date("n");
			$jahr=date("o");
			if($monat>=5) $jahr++;
			$jahr=$jahr+$validity_full_years-1;	
			return "31.07.$jahr";
		}
		else{
			$monat=date("n");
			$jahr=date("o");
			if($monat>=5) $jahr+=2;
			$jahr=$jahr+$validity_full_years-1;	
			return "31.01.$jahr";		
		}
	}


	function print_data_input_formular($action,$classes,$lastpage){
		if($action=="") $action=$_SERVER['PHP_SELF'];
		$given_name="";
		$last_name="";
		$birth_day="_";
		$birth_month="-";
		$birth_year="-";
		$class="";
		$autocorrection_on=" checked ";
		$autocorrection_off="";
		$alter="";
		if(isset($_SESSION["given_name"])) $given_name=$_SESSION["given_name"];
		if(isset($_SESSION["last_name"])) $last_name=$_SESSION["last_name"];
		if(isset($_SESSION["birth_day"])) $birth_day=$_SESSION["birth_day"];
		if(isset($_SESSION["birth_month"])) $birth_month=$_SESSION["birth_month"];
		if(isset($_SESSION["birth_year"])) $birth_year=$_SESSION["birth_year"];
		if(isset($_SESSION["class"])) $class=$_SESSION["class"];
		if(isset($_SESSION["autocorrection"])){
			if($_SESSION["autocorrection"]==false){
				$autocorrection_on="";
				$autocorrection_off=" checked ";
			}
		}
		if(isset($_SESSION["alter"])){
			if($_SESSION["alter"]>0){
				$alter="<tr><td>Alter:</td><td>".$_SESSION["alter"]." Jahre</td></tr>";
			}
		}

	
		

		echo "
		
		<table>
			<form action='$action' method='post'>
				<tr>
					<td>Vorname:</td>
					<td><input type='text' name='given_name' value='$given_name' size='40' autocomplete='off'".form_error_css($_SESSION["given_name_errno"])."></td>
				</tr>
				<tr>
					<td>Nachname:</td>
					<td><input type='text' name='last_name' value='$last_name' size='40' autocomplete='off'".form_error_css($_SESSION["last_name_errno"])."></td>
				</tr>
				<tr>
					<td>Geburtsdatum: (Tag.Monat.Jahr)</td>
					<td>
						<select name='birth_day' size='1'  autocomplete='off'".form_error_css($_SESSION["birth_day_errno"]).">
							".option_list(1,31,$birth_day)."
						</select>.
						<select name='birth_month' size='1' autocomplete='off'".form_error_css($_SESSION["birth_month_errno"]).">
							".option_list(1,12,$birth_month)."
						</select>.
						<select name='birth_year' size='1' autocomplete='off'".form_error_css($_SESSION["birth_year_errno"]).">
							".option_list(1910,2010,$birth_year)."
						</select>					
					</td>
				</tr>
				<tr>
					<td>Klasse</td>
					<td>
						<select name='class' size='1' ".form_error_css($_SESSION["class_errno"]).">
							<option value='-'>-</option>
							".option_list_from_array($classes,$class)."
						</select>
					</td>
				</tr>
				<tr>
					<td>Autokorrektur:</td>
					<td>
						<input type='radio' name='autocorrection' value='on' $autocorrection_on >an<br>
						<input type='radio' name='autocorrection' value='off' $autocorrection_off >aus
					</td>
				</tr>
				$alter
				<tr>
					<td><input type='submit' name='back' value='zurück'></td>
					<td><input type='submit' name='button_check_data' value='Weiter'></td>
				</tr>
			</form>
		</table>
		";

//print_data_input_formular($_SERVER['PHP_SELF'],$classes.$lastpage);
//		print_button($lastpage, "data_input", "zurück");
	}

	function option_list_from_array($array, $checked){
		$s="";
		foreach($array AS $entry){
			$s.="			<option value='$entry'";
			if($entry==$checked)	$s.=" selected";
			$s.=">$entry</option>\n";

		}
	
		return $s;
	}
	
	function option_list($von, $bis, $checked){
		$s="			<option value='-'";
		if($von==$checked)	$s.=" selected";
		$s.=">-</option>\n";
		while($von<=$bis){
			$s.="			<option value='$von'";
			if($von==$checked)	$s.=" selected";
			$s.=">$von</option>\n";
			$von++;
		}
		return $s;
	}

	function clean_string($string2clean){
		//$notallowed   = array("^", "°", "!","\"","§","$","%","&","/","(",")","=","?" ,"`","²","³","{","[","]","}","\\","+","*","~","\'","#",":",">","<","|","'");
		$notallowed   = array("^", "°", "!","\"","§","$","%","&","/","(",")","=","?" ,"´","`","²","³","{","[","]","}","\\","+","*","~","\'","#",":",">","<","|","'");
		$string2clean = str_replace($notallowed, "", $string2clean);
		$string2clean = str_replace("_", "-", $string2clean);
		$string2clean = str_replace(",", ";", $string2clean);
		$string2clean = preg_replace ('#\s+#' , ' ' , $string2clean);
		$string2clean = preg_replace('/(\\\s){2,}/', '$1', $string2clean);
		$string2clean = trim($string2clean );
		return $string2clean;
	}
	
	function form_error_css($errno){
		if($errno==0){
			return " class='input-box-ok' ";
		}
		else if($errno==1){
			return " class='input-box-unset' ";
		}
		else{
			return " class='input-box-error' ";
		}		
	}
	
	function check_student_data($autocorrection=false){
		get_user_data_from_post();
		$error=array();
		$error["msg"]="";
		$error["value"]=0;


		/*if( isset($_SESSION["autocorrection"]) ){
			if($_SESSION["autocorrection"]==true){
				$autocorrection=true;
				namenszusatz_hinter_vornamen($_SESSION, "given_name", "last_name");
			}
		}*/
		if($autocorrection){
			namenszusatz_hinter_vornamen($_SESSION, "given_name", "last_name");
		}	




		if(isset($_SESSION["given_name"])){
			$given_name=$_SESSION["given_name"];
			if($autocorrection) $_SESSION["given_name"]=ucfirst($_SESSION["given_name"]);//Erster Buchstaben=> Großbuchstabe
			if(strlen($given_name)<2){
				$error["msg"].= "Bitte überprüfen Sie die Eingabe Ihres Vornamens!<br>";
				$_SESSION["given_name_errno"]=2;
				$error["value"]+=1;
			}
			else{
				$_SESSION["given_name_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie Ihren Vornamen ein!<br>";
			$error["value"]+=1;
		}
		if(isset($_SESSION["last_name"])){
			if($autocorrection) $_SESSION["last_name"]=ucwords($_SESSION["last_name"]);//Erster Buchstabe jedes Wortes=> Großbuchstabe
			$last_name=$_SESSION["last_name"];
			if(strlen($last_name)<2){
				$error["msg"].= "Bitte überprüfen Sie die Eingabe Ihres Nachnamens!<br>";
				$_SESSION["last_name_errno"]=2;
				$error["value"]+=2;
			}
			else{
				$_SESSION["last_name_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie Ihren Nachnamen ein!<br>";
			$error["value"]+=2;
		}
		if(isset($_SESSION["birth_day"])){
			$birth_day=$_SESSION["birth_day"];
			if($birth_day<1 || $birth_day > 31){
				$error["msg"].= "Bitte überprüfen Sie den Tag Ihres Geburtsdatums!<br>";
				$_SESSION["birth_day_errno"]=2;
				$error["value"]+=8;			
			}
			else{
				$_SESSION["birth_day_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie den Tag Ihres Geburtsdatums ein!<br>";
			$error["value"]+=4;
		}
		if(isset($_SESSION["birth_month"])){
			$birth_month=$_SESSION["birth_month"];
			if($birth_month<1 || $birth_month > 12){
				$error["msg"].= "Bitte überprüfen Sie den Monat Ihres Geburtsdatums!<br>";
				$_SESSION["birth_month_errno"]=2;
				$error["value"]+=8;			
			}
			else{
				$_SESSION["birth_month_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie den Monat Ihres Geburtsdatums ein!<br>";
			$error["value"]+=8;
		}
		if(isset($_SESSION["birth_year"])){
			$birth_year=$_SESSION["birth_year"];
			if($birth_year<1910 || $birth_year > 2010){
				$error["msg"].= "Bitte überprüfen Sie das Jahr Ihres Geburtsdatums!<br>";
				$_SESSION["birth_year_errno"]=2;
				$error["value"]+=8;			
			}
			else{
				$_SESSION["birth_year_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie das Jahr Ihres Geburtsdatums ein!<br>";
			$error["value"]+=16;
		}
		//Alter berechnen
		if( $_SESSION["birth_day_errno"]==0 AND 	$_SESSION["birth_month_errno"]==0 AND $_SESSION["birth_year_errno"]==0){
			$geburtstag = mktime(0,0,0,$_SESSION["birth_month"],$_SESSION["birth_day"],$_SESSION["birth_year"]);
			$heute=date("Ymd"); 
			$alter=floor((date("Ymd")- date("Ymd", $geburtstag)) / 10000);
			$_SESSION["alter"] = $alter;
		}
		if(isset($_SESSION["class"])){
			if( is_array($_SESSION["class"])  ) $_SESSION["class"] = "";
			$class=$_SESSION["class"];
			if(strlen($class)<2){
				$error["msg"].= "Bitte überprüfen Sie die Eingabe Ihrer Klasse!<br>";
				$_SESSION["class_errno"]=2;
				$error["value"]+=32;
			}
			else{
				$_SESSION["class_errno"]=0;
			}
		}
		else{
			$error["msg"].= "Bitte geben Sie Ihre Klasse ein!<br>";
			$error["value"]+=32;
		}
		
		return $error;
	}
	
	function show_data(){
		$given_name=$_SESSION["given_name"];
		$last_name=$_SESSION["last_name"];
		$birth_day=$_SESSION["birth_day"];
		$birth_month=$_SESSION["birth_month"];
		$birth_year=$_SESSION["birth_year"];
		$class=$_SESSION["class"];	
		$alter=$_SESSION["alter"];
		$ablaufdatum=$_SESSION["ablaufdatum"];		
		echo "Sind die folgenden Daten korrekt?<br>";
		echo "
				<table>
					<tr>
						<td>Vorname:</td>
						<td>$given_name</td>
					</tr>
					<tr>
						<td>Nachname:</td>
						<td>$last_name</td>
					</tr>
					<tr>
						<td>Geburtsdatum:</td>
						<td>$birth_day.$birth_month.$birth_year	</td>
					</tr>
					<tr>
						<td>Klasse</td>
						<td>$class</td>
					</tr>
					<tr>
						<td>Alter</td>
						<td>$alter</td>
					</tr>
					<tr>
						<td><small>Gültig bis:</small></td>
						<td><small>$ablaufdatum</small></td>
					</tr>
				</table>";
	}
	
	function preecho($p){
		echo "<pre>";
		print_r($p);
		echo "</pre>";
	}
	
	//===============================================================================================
if (!function_exists('imagerotate')) {

    /*
        Imagerotate replacement. ignore_transparent is work for png images
        Also, have some standard functions for 90, 180 and 270 degrees.
        Rotation is clockwise
    */

    function imagerotate_rotateX($x, $y, $theta) {
        return $x * cos($theta) - $y * sin($theta);
    }

    function imagerotate_rotateY($x, $y, $theta) {
        return $x * sin($theta) + $y * cos($theta);
    }

    function imagerotate($srcImg, $angle, $bgcolor = 0, $ignore_transparent = 0) {
        $srcw = imagesx($srcImg);
        $srch = imagesy($srcImg);

        //Normalize angle
        $angle %= 360;
        //Set rotate to clockwise
        $angle = -$angle;

        if ($angle == 0) {
            if ($ignore_transparent == 0) {
                imagesavealpha($srcImg, true);
            }
            return $srcImg;
        }

        // Convert the angle to radians
        $theta = deg2rad($angle);

        //Standart case of rotate
        if ((abs($angle) == 90) || (abs($angle) == 270)) {
            $width = $srch;
            $height = $srcw;
            if (($angle == 90) || ($angle == -270)) {
                $minX = 0;
                $maxX = $width;
                $minY = -$height+1;
                $maxY = 1;
            } else if (($angle == -90) || ($angle == 270)) {
                $minX = -$width+1;
                $maxX = 1;
                $minY = 0;
                $maxY = $height;
            }
        } else if (abs($angle) === 180) {
            $width = $srcw;
            $height = $srch;
            $minX = -$width+1;
            $maxX = 1;
            $minY = -$height+1;
            $maxY = 1;
        } else {
            // Calculate the width of the destination image.
            $temp = array(
                imagerotate_rotateX(0, 0, 0 - $theta),
                imagerotate_rotateX($srcw, 0, 0 - $theta),
                imagerotate_rotateX(0, $srch, 0 - $theta),
                imagerotate_rotateX($srcw, $srch, 0 - $theta)
            );
            $minX = floor(min($temp));
            $maxX = ceil(max($temp));
            $width = $maxX - $minX;

            // Calculate the height of the destination image.
            $temp = array(
                imagerotate_rotateY(0, 0, 0 - $theta),
                imagerotate_rotateY($srcw, 0, 0 - $theta),
                imagerotate_rotateY(0, $srch, 0 - $theta),
                imagerotate_rotateY($srcw, $srch, 0 - $theta)
            );
            $minY = floor(min($temp));
            $maxY = ceil(max($temp));
            $height = $maxY - $minY;
        }

        $destimg = imagecreatetruecolor($width, $height);
        if ($ignore_transparent == 0) {
            imagefill($destimg, 0, 0, imagecolorallocatealpha($destimg, 255,255, 255, 127));
            imagesavealpha($destimg, true);
        }

        // sets all pixels in the new image
        for ($x = $minX; $x < $maxX; $x++) {
            for ($y = $minY; $y < $maxY; $y++) {
                // fetch corresponding pixel from the source image
                $srcX = round(imagerotate_rotateX($x, $y, $theta));
                $srcY = round(imagerotate_rotateY($x, $y, $theta));
                if ($srcX >= 0 && $srcX < $srcw && $srcY >= 0 && $srcY < $srch) {
                    $color = imagecolorat($srcImg, $srcX, $srcY);
                } else {
                    $color = $bgcolor;
                }
                imagesetpixel($destimg, $x-$minX, $y-$minY, $color);
            }
        }

        return $destimg;
    }

}






//====================================================================================

	function filename2student($dirname_org, $file_org, $target,  $createTime=-1){
		global $studentnr;
	
		$dirname=convert_string($dirname_org);
		$dirnameex=explode("/",$dirname);
		$i=sizeof($dirnameex)-1;
		if($i>1){
			while( strlen($dirnameex[$i])<1 AND $i>=0){
				$i--;
			}
			$dirname=$dirnameex[$i];
		}
		
		$file=convert_string($file_org);
		
		//Zerteilt die Zeichekette um später Name, Vorname, Gebdatum zu ermitteln
		 $parts=explode("_",trim($file));
		$nr_parts=sizeof($parts);
		if( $nr_parts>=3 ){
			$student=array();

			//Set $createTime
			$student["createTime"]=$createTime;

			$student["lastname"]=$parts[0];
			$student["givenname"]=$parts[1];
			namenszusatz_hinter_vornamen($student,"givenname","lastname", true);


			//Short Name for better matching
			$len_ln=strlen($student["lastname"]);
			if($len_ln>4) $len_ln=4;
			$student["lastnameshort"]=substr($student["lastname"],0,$len_ln);
			//Short Name for better matching
			$len_gn=strlen($student["givenname"]);
			if($len_gn>4) $len_gn=4;
			$student["givennameshort"]=substr($student["givenname"],0,$len_gn);				

			$student["nr"]=$studentnr;
			$birthday=explode(".",$parts[2]);
			$birthday_nr_parts=sizeof($birthday);
			if($birthday_nr_parts >= 3 ){
				//Tag,Monat,Jahr müssen eine Zahl sein!
				if(is_numeric($birthday[0]) AND is_numeric($birthday[1]) AND is_numeric($birthday[2])){
//EINRÜCKEN
				$student["birthday_day"]=$birthday[0];
				$student["birthday_month"]=$birthday[1];
				$student["birthday_year"]=$birthday[2];
				$birthday=$student["birthday_day"].".".$student["birthday_month"].".".$student["birthday_year"];
				//$birthdaylong=$student["birthday_day"].".".$student["birthday_month"].".".$student["birthday_year"];
				$student["birthday"]=$birthday;
				$student["class"]=$dirname;
				//Short Class-Name for better matching
				$len_cl=strlen($student["class"]);
				if($len_cl>4) $len_cl=4;
				$student["classshort"]=substr($student["class"],0,$len_cl);			

				$student["picfile_org"]="$dirname_org/$file_org";
				$student["picfile"]="$dirname/$file";

				//Felder für verschiedene Vergleiche erzeugen
				//=====================================
				//=== name_birthday_class
				$student["name_birthday_class"]=$student["lastname"]."-".$student["givenname"]."-".$birthday."-".$student["class"];
				$student["name_birthday_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthday_class"]);
				 $student["name_birthday_class"]=strtolower( $student["name_birthday_class"]);
				//Felder für verschiedene Vergleiche erzeugen

				//=== name_birthday_classshort
				$student["name_birthday_classshort"]=$student["lastname"]."-".$student["givenname"]."-".$birthday."-".$student["classshort"];
				$student["name_birthday_classshort"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthday_classshort"]);
				 $student["name_birthday_classshort"]=strtolower( $student["name_birthday_classshort"]);
				//Felder für verschiedene Vergleiche erzeugen
				//=== name_birthday
				$student["name_geb"]=$student["lastname"]."-".$student["givenname"]."-".$birthday;
				$student["name_geb"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_geb"]);
				 $student["name_geb"]=strtolower( $student["name_geb"]);
				//=== name_birthday(day_month)_class
				$student["name_birthdayDM_class"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_month"]."-".$student["class"];
				 $student["name_birthdayDM_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayDM_class"]);
				 $student["name_birthdayDM_class"]=strtolower( $student["name_birthdayDM_class"]);
				//=== name_birthday(day_year)_class
				$student["name_birthdayDY_class"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_year"]."-".$student["class"];
				$student["name_birthdayDY_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayDY_class"]);
				 $student["name_birthdayDY_class"]=strtolower( $student["name_birthdayDY_class"]);
				//=== name_birthday(month_year)_class
				$student["name_birthdayMY_class"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_month"]."-".$student["birthday_year"]."-".$student["class"];
				$student["name_birthdayMY_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayMY_class"]);
				 $student["name_birthdayMY_class"]=strtolower( $student["name_birthdayMY_class"]);
				//=== lastname_birthday_class
				$student["lastname_birthday_class"]=$student["lastname"]."-".$birthday."-".$student["class"];
				$student["lastname_birthday_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["lastname_birthday_class"]);
				 $student["lastname_birthday_class"]=strtolower( $student["lastname_birthday_class"]);
				//=== lastname_birthday_class
				$student["givenname_birthday_class"]=$student["givenname"]."-".$birthday."-".$student["class"];
				$student["givenname_birthday_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["givenname_birthday_class"]);
				 $student["givenname_birthday_class"]=strtolower( $student["givenname_birthday_class"]);

				//=== name_birthday(day_month)
				$student["name_birthdayDM"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_month"];
				 $student["name_birthdayDM"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayDM"]);
				 $student["name_birthdayDM"]=strtolower( $student["name_birthdayDM"]);
				//=== name_birthday(day_year)
				$student["name_birthdayDY"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_year"];
				$student["name_birthdayDY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayDY"]);
				 $student["name_birthdayDY"]=strtolower( $student["name_birthdayDY"]);
				//=== name_birthday(month_year)
				$student["name_birthdayMY"]=$student["lastname"]."-".$student["givenname"]."-".$student["birthday_month"]."-".$student["birthday_year"];
				$student["name_birthdayMY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["name_birthdayMY"]);
				 $student["name_birthdayMY"]=strtolower( $student["name_birthdayMY"]);
				//=== birthday_class
				$student["birthday_class"]=$birthday."-".$student["class"];
				$student["birthday_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["birthday_class"]);
				 $student["birthday_class"]=strtolower( $student["birthday_class"]);
				//=== birthday_classshort
				$student["birthday_classshort"]=$birthday."-".$student["classshort"];
				$student["birthday_classshort"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["birthday_classshort"]);
				 $student["birthday_classshort"]=strtolower( $student["birthday_classshort"]);
				//=== birthday
				$student["birthday"]=$birthday;
				$student["birthday"]=preg_replace('/[^A-Za-z0-9.]+/', '', $student["birthday"]);
				 $student["birthday"]=strtolower( $student["birthday"]);

				//=== nameShort_birthday
				$student["nameshort_geb"]=$student["lastnameshort"]."-".$student["givennameshort"]."-".$birthday;
				$student["nameshort_geb"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["nameshort_geb"]);
				 $student["nameshort_geb"]=strtolower( $student["nameshort_geb"]);





				//=== givenname_birthday(day_month)
				$student["givenname_birthdayDM"]=$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_month"];
				 $student["givenname_birthdayDM"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["givenname_birthdayDM"]);
				 $student["givenname_birthdayDM"]=strtolower( $student["name_birthdayDM"]);
				//=== givenname_birthday(day_year)
				$student["givenname_birthdayDY"]=$student["givenname"]."-".$student["birthday_day"]."-".$student["birthday_year"];
				$student["givenname_birthdayDY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["givenname_birthdayDY"]);
				 $student["givenname_birthdayDY"]=strtolower( $student["givenname_birthdayDY"]);
				//=== givenname_birthday(month_year)
				$student["givenname_birthdayMY"]=$student["givenname"]."-".$student["birthday_month"]."-".$student["birthday_year"];
				$student["givenname_birthdayMY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["givenname_birthdayMY"]);
				 $student["givenname_birthdayMY"]=strtolower( $student["givenname_birthdayMY"]);



				//=== lastname_birthday(day_month)
				$student["lastname_birthdayDM"]=$student["lastname"]."-".$student["birthday_day"]."-".$student["birthday_month"];
				 $student["lastname_birthdayDM"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["lastname_birthdayDM"]);
				 $student["lastname_birthdayDM"]=strtolower( $student["lastname_birthdayDM"]);
				//=== lastname_birthday(day_year)
				$student["lastname_birthdayDY"]=$student["lastname"]."-".$student["birthday_day"]."-".$student["birthday_year"];
				$student["lastname_birthdayDY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["lastname_birthdayDY"]);
				 $student["lastname_birthdayDY"]=strtolower( $student["lastname_birthdayDY"]);
				//=== lastname_birthday(month_year)
				$student["lastname_birthdayMY"]=$student["lastname"]."-".$student["birthday_month"]."-".$student["birthday_year"];
				$student["lastname_birthdayMY"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["lastname_birthdayMY"]);
				 $student["lastname_birthdayMY"]=strtolower( $student["lastname_birthdayMY"]);




				//=== nameshort_geb_classshort
				$student["nameshort_geb_classshort"]=$student["lastnameshort"]."-".$student["givennameshort"]."-".$birthday."-".$student["classshort"];
				$student["nameshort_geb_classshort"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["nameshort_geb_classshort"]);
				$student["nameshort_geb_classshort"]=strtolower( $student["nameshort_geb_classshort"]);

				//=== nameshort_classshort
				$student["nameshort_classshort"]=$student["lastnameshort"]."-".$student["givennameshort"]."-".$student["classshort"];
				$student["nameshort_classshort"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["nameshort_classshort"]);
				$student["nameshort_classshort"]=strtolower( $student["nameshort_classshort"]);

				//=== nameShort_class
				$student["nameshort_class"]=$student["lastnameshort"]."-".$student["givennameshort"]."-".$student["class"];
				$student["nameshort_class"]=preg_replace('/[^A-Za-z0-9]+/', '', $student["nameshort_class"]);
				$student["nameshort_class"]=strtolower( $student["nameshort_class"]);


				//ENDE: Felder für verschiedene Vergleiche erzeugen
				
				//=====================================
				//Datensatz speichern
				$_SESSION[$target][$studentnr]=$student;
				$_SESSION[$target."_sortbygeb"][$birthday][$studentnr]=$student;
				$studentnr++;
				}
				else{
					echo "Ignoriere: $dirname/$file";
					if(!is_numeric($birthday[0])) echo " - kann den Tag: '".$birthday[0]."'";
					if(!is_numeric($birthday[1])) echo " - kann den Monat: '".$birthday[1]."'";
					if(!is_numeric($birthday[2])) echo " - kann das Jahr: '".$birthday[2]."'";
					echo " des Geburtstages nicht einlesen <br>";
					
				}
			}
			else{
				echo "Ignoriere: $dirname/$file - Kann Geburtstag nicht einlesen <br>";
			}
		}
		else{
			echo "Ignoriere: $dirname/$file - zu wenige Trennzeichen:".($nr_parts-1)." <br>";
		}
	}

	/*
		$dir=Verzeichnis, welches nach Schülerbildern durchsucht werden soll
		$dirname="MAINDIRECTORY" für Wurzelverzeichnis
		$target="lisa" oder "bbsplan"
		
		Aufruf: getDir($_SESSION["lisa_source"],"MAINDIRECTORY", "lisa");
	*/

	function getDir($dir,$dirname, $target) {
		
		if(is_dir($dir)){
			$directory = opendir($dir);
			while($file = readdir($directory)) {
				if($file != "." && $file != ".."  && $file != "small" ) {
					if(is_dir("$dir/$file")) {
						getDir("$dir/$file",$file, $target);
					} 
					else {
						if( imagetype($file)!="UNKNOWN" && $dirname != "small" && $dirname != "MAINDIRECTORY" ){
							$createTime=filemtime("$dir/$file");
							filename2student($dirname, $file, $target, $createTime); 
						}
					}
				}
			}
			closedir($directory);
		}
	}

	
?>
