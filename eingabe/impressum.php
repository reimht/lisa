<?php
	require_once('../preload.php'); 	//Create Session an load Config
	//require_once('functions.php'); 
	//Start Page ($tilte, $style,$script,$meta,$body)
	echo create_header($_SESSION["settings"]["html_title"], '','','','',"logolisa.svg",false);
	
?>

<script language="javascript" type="text/javascript">
function closeWindow() {
window.open('','_parent','');
window.close();
}
</script> 

<a href="javascript:closeWindow();">Fenster schließen</a>

<?php 
    echo "


<p><strong>Schulordnung</strong></p>
<p>Für die Verwendung dieser Seite gilt die Schulordnung und die ggf. vorhandene Computernutzungsvereinbarung</p>

<p><strong>Haftungsausschluss</strong></p>
<p>Nach &sect; 7 Abs.1 TMG sind wir f&uuml;r eigene Inhalte innerhalb dieser Seite verantwortlich. Wir sind jedoch nicht verpflichtet externe Informationen zu überwachen, ob diese auf rechtswidrige Tätigkeiten schließen lassen. Wenn uns Rechtsverletzungen bekannt gemacht werden, werden diese Inhalte zeitnah entfernt.  </p>

<p>Wir haben auf <b>verlinkte Inhalte</b> keinen Einfluss. Wenn uns Rechtsverletzungen bekannt gemacht werden, werden diese <b>Links</b> zeitnah entfernt.  </p>

<p><strong>Datenschutzerkl&auml;rung:</strong></p>
<p>Die Nutzung dieser Webseite ist in der Regel nicht ohne Angabe personenbezogener Daten m&ouml;glich. Die erhobenen personenbezogenen Daten (wie Name, Geburtsdatum und Klasse) werden nur für den schulinternen Gebrauch gespeichert. Diese Daten werden ohne Ihre ausdr&uuml;ckliche Zustimmung nicht an Dritte weitergegeben.</p>

"; ?>


<a href="javascript:closeWindow();">Fenster schließen</a>


<?php echo create_footer(""); ?>
