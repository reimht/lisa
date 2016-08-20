<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	//=== START LiSA mod (only for usage with LiSA)
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout(false); //false => auto => folder name
	
	$URLBASE="/".$_SESSION["settings"]["target_image_file_path"];
 	$BASE=realpath(  $_SESSION["lisa_path"].$URLBASE );
	

	
	//=== END LiSA mod
	
	
	//============= Settings
 	if( !isset($BASE) ) $BASE=realpath("/var/www/lisa/images/classes/");
	if( !isset($URLBASE) ) $URLBASE="/images/classes/";


	//=== Nichts cachen - Seite immer neu aufrufen
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header('Pragma: no-cache');
	//System auf UTF-8 einstellen
	$character_encoding="UTF-8";
	if (function_exists('iconv') && PHP_VERSION_ID < 50600){
		//Older PHP Version
		iconv_set_encoding("input_encoding", $character_encoding);
		iconv_set_encoding("internal_encoding", $character_encoding);
		iconv_set_encoding("output_encoding", $character_encoding);
	}
	elseif (PHP_VERSION_ID >= 50600){
		ini_set("default_charset", $character_encoding);
	}
	
	//session_start();
	if( !isset($_SESSION["clipboard"]["files"])) $_SESSION["clipboard"]["files"]=array();
	
	
	//echo 'Name des Benutzers: ' . get_current_user();
	
	//============= Functions
	
	//Inline CSS da nach einem Button normalerweise ein Zeilenumbruch folgt vgl: style='float: left;'></form><p style='float: break;'></p>
	function create_rename_file_button($path, $filename_org, $buttonStatus=""){
		return "<form action='' method='POST'><input type='hidden' name='path' value='$path'><input type='hidden' name='file_name_org' value='$filename_org'><input type='submit' name='setnewname' value='rename' $buttonStatus style='float: left;'></form><p style='float: break;'></p>";   
	}
	
	function create_cut_file_button($path, $filename_org, $buttonStatus=""){
		return "<form action='' method='POST'><input type='hidden' name='path' value='$path'><input type='hidden' name='file_name_org' value='$filename_org'><input type='submit' name='cut' value='verschieben' $buttonStatus style='float: left;'></form><p style='float: break;'></p>";   
	}	
	
	function create_goto_folder_button($path, $label="show folder"){
		return "<form action='' method='POST'><input type='hidden' name='path' value='$path'><input type='submit' name='showfolder' value='$label' style='float: left;'></form><p style='float: break;'></p>";   
	}
	function create_delete_file_button($path, $filename, $buttonStatus=""){
		return "<form action='' method='POST'><input type='hidden' name='path' value='$path'><input type='hidden' name='file_name' value='$filename'><input type='submit' name='deletefile' value='delete' $buttonStatus style='float: left;'></form><p style='float: break;'></p>";   
	}
	function create_delete_folder_button($path,$foldername){
		return "<form action='' method='POST'><input type='hidden' name='path' value='$path'><input type='hidden' name='folder_name' value='$foldername'><input type='submit' name='deletefolder' value='delete' style='float: left;'></form><p style='float: break;'></p>";   
	}
	
	//Check if path is insite allowed BASE-path (else return BASE-Path)
	function is_in_base_path($path, $BASE, $msg=false){
		if(!file_exists($BASE.$path)){
			if($msg) echo "file or directory not exists<br>";
			return $BASE;
		}
		$realpath=realpath($BASE.$path);
		$checkBase = strpos($realpath, $BASE);
		if ($checkBase === false) {
			if($msg) echo "Setze Path auf BASE-Path (SecurityMode)<br>";
			return $BASE;
		}
		else if ($checkBase != 0) {
			if($msg) echo "Setze Path auf BASE-Path (SecurityMode)<br>";
			return $BASE;
		}
		return $realpath;
	}
	
	function read_filesystem($path, $BASE, &$result){
		if($result==null) $result=array();
		$handle = opendir($path); 
		//echo "$path<br>\n";
		//Überprüfe ob der Pfad verfügbar ist
		if (false === $handle) {
			echo "no path $path<br>";
			return null;
		}
		//Durchlaufe alle Einträge in dem Path
		while (false !== ($entry = readdir($handle))) {
			$fullpath=realpath($path ."/". $entry);
			if (  $entry != '.' AND $entry != '..' ) {
				if(is_dir($fullpath)){
					$result[]=str_replace($BASE,"",$fullpath)."/";
					read_filesystem($fullpath, $BASE, $result);
				}
				else{
					$result[]=str_replace($BASE,"",$fullpath);
				}
			}
		}
		closedir($handle);
		return $result;
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>show me files</title>
	<style>
		input { display: inline;}
table {
  border: 1px solid #4F4F4F;
  border-collapse: collapse;
}
	</style>
</head>
<body>
<h2>Simple Filebrowser</h2>
<?php
//print_r($_POST);
//print_r($_SESSION);
	if( isset($_POST['path'])){
	     $path= $_POST['path'];
	}	
	else if( isset($_GET['path'])){
	     $path= $_GET['path'];
	}
	else{
	    $path = '';
	}

	//Check if path is insite allowes BASE-path
	$realpath=is_in_base_path($path, $BASE, true);
	

	
	$path=str_replace($BASE,"",$realpath);

	//echo "$BASE<br>$realpath<br>$path";
	


	//=== START clipboard logic
	//Clear clipboard
	if(isset($_POST["clearclipboard"]) ){
		$_SESSION["clipboard"]["files"]=array();
	}
	//Put new file into clipboard
	else if(isset($_POST["cut"], $_POST["path"], $_POST["file_name_org"]) ){
		if($filename_old=realpath($BASE."/".$_POST["path"]."/".$_POST["file_name_org"])){
			if( !in_array($filename_old, $_SESSION["clipboard"]["files"]) ){
				$file=array();
				$file["path"]=$_POST["path"];
				$file["name"]=$_POST["file_name_org"];
				$_SESSION["clipboard"]["files"][trim($filename_old)]=$file;
			}
		}
		else{
			echo "Warnung: Quell-Datei '".$_POST["path"]."/".$_POST["file_name_org"]."' existiert nicht!<br>\n";			
		}
	}

	//=== START delete folderlogic
	if(isset($_POST["path"], $_POST["folder_name"], $_POST["deletefolder"])){
		$delete_path=$_POST["path"]."/".$_POST["folder_name"];
		$delete_realpath=is_in_base_path($delete_path, $BASE, false);
		
		if( $delete_realpath != $BASE AND is_dir($delete_realpath) ) {
		
			if( @rmdir($delete_realpath) ){
				echo "Delete folder '$delete_path'<br>";
			}
			else{
				echo "Can't delete folder '".$_POST["folder_name"]."'! Maybe it isn't empty!<br>";
			}

		}
		else{
			echo "Can't find file '".$_POST["folder_name"]."'!<br>";
		}

	}
	
	//=== START delete filelogic
	if(isset($_POST["path"], $_POST["file_name"], $_POST["deletefile"])){
		$delete_path=$_POST["path"]."/".$_POST["file_name"];
		$delete_realpath=is_in_base_path($delete_path, $BASE, false);
		
		if( $delete_realpath != $BASE AND is_file($delete_realpath) ) {
			if( unlink($delete_realpath) ){
				echo "Delete file '$delete_path'<br>";
			}
			else{
				echo "Can't delete file '".$_POST["file_name"]."'!<br>";
			}
		}
		else{
			echo "Can't find file '".$_POST["file_name"]."'!<br>";
		}
	}
	

	//=== START move logic
	if(isset($_POST["moveClipboardFilesToPath"], $_POST["clipboardmovefiles"])){
		
		if($newpath=realpath($BASE."/".$_POST["moveClipboardFilesToPath"])){
			$nr=sizeof($_SESSION["clipboard"]["files"]);
			if( $nr>0 ){
				foreach( $_SESSION["clipboard"]["files"] AS $filename_old => $file){
					//echo "move file '".$file["path"]."/".$file["name"]. "' to '".$_POST["moveClipboardFilesToPath"]."/".$file["name"]."'<br>\n";
					//DEBUGecho "move file '$filename_old' to '".$newpath."/".$file["name"]."'<br>\n";
					if( !rename ( $filename_old, $newpath."/".$file["name"] )  ){
						echo "Warnung: Konnte die Datei '".str_replace($BASE,"",$filename_old)."' nicht zu '".str_replace($BASE,"",$newpath."/".$file["name"])."' umbenennen!<br>\n";
					}
					else{
						//After move on filesystem remove from clipboard
						unset($_SESSION["clipboard"]["files"]["$filename_old"]);
					}
				}
			}			
		}
		else{
			echo "Warnung: Quell-Datei '".$_POST["path"]."/".$_POST["moveClipboardFilesToPath"]."' existiert nicht!<br>\n";	
		}
	}
	//=== START rename logic
	else if(isset($_POST["setnewname"])){
		$filename_org=$_POST["file_name_org"];
		echo "<form action='' method='POST'>\n";
		echo $filename_org. " -".
			"<input type='text' name='file_name_new' value='".$filename_org."'>\n";
		echo "<input type='hidden' name='path' value='$path'>\n";
		echo "<input type='hidden' name='file_name_org' value='$filename_org'>\n";
		echo "<input type='submit' name='rename' value='rename'> \n";
		echo "</form>\n";
	}
	if( isset($_POST["rename"], $_POST["path"], $_POST["file_name_org"], $_POST["file_name_new"]) ){
		//Stringlänge>0 und Neu != Alt
		if(strlen($_POST["file_name_org"])>0 AND strlen($_POST["file_name_new"])>0 AND $_POST["file_name_org"]!=$_POST["file_name_new"]){
			if($filename_old=realpath($BASE."/".$_POST["path"]."/".$_POST["file_name_org"])){
				$filename_new=realpath($BASE."/".$_POST["path"])."/".$_POST["file_name_new"];		
				echo "rename file '".str_replace($BASE,"",$filename_old)."' to '".str_replace($BASE,"",$filename_new)."'<br>\n";
				if( !rename ( $filename_old, $filename_new )  ){
					echo "Warnung: Konnte die Datei '".str_replace($BASE,"",$filename_old)."' nicht zu '".str_replace($BASE,"",$filename_new)."' umbenennen!<br>\n";
				}
			}
			else{
					echo "Warnung: Quell-Datei '".$_POST["path"]."/".$_POST["file_name_org"]."' existiert nicht!<br>\n";			
			}
		}
	}
	
	//=== START Show clipboarb
	if(isset($_SESSION["clipboard"]["files"])){
		$nr=sizeof($_SESSION["clipboard"]["files"]);
			if($nr>0){
			echo "There are '$nr' files in the clipboard<br>\n";
			if( $nr>0 ){
				foreach( $_SESSION["clipboard"]["files"] AS $realpathfile => $file){
					echo /*$realpathfile."---".*/$file["path"]."/".$file["name"]. "<br>\n";
				}
				echo "<div >
						<form action='' method='POST'>
							<input type='hidden' name='moveClipboardFilesToPath' value='$path'>
							<input type='submit' name='clipboardmovefiles' value='move to \"$path\"' style='float: left;'>
						</form>
						<p style='float: break;'></p>
					</div>"; 
				echo "<div ><form action='' method='POST'><input type='submit' name='clearclipboard' value='clear clipboard' ></form></div>"; 
			}
		}
		echo "<br>\n";
	}	
	
	//=== START Show path logic

	
 
 	if(isset( $_POST["find_file_name"],$_POST["find"] ) ){
		echo "<br>";
		if(!isset($result)) $result="";
		$results=read_filesystem($BASE,$BASE,$result);	
		$searchstring=strtolower($_POST["find_file_name"]);
		echo "<b>show search results for '$searchstring'</b><br><br>\n";
		$found=array();
		foreach($results AS $result){
			if(mb_strpos(strtolower($result), $searchstring) )   $found[]=$result;
		}
		$nrFound=sizeof($found);
		echo "Found $nrFound results in ".sizeof($results)." entries<br><br>";
		if($nrFound>0){
			echo "<table border='1'>";
			foreach($found AS $f){
				echo "<tr><td>$f</td><td>".create_goto_folder_button(dirname($f),"show folder")."</td></tr>";
			}
			echo "</table>";
		}
	}
	else if(is_dir($realpath)) {

		echo "<b>browse folder '$path'</b><br><br>\n";
		echo "<table border='1'>";
		$dir = opendir($realpath);
		$ds=array();
		while( $line = readdir($dir) ){
			if($line != "." and $line !="..") $ds[]=$line;
		}
		
		natsort($ds);
		if($BASE != $realpath) echo "<tr><td><a href='?path=$path/..'>..</a></td><td>last change</td><td>filesize</td><td>owner</td><td>permissions</td><td>rename</td><td>cut</td><td>delete</td></tr>";
		foreach($ds AS $d){
			$realpathd=trim(realpath($realpath."/".$d));
			if(fileowner($realpathd) !== FALSE){
				$owner=posix_getpwuid(fileowner($realpathd))["name"].":".posix_getpwuid(filegroup($realpathd))["name"];
			}
			else{
				$owner="unknown";
			}
			$permissions=substr(sprintf("%o", fileperms("$realpathd")), -4);
			
			if(is_dir($realpathd)){
				echo "<tr><td><a href='?path=$path/$d'>$d</a></td><td>".date ("Y.m.d H:i:s", filemtime($realpathd))."</td><td>&nbsp;</td><td>$owner</td><td>$permissions</td><td>".create_rename_file_button($path, $d)."</td><td>&nbsp;</td><td>".create_delete_folder_button($path,$d)."</td></tr>\n";
			}
			else{
				//Prüfe ob die Datei in der Zwischenablage ist
				if(isset( $realpathd, $_SESSION["clipboard"]["files"]["$realpathd"])) $status="disabled";
				else $status="";
				echo "<tr><td><a href='?path=$path/$d'>$d </a></td><td>".date ("Y.m.d H:i:s", filemtime($realpathd))."</td><td>".ceil(filesize($realpathd)/1024)."kB</td><td>$owner</td><td>$permissions</td><td>".create_rename_file_button($path, $d, $status)."</td><td>".create_cut_file_button($path, $d, $status)."</td><td>".create_delete_file_button($path, $d, $status)."</td></tr>\n";
				
			}
		}
		
	}
	else if(is_file($realpath)) {
			$pathi = pathinfo($path);
			$pathi_extension=strtolower($pathi["extension"]);	
			if($pathi_extension== "jpg" OR $pathi_extension == "jpeg" OR $pathi_extension == "png"){
				$infofield="<img src='..".$URLBASE.$path."' alt='image ".$URLBASE.$path."' height='100px'> ";
			}
			else{
				$infofield="%nbsp;";
			}
			
			echo "<table border='1'>
					<tr><td>dirname</td><td>".$pathi["dirname"]."</td><td rowspan='4'>$infofield</td></tr>
					<tr><td>basename</td><td>".$pathi["basename"]."</td></tr>
					<tr><td>extension</td><td>".$pathi["extension"]."</td></tr>
					<tr><td><a href='?path=".$pathi["dirname"]."'>Back</a> </td><td> ".create_delete_file_button($pathi["dirname"], $pathi["basename"])." </td></tr>
				</table>";
			//$fp = fopen($file,'r');
			//echo nl2br(htmlentities(fread($fp, filesize($file))));
			//fclose($fp);
 
	}
	else{
		echo "'$realpath' ist kein Verzeichnis";
	}
	
	echo "</table>";
	
	
	//=== START find file logic
	if(isset($_POST["find_file_name"])) $searchString=$_POST["find_file_name"];
	else $searchString="";
	echo "<br>";
	echo "<form action='' method='POST'>
		Find file: <input type='text' name='find_file_name' value='$searchString'>
		<input type='submit' name='find' value='find'>
		</form>\n";
	


 

?>
</body>
</html> 
