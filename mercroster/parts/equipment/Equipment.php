<?php
if(!defined('g6afyHgJhHu87F'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf=new BBFunctions;

require("htdocs/dbsetup.php");
$vehicleID=$_GET['equipment'];
$vehicleID=stripslashes($vehicleID);
$vehicleID=mysql_real_escape_string($vehicleID);

$equipmentResult=$dbf->queryselect("SELECT v.id, v.name, v.subtype, v.weight, v.regnumber, v.notes, v.troid, c.lname, c.fname, et.weightscale, c.id as personnelid, v.image, r.text as readout FROM equipment v LEFT JOIN crew c ON v.crew=c.id LEFT JOIN equipmenttypes et ON v.type=et.id LEFT JOIN technicalreadouts r ON v.troid=r.id WHERE v.id='$vehicleID';");
if(mysql_num_rows($equipmentResult)==1)
{
  $equipmentArray=mysql_fetch_array($equipmentResult, MYSQL_BOTH);

  echo "<div id='content'>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>Vehicle number: {$equipmentArray[regnumber]}</b>\n";
  //Edit button
  if($action!="readout" && isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
  {
    echo "<a class='genericedit' href='index.php?action=editequipment&amp;equipment={$equipmentArray[id]}'>edit</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  if($equipmentArray[image]!="" && $equipmentArray[image]!=null)
  {
    echo "<div class='unitimage'>\n";
    echo "<img class='unitlogoimage' src='./images/equipmentimages/{$equipmentArray[image]}' alt='{$equipmentArray[image]}' />\n";
    echo "</div>\n";
    echo "<div class='unittableright'>\n";
  }
  else
  {
    echo "<div class='unittableleft'>\n";
  }
  echo "<table class='generictable' border='0'>\n";
  //Name
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Type:</b></td>\n";
  echo "<td class='generictablecell85'>{$equipmentArray[name]}</td>\n";
  echo "</tr>\n";
  //Type
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Sub Model:</b></td>\n";
  echo "<td class='generictablecell85'>{$equipmentArray[subtype]}</td>\n";
  echo "</tr>\n";
  //Weight
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Weight:</b></td>\n";
  echo "<td class='generictablecell85'>$equipmentArray[weight] $equipmentArray[weightscale]</td>\n";
  echo "</tr>\n";
  //Assigned to
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Assigned to:</b></td>\n";
  if($action=="readout") 
  {
  	echo "<td class='generictablecell85'><a class='personnellink' href='index.php?action=notable&amp;personnel={$equipmentArray[personnelid]}'>{$equipmentArray[fname]} {$equipmentArray[lname]}</a></td>\n";
  } 
  else 
  {
  	echo "<td class='generictablecell85'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$equipmentArray[personnelid]}'>{$equipmentArray[fname]} {$equipmentArray[lname]}</a></td>\n";
  }
  echo "</tr>\n";
  echo "</table>\n";
  echo "</div>\n";
  //TRO
  if($equipmentArray[readout]!="" && $equipmentArray[readout]!=null)
  {
  	echo "<div class='unitnotes'>\n";
    echo "<b>Readout:</b><br />\n";
    echo "<pre>$equipmentArray[readout];</pre>\n";
    echo "</div>\n";
  }
  //Notes
  if($equipmentArray[notes]!="" && $equipmentArray[notes]!=null)
  { 
  	echo "<div class='unitnotes'>\n";
    
    $text=nl2br($equipmentArray[notes]);
    $text=$bbf->addTags($text);
    echo "<b>Notes:</b><br />\n";
    echo "$text\n";
    echo "</div>\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Equipment not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing equipment information.<br />\n";
  echo "No equipment found or you don't have rights to access this equipment information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>