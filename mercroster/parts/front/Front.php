<?php
if(!defined('Gyu53Hkl3'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("includes/BBFunctions.php");
$bbf=new BBFunctions;

$pageID = $_GET['page'];
$pageID = stripslashes($pageID);
$pageID = mysql_real_escape_string($pageID);

$headerResult=$dbf->queryselect("SELECT name, motto, description, image, services, contact, main FROM command WHERE id='1';");
$headerArray=mysql_fetch_array($headerResult, MYSQL_ASSOC);



if($_GET["action"]=="pages" || !isset($_GET["action"]))
{
	$pageResult=$dbf->queryselect("SELECT text FROM pages WHERE id='{$pageID}';");
	$pageArray=mysql_fetch_array($pageResult, MYSQL_ASSOC);
	$text=nl2br($pageArray[text]);
  	$text=$bbf->addTags($text);
  	echo "<div id='content'>\n";;
  	echo "{$text}\n";
  	echo "</div>\n";
}

?>