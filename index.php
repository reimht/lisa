<?php
	$path=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].pathinfo($_SERVER["PHP_SELF"])["dirname"]."/eingabe/index.php";
	header("Location: $path");
	exit;
?> 

