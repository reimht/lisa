<?php

	/* Copyright (c) H. Reimers reimers@heye-tammo.de*/
	require_once('../preload.php'); 	//Create Session an load Config
	check_login_logout("eingabe");	
	
	$lastpage="picture_source.php";
	$_SESSION["picture_source"]="picture_from_webcam.php";

	//Test if Session is valid ($step)
	test_session(1);

	$script="
		//****************************************************************
		// Example function save save canvas content into image file.
		// www.permadi.com
		//****************************************************************
		function saveViaAJAX()
		{
			var debug=0;
			var testCanvas = document.getElementById('screenshot-canvas');
			var canvasData = testCanvas.toDataURL('image/png');
			var postData = 'canvasData='+canvasData;
			//var debugConsole= document.getElementById('debugConsole');
			//debugConsole.value=canvasData;
			var ajax = new XMLHttpRequest();
			ajax.open('POST','picture_from_webcam_upload.php',true);
			ajax.setRequestHeader('Content-Type', 'canvas/upload');

			//Läuft im Hintergrund, wird ausgerufen wenn Upload fertig
			ajax.onreadystatechange=function()
			{
				if (ajax.readyState == 4)
				{
					location.href = 'picture_rotage.php';
					// Write out the filename.
				}
			}


			//Starte Übertragung
			ajax.send(postData);
		}	
	
	
        function onFailure(err) {
            alert('The following error occured: ' + err.name);
        }
        jQuery(document).ready(function () {

			//Beim ersten Anzeigen Elemente ohne Funktion ausblenden
			$('#screenshot-canvas').hide(); //hide picture
			$('#livecam-button').hide(); //hide new picture button	
			$('#upload-nextpage-button').hide(); //hide next page button	
			$('#uploadmsg').hide(); //hide upload message

			//$('#webcam').hide();
			//$('#screenshot-button').hide();
			  
			
		
			var video = document.querySelector('#webcam');
			
            		var bscreenshot = document.querySelector('#screenshot-button');
			var blivecam = document.querySelector('#livecam-button');

			var bupload_nextpage = document.querySelector('#upload-nextpage-button');

			//var bsaveimage = document.querySelector('#saveimage-button');
			var canvas = document.querySelector('#screenshot-canvas');
			var ctx = canvas.getContext('2d');

            		navigator.getUserMedia = (navigator.getUserMedia ||
				navigator.webkitGetUserMedia ||
				navigator.mozGetUserMedia ||
				navigator.msGetUserMedia);

			if (navigator.getUserMedia) {
               			navigator.getUserMedia
                            	(
                             	 { video: true },
                              	function (localMediaStream) {
					video.src = window.URL.createObjectURL(localMediaStream);
				}, onFailure);
			}
			else {
				onFailure();
			}
			
			
			$(document).keydown(function(e) {
				if (e.keyCode === 32) {
					snapshot();
				}
			});
			
            		bscreenshot.addEventListener('click',snapshot, false);
            
			//Erstellt einen Snapshot und zeigt diesem im Browser an (keine Übertragung)
		            function snapshot() {
		                canvas.width = video.videoWidth;
		                canvas.height = video.videoHeight;
		                ctx.drawImage(video, 0, 0);
						$('#webcam').hide(); //hide Livecam
						$('#screenshot-button').hide(); //hide Capture Button
						$('#screenshot-canvas').show() //show picture
						$('#livecam-button').show(); //show new picture button
						$('#nextpage-button').show(); 
						$('#upload-nextpage-button').show(); //show save picture button
						//saveViaAJAX();
           		 }
			
	    		bupload_nextpage.addEventListener('click',uploadimage, false);
			function uploadimage() {
				saveViaAJAX();

				$('#screenshot-canvas').hide(); 	//hide picture
				$('#webcam').hide(); 				//hide Livecam
				$('#screenshot-button').hide(); 	//hide Capture Button
				$('#livecam-button').hide();		 //hide new picture button

				//$('#pagetitle').hide(); //hide uploas message
				$('#pagetitle').text('Bitte warten ...');
				$('#msg').text('Lade das Bild auf den Server, dies kann je nach Internetverbindung andauern.');


				$('#upload-nextpage-button').hide(); //hide uploas message
				$('#uploadmsg').show(); //show upload message


           		 }			
			
			
			blivecam.addEventListener('click',livecam, false);
			function livecam() {
				$('#webcam').show(); //show Livecam
				$('#screenshot-button').show(); //show Capture Button
				$('#screenshot-canvas').hide(); //hide picture
				$('#livecam-button').hide(); //hide new picture button
				$('#nextpage-button').hide(); 
				$('#upload-nextpage-button').hide(); //hide save picture button

            		}
        });

	";

	$meta="<script src='jquery-1.11.1.min.js' type='text/javascript'></script>";

	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], "",$script,$meta,"","logolisa.svg",false);
?>

			<h3 id='pagetitle' >1.1. Schritt: Foto aufnehmen!</h3>
			<video id='webcam' autoplay >
			</video>
			<canvas id='screenshot-canvas'>
			</canvas>
			<p>
				<table>
					<tr id='msg'   >
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<button id='screenshot-button'>
								Bild erstellen
							</button>
							<button id='livecam-button'>
								Neues Bild
							</button>
							<button id='upload-nextpage-button'>
								weiter
							</button>
						</td>

						<td>
							<?php print_button($lastpage, "picture_from_webcam", "zurück") ?>
						</td>
					</tr>
				</table>
			</p>		
<?php echo create_footer(""); ?>



