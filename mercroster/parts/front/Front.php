<?php
if(!defined('Gyu53Hkl3'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("includes/BBFunctions.php");
$bbf=new BBFunctions;

$headerResult=$dbf->queryselect("SELECT name, motto, description, image, services, contact, main FROM command WHERE id='1';");
$headerArray=mysql_fetch_array($headerResult, MYSQL_ASSOC);

if($_GET["action"]=="main" || !isset($_GET["action"]))
{
  $text=nl2br($headerArray[main]);
  $text=$bbf->addTags($text);
  echo "<div id='content'>\n";;
  echo "<h1 class='main'>{$headerArray[name]}</h1>\n";
  echo "{$text}\n";
  echo "</div>\n";
}

if($_GET["action"]=="information")
{
  $text=nl2br($headerArray[description]);
  $text=$bbf->addTags($text);
  echo "<div id='content'>\n";
  echo "<div style='float: left; background-color:transparent;'>\n";
  echo "<img class='imagecenter' src='./images/commandimages/{$headerArray[image]}' alt='Commands image'/>\n";
  echo "</div>\n";
  echo "<h1 class='information'>{$headerArray[name]}:</h1>\n";
  echo "<h2 class='information'>{$headerArray[motto]}</h2>\n";
  echo "{$text}\n";
  echo "</div>\n";
}

if($_GET["action"]=="services")
{
  $text=nl2br($headerArray[services]);
  $text=$bbf->addTags($text);
  echo "<div id='content'>\n";
  echo "{$text}\n";
  echo "</div>\n";
}

if($_GET["action"]=="contact")
{
  $text=nl2br($headerArray[contact]);
  $text=$bbf->addTags($text);
  echo "<div id='content'>\n";
  echo "{$text}\n";
  echo "</div>\n";
}
?>