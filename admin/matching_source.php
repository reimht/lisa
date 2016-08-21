<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin"); //area = false => auto = folder name
	
	//System auf UTF-8 einstellen
	header("Content-Type: text/html; charset=UTF-8");

	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");		

//preecho($_POST);
	
	//====== Kodierung abfragen ZIP-Datei
	if(isset($_POST["zipencsource"])){
		$_SESSION["zipencsource"]=$_POST["zipencsource"];
		$zipencsource=$_POST["zipencsource"];
	}
	else if(isset($_SESSION["zipencsource"])){
		$zipencsource=$_SESSION["zipencsource"];
	}
	else{
		$zipencsource="CP437"; //or auto
	}

	//====== Kodierung abfragen Dateisystem
	if(isset($_POST["zipenctarget"])){
		$_SESSION["zipenctarget"]=$_POST["zipenctarget"];
		$zipenctarget=$_POST["zipenctarget"];
	}
	else if(isset($_SESSION["zipenctarget"])){
		$zipenctarget=$_SESSION["zipenctarget"];
	}
	else{
		$zipenctarget="UTF-8"; //utf-8 or auto
	}



	//====== Erstelle Verzeichnis
	if( isset($_POST["createdir"]) && isset($_POST["dirname"]) && isset($_POST["path"]) ){
		$newdir=enc_html($_POST["path"]."/".$_POST["dirname"]) ;

		if(@mkdir($newdir, 0700)){
			echo "Erstelle Ordner $newdir.<br>";
		}
		else{
			echo "Fehler: Konnte Verzeichnis nicht $newdir erstellen.<br>";
		}
	}

	//====== Lösche Verzeichnis 

	function delTree($dir) {
		$dir = preg_replace('~([/])\1+~', '$1', $dir) ;
		//echo "Durchsuche ".encode_to_html($dir)."<br>";
		$directory = opendir($dir);
		while($file = readdir($directory)) {
			if($file != "." && $file != ".."  && $file != "small" ) {
				if(is_dir($dir."/".$file)) {
					delTree($dir."/".$file);
				} 
				else {
					//echo "L&ouml;sche Datei ".encode_to_html($dir."/".$file)." <br>";
					if( file_exists($dir."/".$file) ) unlink( $dir."/".$file );						
					else echo "Fehler: Kann Date ".encode_to_html($dir."/".$file)." nicht l&ouml;schen <br>";		
		      		}
		 	 }
		  }
		closedir($directory);
		//echo "L&ouml;sche Verzeichnis ".encode_to_html($dir."/".$file)." <br>";
		rmdir( $dir );
	}

	if( isset($_POST["path"]) && isset($_POST["dirname"]) && isset($_POST["delete"]) ){
		$deletedir=$_POST["path"]."/".$_POST["dirname"] ;

		echo "L&ouml;sche Ordner $deletedir.<br>";
		if(is_dir($deletedir)) {
			delTree($deletedir);
		}
		else if( file_exists($deletedir) ){
			unlink( $deletedir );	
		}
	}


	//====== Lege Quelle (BBSPlan oder LiSA) fest
	if( isset( $_POST["source"] ) ){
		if($_POST["source"]=="bbsplan"){
			echo "<h1>Quelle der BBSPlan Fotos setzen</h1>";
		}
		else if($_POST["source"]=="lisa"){
			echo "<h1>Quelle der LiSA Fotos setzen</h1>";	
		}
		else{
			echo "<a href='matching_index.php'> Zur&uuml;ck </a>";
			$body="Fehler: Source nicht gesetzt<br>";
			echo create_footer("$body"); 
			exit(0);
		}

		$source=$_POST["source"];
	}
	else{
		echo "<a href='matching_index.php'> Zur&uuml;ck </a>";
		$body="Fehler: Source nicht gesetzt<br>";
		echo create_footer("$body"); 
		exit(0);
	}

	$standart_dir=false;
	if( isset($_POST["standart"])) {
		if( $_POST["standart"]=="on" ) {
			$standart_dir=true;
			echo "Setze Standartverzeichnis<br>";
		}
	}



	if( !isset($_SESSION[$source."_source"]) ){
		$_SESSION[$source."_source"] = $_SESSION["lisa_path"]."/".$_SESSION["settings"]["images_matching"];
	}

	//Verzeichnis auswählen
	if($standart_dir){
		$_SESSION[$source."_source"]=$_SESSION["lisa_path"]."/".$_SESSION["settings"]["images_matching_lisa"];
	}
	//Aktuelles Verzeichnis auswählen
	else if( isset( $_POST["path"] ) && isset( $_POST["chdir"] ) ){
		//$_SESSION[$source."_source"]=realpath($_POST["path"]);
		$_SESSION[$source."_source"]=preg_replace('~([/])\1+~', '$1', $_POST["path"]) ;
	}

	echo "Aktuelle Quelle: ".enc_html($_SESSION[$source."_source"])."<br>";

	echo "<a href='matching_index.php'>Zur&uuml;ck</a><br>";


	//Gibt das nächst höhere Verzeichnis zurück
	function prepath($path){
		
		$p=explode("/",$path);
		$nr=sizeof($p)-1;
		if($p[$nr]=="") $nr--;
		$pn="";
			for($i=0;$i<($nr);$i++){
				if( trim($p[$i])!="" ){
					$pn=$pn.$p[$i]."/";
				}
			}
		return $pn;
	}


	//Verzeichnis prüfen
	if( !file_exists($_SESSION[$source."_source"]) ){
		echo "<b>Fehler:</b> Verzeichnis existiert nicht!<br>";
		$path="matching_images";
	}
	else if( !is_dir($_SESSION[$source."_source"]) ){
		echo "<b>Fehler:</b> Verzeichnis existiert nicht!<br>";
		$path="matching_images";
	}
	else{
		$path=$_SESSION[$source."_source"];
	}

	//Wenn Standartverzeichnis 
	if($standart_dir){
		$body = ( isset($body) ? $body : "");
		echo create_footer("$body"); 
		exit(0);
	}


	//====== Zip-Datei upload
	if( isset( $_FILES["file"]["tmp_name"] ) ){
		$dateityp = "zip"; //Einbauen!!!!
		if($dateityp == "zip"){
			if($_FILES["file"]['size'] <  102400000){
				$zip_path=$path."/".$_FILES["file"]['name'];
				move_uploaded_file($_FILES["file"]['tmp_name'], $zip_path);

				//====== Entpacken
				$zip = new ZipArchive;
				$res = $zip->open($zip_path);
				if ($res === TRUE) {
					//Dateinamen Kodierung aller Dateien ermitteln
					$f_enc=array();
					$filenr=0;
					while(false !== $zip->statIndex($filenr )) {
	   					$stat = $zip->statIndex($filenr );
						$filename=$stat['name'];
						$filename_enc=get_encoding($filename,$zipencsource);

						if($filename_enc!='ASCII' AND $filename_enc!=$zipenctarget){
							$f=array();
							$f["filename_org"]=$filename;
							$f["encoding"]=$filename_enc;
							$f["filename_new"]=convert_string($filename,$filename_enc,$zipenctarget);
							$f_enc[]=$f;
						}
						$filenr++;
					}

					$zip->extractTo($path);
					$zip->close();
					echo "Das Zip-Archiv wurde nach $path entpackt. Es wurden $filenr Dateien entpackt!<br>\n";

					//Encoding anpassen
					foreach($f_enc AS $f){
						//echo "rename: ".$path.$f["filename_org"]." to:". $path.$f["filename_new"]." encoding: ".$f["encoding"]."<br>\n";
						rename($path.$f["filename_org"], $path.$f["filename_new"]); //Datei umbennen
					}
				}
				else {
     					 echo "Fehler: Konnte Zip-Archiv nicht entpacken!<br>";
   				}


/*					$archiv = new ZipArchive();
					$archiv->open($zip_path);
					$filenr = 0;
					while(false !== $archiv->statIndex($filenr )) {
	   					$stat = $archiv->statIndex($filenr );

						$filename=$stat['name'];
						$filename_enc=get_encoding($filename);
						$filename_enc_html=enc_html($filename);
						$filename_convert=convert_string($filename);
//$stat['name']=convert_string($filename);
$zip->renameName($stat["name"],$filename_convert);
print_r($stat);
						echo "$filename_enc-$filename-$filename_enc_html-c:$filename_convert<br>\n";
	    					//echo "encoding:".get_encoding($stat['name'])." filename: ".enc_html(convert_string($stat['name'],get_encoding($stat['name']),"UTF-8")) . "<br>\n";
	   					$filenr ++;
					}
					echo 'Anzahl Dateien: ' . $filenr ;
					$zip->extractTo($path);
					$zip->close();
					echo "Das Zip-Archiv wurde nach $path entpackt";
				}
				else {
     					 echo "Fehler: Konnte Zip-Archiv nicht entpacken!<br>";
   				}

/*


/*ALTE Version Umlautprobleme
				$zip = new ZipArchive;
				$res = $zip->open($zip_path);
				if ($res === TRUE) {
					$zip->extractTo($path);
					$zip->close();
					echo "Das Zip-Archiv wurde nach $path entpackt";
				}
				else {
     					 echo "Fehler: Konnte Zip-Archiv nicht entpacken!<br>";
   				}
*/

				//end
			}
			else{
				echo "Fehler: Das Zip-Archiv ist größer als 100 Mb!<br> ";
			}
		}
		else{
			echo "Fehler: Kein Zip-Archiv hochgeladen!<br>";
		}
	}

	//====== Menü/Verzeichnisse ausgeben
	echo "<br><br>";

	function buttonpath($source, $path){
		return	"<form action='matching_source.php' method='POST' >
					<input type='hidden' name='source' value='$source'>
					<input type='hidden' name='path' value='$path'>
					<input type='submit' name='chdir' value='ausw&auml;hlen'>
				</form>";
	}

	function buttondelete($source, $path, $dirname){
		return	"<form action='matching_source.php' method='POST' >
					<input type='hidden' name='source' value='$source'>
					<input type='hidden' name='path' value='$path'>
					<input type='hidden' name='dirname' value='$dirname'>
					<input type='submit' name='delete' value='l&ouml;schen'>
				</form>";
	}

	echo "<table border='1'>";
	$directory = opendir($path);
	echo "<tr><td>Hauptordner</td><td colspan='2'>". buttonpath($source,$_SESSION["lisa_path"]."/".$_SESSION["settings"]["images_matching"])."</td>";
	echo "<tr><td>Eine Ebende zurück</td><td colspan='2'>". buttonpath($source,prepath($path))."</td>";
	echo "<tr><td>
					<form action='matching_source.php' method='POST'>
						<input type='text' name='dirname'>
						<input type='hidden' name='source' value='$source'>
						<input type='hidden' name='path' value='$path'>
				</td>
				<td colspan='2'>
						<input type='submit' name='createdir' value='Ordner erstellen'>
					</form>
				</td>";
	while($file = readdir($directory)) {
		$extention = pathinfo($file, PATHINFO_EXTENSION); 
		if($file != "." && $file != ".."  /*&& $file != "small"*/ && is_dir($path."/".$file)) {
			echo "<tr><td>".enc_html($path."/".$file)."</td><td>".buttonpath($source, $path."/".$file)."</td><td>".buttondelete($source, $path, $file)."</td>";
		}
		else if($extention=="zip"){
			echo "<tr><td>".enc_html($path."/".$file)."</td><td> &nbsp; </td><td>".buttondelete($source, $path, $file)."</td>";
		}
	}
	closedir($directory);
	echo "</table>";




	echo "<br><h2>Neue ZIP-Datei mit Bildern hochladen</h2><br>
		<form action='matching_source.php' method='post' enctype='multipart/form-data'>
			<input type='hidden' name='source' value='$source'>
			<input type='hidden' name='path' value='$path'>
			<input type='hidden' name='path' value='$path'>
			<table border='0'>
				<tr>
					<td>
						<label for='file'>Filename:</label>
						<input type='file' name='file' id='file'>
						<input type='submit' name='submit' value='Submit'>
					</td>
				</tr>
				<tr>
					<td>
						Dateinamen-Kodierung ZIP-Datei:
						<input type='text' name='zipencsource' value='$zipencsource'>
					</td>
				<tr>
					<td>
						Dateinamen-Kodierung Server-Datei-System:
						<input type='text' name='zipenctarget' value='$zipenctarget'>
					</td>
				</tr>
				</tr>
				<tr>
					<td>
						Mögliche Kodierungen u.a.: auto oder CP850,CP437,UTF-8,ISO-8859-1
					</td>
				</tr>
			</table>
		</form>";	



	

	echo "<a href='matching_index.php'> Zur&uuml;ck </a>";

	$body="<br>IP:".$_SERVER["REMOTE_ADDR"] ."<br>";
	echo create_footer("$body"); 

?>