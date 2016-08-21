<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout(false); //area = false => auto = folder name	
	
	//=== Nichts cachen - Seite immer neu aufrufen
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store');
	header('Pragma: no-cache');
	//System auf UTF-8 einstellen
	header("Content-Type: text/html; charset=UTF-8");
	




	//Layout einlesen
	$layout="ausweis";
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



?>
<!DOCTYPE html>
<html lang='en'>
	<head>
	<title><?php echo $_SESSION["settings"]["html_title"]; ?></title>
	<link rel="stylesheet" type="text/css"; media="print" href="druck.css">
	<?php echo $layouthead ?>
    <style>
    #main
        {
	    <?php if( isset($menu_visibility)  ) echo $menu_visibility; ?>
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
    </style>

	
</head>

<?php
//Breite eines Stings abschätzen
function strwidth($string){
    
    $tl=0;
    
    $string_laenge = strlen($string);

    //Abstand

    for($i=0; $i < $string_laenge; $i++){
        $string_x = $string{$i};

        if($string_x == " "){
            $tl += 0.3;
        } elseif($string_x == "0"){
            $tl += 0.5;
        } elseif($string_x == "1"){
            $tl += 0.3;
        } elseif($string_x == "2"){
            $tl += 0.5;
        } elseif($string_x == "3"){
            $tl += 0.5;
        } elseif($string_x == "4"){
            $tl += 0.5;
        } elseif($string_x == "5"){
            $tl += 0.5;
        } elseif($string_x == "6"){
            $tl += 0.5;
        } elseif($string_x == "7"){
            $tl += 0.5;
        } elseif($string_x == "8"){
            $tl += 0.5;
        } elseif($string_x == "9"){
            $tl += 0.5;
        } elseif($string_x == "a"){
            $tl += 0.5;
        } elseif($string_x == "b"){
            $tl += 0.5;
        } elseif($string_x == "c"){
            $tl += 0.5;
        } elseif($string_x == "d"){
            $tl += 0.5;
        } elseif($string_x == "e"){
            $tl += 0.48;
        } elseif($string_x == "f"){
            $tl += 0.35;
        } elseif($string_x == "g"){
            $tl += 0.5;
        } elseif($string_x == "h"){
            $tl += 0.54; 
        } elseif($string_x == "i"){
            $tl += 0.3;
        } elseif($string_x == "j"){
            $tl += 0.3;
        } elseif($string_x == "k"){
            $tl += 0.54;
        } elseif($string_x == "l"){
            $tl += 0.3;
        } elseif($string_x == "m"){
            $tl += 0.82;
        } elseif($string_x == "n"){
            $tl += 0.55;
        } elseif($string_x == "o"){
            $tl += 0.54;
        } elseif($string_x == "p"){
            $tl += 0.49;
        } elseif($string_x == "q"){
            $tl += 0.53;
        } elseif($string_x == "r"){
            $tl += 0.3;
        } elseif($string_x == "s"){
            $tl += 0.4;
        } elseif($string_x == "t"){
            $tl += 0.28;
        } elseif($string_x == "u"){
            $tl += 0.41;
        } elseif($string_x == "v"){
            $tl += 0.5;
        } elseif($string_x == "w"){
            $tl += 0.79;
        } elseif($string_x == "x"){
            $tl += 0.49;
        } elseif($string_x == "y"){
            $tl += 0.5;
        } elseif($string_x == "z"){
            $tl += 0.5;
        } elseif($string_x == "ä"){
            $tl += 0.48;
        } elseif($string_x == "ö"){
            $tl += 0.5;
        } elseif($string_x == "ü"){
            $tl += 0.47;
        } elseif($string_x == "A"){
            $tl += 0.71;
        } elseif($string_x == "B"){
            $tl += 0.6;
        } elseif($string_x == "C"){
            $tl += 0.7;
        } elseif($string_x == "D"){
            $tl += 0.62;
        } elseif($string_x == "E"){
            $tl += 0.5;
        } elseif($string_x == "F"){
            $tl += 0.5;
        } elseif($string_x == "G"){
            $tl += 0.7;
        } elseif($string_x == "H"){
             $tl += 0.6;
        } elseif($string_x == "I"){
            $tl += 0.35;
        } elseif($string_x == "J"){
            $tl += 0.42;
        } elseif($string_x == "K"){
            $tl += 0.6;
        } elseif($string_x == "L"){
            $tl += 0.48;
        } elseif($string_x == "M"){
            $tl += 0.76;
        } elseif($string_x == "N"){
            $tl += 0.6;
        } elseif($string_x == "O"){
            $tl += 0.71;
        } elseif($string_x == "P"){
            $tl += 0.55;
        } elseif($string_x == "Q"){
            $tl += 0.8;
        } elseif($string_x == "R"){
            $tl += 0.7;
        } elseif($string_x == "S"){
            $tl += 0.6;
        } elseif($string_x == "T"){
            $tl += 0.6;
        } elseif($string_x == "U"){
            $tl += 0.6;
        } elseif($string_x == "V"){
            $tl += 0.63;
        } elseif($string_x == "W"){
            $tl += 1;
        } elseif($string_x == "X"){
            $tl += 7;
        } elseif($string_x == "Y"){
            $tl += 0.65;
        } elseif($string_x == "Z"){
            $tl += 0.62;
        } elseif($string_x == "Ä"){
            $tl += 0.7;
        } elseif($string_x == "Ö"){
            $tl += 0.71;
        } elseif($string_x == "Ü"){
            $tl += 0.6;
        } elseif($string_x == "ß"){
            $tl += 0.5;
        } elseif($string_x == ":"){
            $tl += 0.1;
        } elseif($string_x == "'"){
            $tl += 0.1;
        } elseif($string_x == "`" ){
            $tl += 0.1;
        } elseif( $string_x == "´"){
            $tl += 0.1;
        } elseif($string_x == ","){
            $tl += 0.1;
        } elseif($string_x == "-"){
            $tl += 0.3;
        } elseif($string_x == "+"){
            $tl += 0.5;
        } elseif($string_x == "."){
            $tl += 0.1;
        } elseif($string_x == ";"){
            $tl += 0.1;
        } elseif($string_x == "*"){
            $tl += 0.37;
        } elseif($string_x == "_"){
            $tl += 0.6;
        } else {
            $tl += 1;
        }

    }
    return $tl;
} 


	//=== Prüfen ob alle Daten vorliegen
	if(!isset($_GET["img"])){
		echo "Fehler: Keine Bilddatei übergeben<br>\n";
		$img="";
	}
	else{
		$img=urldecode($_GET["img"]);
		//Extract Path and Filename
		$imgfile=imagepath($img);
		$studentnr=0;
		filename2student($imgfile["filepath"], $imgfile["filename"], "studentdata");
	}
	
	//=== Daten einlesen
	if(!isset($_GET["ln"])){
		if(!isset($_SESSION["studentdata"][0]["lastname"])){
			echo "Fehler: Keinen Nachnamen übergeben<br>\n";
		}
		else{
			$last_name=$_SESSION["studentdata"][0]["lastname"];
		}
	}
	else{
		$last_name=urldecode($_GET["ln"]);
	}
	
	if(!isset($_GET["gn"])){
		if(!isset($_SESSION["studentdata"][0]["givenname"])){
			echo "Fehler: Keinen Vornamen übergeben<br>\n";
		}
		else{
			$given_name=$_SESSION["studentdata"][0]["givenname"];
		}
	}
	else{
		$given_name=urldecode($_GET["gn"]);
	}	
	
	 if(!isset($_GET["b"])){
		if(!isset($_SESSION["studentdata"][0]["birthday"])){
			echo "Fehler: Kein Geburtsdatum übergeben<br>\n";
		}
		else{
			$birthday=$_SESSION["studentdata"][0]["birthday"];
		}
	}
	else{
		$birthday=urldecode($_GET["b"]);
	}
	if(!isset($_GET["c"])){
		if(!isset($_SESSION["studentdata"][0]["class"])){
			echo "Fehler: Keine Klasse übergeben<br>\n";
		}
		else{
			$class=$_SESSION["studentdata"][0]["class"];
		}
	}
	else{
		$class=urldecode($_GET["c"]);
	}
	
	//Ablaufdatum
	if(!isset($_GET["a"])){
		if(!isset($_SESSION["studentdata"][0]["ablaufdatum"])){
			//echo "Fehler: Kein Ablaufdatum übergeben<br>\n";
			$ablauf=urldecode($_GET["a"]);
		}
		else{
			$ablauf=$_SESSION["studentdata"][0]["ablaufdatum"];
		}
	}
	else{
		$ablauf=urldecode($_GET["a"]);
	}
	
	$jahr=date("Y");



	$ablaufdatum_nicht_automatik="";
	$ablaufdatum="31.07.".($jahr+1);
	if( isset($_POST["ablaufdatum_nicht_automatik"]) ){
		$ablaufdatum_nicht_automatik="checked";
		$ablaufdatum_msg="Das Ablaufdatum wurde nicht automatisch bestimmt!";
	}
	else{
	
		//$classes_ablauf=read_classes_from_csv("../".$_SESSION["settings"]["classes.csv"], true);
		$classes_ablauf=read_classes_from_csv($_SESSION["lisa_path"].DIRECTORY_SEPARATOR.$_SESSION["settings"]["classes.csv"], true);
		
		
		$ablauf=ausweisAblauf($class,$classes_ablauf);		
		$validity_years=class_validity_years($class, $classes_ablauf);
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
	
	
	if( strlen($ablauf) <=3 ) $ablauf=ausweisAblauf();
	$ablaufdatum=$ablauf;

	
	$name=$given_name."<br>".$last_name;

	//echo "lastname:".strwidth($last_name)."<br>";
	//echo "givenname:".strwidth($given_name)."<br>";

	$name_width=max(strwidth($last_name),strwidth($given_name));

	//Bei langen Namen die Schiftart verkleinern
	if(  $name_width > 16  ) {
		$css_font_mod=" very_long_entry ";
	}
	else if(  $name_width >10  ) {
		$css_font_mod=" long_entry ";
	}
	else{
		$css_font_mod="pass_field_value";
	}





		$student=array();
		$student["given_name"] = $given_name;
		$student["last_name"] =$last_name;
		$student["birthday"] = $birthday;
		$student["class"] = $class;
		$student["pic"] = $imgfile["filepath"].$imgfile["filename"];
		//$student["pic"] = $imgfile["filepath"].$imgfile["filename"];

		echo print_pass($student, $layoutbody,$_SESSION["lisa_web_base_path"]);

?>
