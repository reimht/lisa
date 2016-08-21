<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	
	if( isset($_SESSION["picture_source"]) ) $lastpage=$_SESSION["picture_source"];
	else 	$lastpage="picture_source.php";
	$nextpage="picture_crop.php";	


	//Test if Session is valid ($step)
	test_session(2);
	
	//Bilddaten bestimmen, ist für die größe der CSS-Container wichtig
	$image_height=$_SESSION["settings"]["show_picture_max_height"];	
	$image_width=$_SESSION["settings"]["show_picture_max_width"];

	
	$image_height=$_SESSION["image_height_show"];
	$image_width=$_SESSION["image_width_show"];

	//echo "image_height:$image_height-image_width:$image_width<br>"; //Debug
	
	if($image_height>$image_width){
		//hochformat
		$max_xy=$image_height;
		//$img_padding=($image_height-$image_width);
		$img_padding=0;
	}
	else{
		//querformat
		$max_xy=$image_width;
		$img_padding=($image_width-$image_height)/2;
	}	

	//Seiten-Breite bestimmen
	$width=$max_xy+100;
	if($width<300) $width=300;
	//Seiten-Höhe bestimmen
	$height=$max_xy-$img_padding;

	$style="
		#main
		{
			width: ".($width+100)."px;
		}
		#imagediv
	        {
				vertical-align: middle;
				margin: 0em auto 0;
				width: ".$width."px;
				height:  ".$height."px;
				background: gray;
				padding-top: ".$img_padding."px;
        	}";

	$script="
	        jQuery(document).ready(function () {
	
				$('#radio_r0, #radio_r90, #radio_r180, #radio_r270').change(function () {
					if ($('#radio_r0').is(':checked')) {
						$('#image').rotate(0);
					}
					if ($('#radio_r90').is(':checked')) {
						$('#image').rotate(90);
					}
					if ($('#radio_r180').is(':checked')) {
						$('#image').rotate(180);
					}
					if ($('#radio_r270').is(':checked')) {
						$('#image').rotate(270);
					}
				});  
				
		
	        }); ";

	$meta="
			<script src='jquery-1.11.1.min.js' type='text/javascript'></script>
			<script src='jQueryRotate.js' type='text/javascript'></script>";


	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"],  $style,$script,$meta,"","logolisa.svg",false);
?>

			<h3>2. Schritt: Foto drehen!</h3>
			
			<table border='0'>
				<tr>
					<td>
						<?php print_button($lastpage, "button-back-editpicure", "zurück"); ?>
					</td>
					<td>
						<form action='<?php echo $nextpage ?>' method='post'>
							<input type='radio' name='rotage' value='0'   id='radio_r0' checked >0°
							<input type='radio' name='rotage' value='90'  id='radio_r90'>90°
							<input type='radio' name='rotage' value='180' id='radio_r180'>180°
							<input type='radio' name='rotage' value='270' id='radio_r270'>270°
							<input type='submit' name='nextpage-button' value='weiter'>
						</form>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<div align='center' id='imagediv'>
							<img src='getimage.php?norotage=1&nocrop=1&rand=<?php echo rand(0, 100000)?>' alt='Bild' id='image'>
						</div>
					</td>
				</tr>
			</table>
		
			
<?php echo create_footer(""); ?>




