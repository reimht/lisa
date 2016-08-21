<?php
	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	
	//Test if Session is valid ($step)
	test_session(2);
	//print_r($_SESSION);
	$lastpage="picture_rotage.php";
	//$nextpage="picture_crop2.php";
	$nextpage="data_input.php";
	
	$filename=$_SESSION["image_temp_filename"];
	$settings=$_SESSION["settings"];
	
	//Bild drehen
	if(isset($_POST["rotage"])){
		$_SESSION["rotage"]=$_POST["rotage"];
	}	
	
	//Bilddaten bestimmen, ist für die größe der CSS-Container wichtig
	$settings=$_SESSION["settings"];
	$image_height=$settings["show_picture_max_height"];	
	$image_width=$settings["show_picture_max_width"];
	//echo "image_height:$image_height-image_width:$image_width<br>"; //Debug
	
	if($image_height>$image_width){
		//hochformat
		$max_xy=$image_height;
		$img_padding=($image_height-$image_width);
	}
	else{
		//querformat
		$max_xy=$image_width;
		$img_padding=($image_width-$image_height)/2;
	}	
	
	if(isset($_POST["rotage"])){
			if($_POST["rotage"]>=0 AND $_POST["rotage"]<=360){
				$_SESSION["rotage"]=$_POST["rotage"];
			}
	}

	//Sofern vorhanden alte Auswahl wiederherstellen
	if(isset($_SESSION["crop_x"]) AND isset($_SESSION["crop_y"]) AND isset($_SESSION["crop_w"]) AND isset($_SESSION["crop_h"])   ){
		$crop_x=round($_SESSION["crop_x"]);
		$crop_y=round($_SESSION["crop_y"]);
		$crop_w=$crop_x+round($_SESSION["crop_w"]);
		$crop_h=$crop_y+round($_SESSION["crop_h"]);
	}
	else{
		//Vorauswahl des Bereiches
		$crop_x=50;
		$crop_y=0;
		$crop_w=100;
		$crop_h=100;
	}
	
	$style="
	    #main
	        {
			width: ".($max_xy+100)."px;
			//height: ".($max_xy+250)."px;
	        }
		#imagediv
	        {
			vertical-align: middle;
			margin: 2em auto 0;
			width: ".($image_width+10)."px;
			height: ".($image_height+10)."px;
			background: gray;
			padding: 10px;
		}";


	$script="
		 jQuery(function(){
		
			jQuery('#cropbox').Jcrop({
				aspectRatio: ".$settings["picture_aspectRatio"].",
				//setSelect:   [50, 0, 100,100],
				setSelect:   [$crop_x , $crop_y , $crop_w , $crop_h],
				minSize: [ ".$settings["picture_min_x"]." , ".$settings["picture_min_y"]." ],
				onSelect: updateCoords
			});
		
		});
		
		function updateCoords(c)
		{
			jQuery('#x').val(c.x);
			jQuery('#y').val(c.y);
			jQuery('#w').val(c.w);
			jQuery('#h').val(c.h);
		};
		
		function checkCoords()
		{
			if (parseInt(jQuery('#w').val())>0) return true;
			alert('Please select a crop region then press submit.');
			return false;
		};	";

	$meta="
		<script src='jquery-1.11.1.min.js' type='text/javascript'></script>
		<script src='jQueryRotate.js' type='text/javascript'></script>
		<script src='jquery.Jcrop.js' type='text/javascript'></script>
		<script src='jquery.color.js' type='text/javascript'></script>	
		<link rel='stylesheet' href='jquery.Jcrop.min.css' type='text/css' />
		<META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
		<META HTTP-EQUIV='Expires' CONTENT='-1'>  
	";
		
	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], $style,$script,$meta,"","logolisa.svg",false);
?>

			<h3>2. Schritt: Bitte markieren Sie Ihren Kopf!!</h3>

			<table>
				<tr>
					<td align="right">
						<?php print_button($lastpage, "button-back-croppicure", "zurück/drehen"); ?>
					</td>
					<td align="left">
						<!-- This is the form that our event handler fills -->
						<form action="<?php echo $nextpage ?>" method="post" onsubmit="return checkCoords();">
							<input type="hidden" id="x" name="x" />
							<input type="hidden" id="y" name="y" />
							<input type="hidden" id="w" name="w" />
							<input type="hidden" id="h" name="h" />
							<input type="submit" value="weiter"  />
							<p style="margin: 0 0 0 0px; font-size: 90%;">
								
							</p>
						</form>
					</td>
				</tr>
				<tr>
					<td colspan='2' align="center">
						<!-- This is the image we're attaching Jcrop to -->
						<img src="getimage.php?nocrop=1&rand=<?php echo rand(0, 100000)?>" id="cropbox" />
					</td>
				</tr>
			</table>
			
<?php echo create_footer(""); ?>




