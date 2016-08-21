<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("admin");	

	echo create_header($_SESSION["settings"]["html_title"], "","","","","logolisa.svg");		
	


?>
			<h3>LiSA - Admin!</h3>
			
			<p>
				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form><br>

<?php 


	if (extension_loaded('zip')) {
		echo "OK: Extension 'zip' ist geladen<br>\n";
	}
	else{
		echo "Error: Extension 'zip' ist nicht verf&uuml;gbar<br>\n";
	}
	if( !class_exists('ZipArchive')){
		echo "Error: Klasse 'ZipArchive' ist nicht verf&uuml;gbar<br><br>\n";
	}	
	
	if (extension_loaded('gd')) {
		echo "OK: Extension 'gd' ist geladen<br>\n";
	}
	else{
		echo "Error: Extension 'gd' ist nicht verf&uuml;gbar<br>\n";
	}
	if( !function_exists("imagecreatefromjpeg")){
		echo "Error: Funktion 'imagecreatefromjpeg' ist nicht verf&uuml;gbar<br><br>\n";
	}	

	if (extension_loaded('mbstring')) {
		echo "OK: Extension 'mbstring' ist geladen<br>\n";
	}
	else{
		echo "Error: Extension 'mbstring' ist nicht verf&uuml;gbar<br>\n";
	}
	if( !function_exists("mb_strpos")){
		echo "Error: Funktion 'mb_strpos' ist nicht verf&uuml;gbar<br><br>\n";
	}		
	

	
	function create_size_row($path_id){
		$path=$_SESSION["lisa_path"]."/".$_SESSION["settings"][$path_id];
		$size=round(  ( pathSize($path)/1024/1024 ),2);
		return "<tr><td>Size of ($path_id) '$path' </td><td> $size MByte</td></tr>"; 
	}
	
	echo "<table border='1'>";
	echo "<tr><td>PHP Version:</td><td> ".phpversion() ."</td></tr>\n";
	echo create_size_row("temp_image_file_path");
	echo create_size_row("images_school_classes");
	echo create_size_row("images_matching");
	echo create_size_row("target_image_file_path");

	echo "<tr><td>Alle geladenen PHP-Module</td><td>";
	$mod=get_loaded_extensions();
	foreach($mod AS $name){
			echo $name.", ";
	}
	echo "</td></tr>\n";

	echo "<tr><td>Verfügbare Hash Algorithmen</td><td>";
	$hashalgo=hash_algos() ;
	foreach ($hashalgo as $v){
			echo $v.", ";
	}
	echo "</td></tr>\n";

	
	
	
	function parsePHPConfig() {
		ob_start();
		phpinfo(-1);
		$s = ob_get_contents();
		ob_end_clean();
		$a = $mtc = array();
		if (preg_match_all('/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/',$s,$mtc,PREG_SET_ORDER)) {
			foreach($mtc as $v){
				if($v[2] == '<i>no value</i>') continue;
				$a[$v[1]] = $v[2];
			}
		}
		return $a;
	} 
	$phpconf=parsePHPConfig();
	echo "<tr><td colspan='2'> phpinfo() </td></tr>\n";	
	foreach($phpconf AS $n => $v) {
		echo "<tr><td>$n</td><td>$v</td></tr>";
	}
	echo "</table>";
	

?>	

				<form action='index.php' method='POST' >
					<input type='submit' value='zur&uuml;ck'>
				</form>
			</p>	

<?php echo create_footer(""); ?>

