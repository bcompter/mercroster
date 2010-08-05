<?php
if(!defined('j6Fr4F7k0cs8'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

require("includes/GlobalFunctions.php");
$gblf = new GlobalFunctions;

require("htdocs/dbsetup.php");
$unitid=$_GET['unit'];
$unitid=stripslashes($unitid);
$unitid=mysql_real_escape_string($unitid);

$unitResult=$dbf->queryselect("SELECT u.id, u.name, u.limage, u.rimage, u.parent, u.level, u.text, ut.name as unittype FROM unit u LEFT JOIN unittypes ut ON u.type=ut.id WHERE u.id='$unitid';");
if(mysql_num_rows($unitResult)==1)
{
  $unitArray=mysql_fetch_array($unitResult, MYSQL_BOTH);

  $childUnitResult=$dbf->queryselect("SELECT id, type, name, limage, rimage, parent, level, text FROM unit WHERE parent='$unitid' ORDER BY prefpos ASC;");
  $childUniArray=$dbf->resulttoarray($childUnitResult);
  $childUniArraySize=sizeof($childUniArray);

  $parentUnitResult=$dbf->queryselect("SELECT id, name FROM unit WHERE id='$unitArray[parent]';");
  $parentUnitArray=mysql_fetch_array($parentUnitResult, MYSQL_BOTH);

  $personnelResult=$dbf->queryselect("SELECT c.id, r.rankname, c.lname, c.fname, c.callsign, v.name, v.subtype, v.id AS vid FROM crew c LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN ranks r ON c.rank=r.number WHERE parent='$unitid' ORDER BY c.rank DESC, c.joiningdate ASC, c.lname ASC, c.id ASC;");
  $personnelArray=$dbf->resulttoarray($personnelResult);
  $personnelArraySize=sizeof($personnelArray);

  echo "<div id='content'>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>{$unitArray[name]}</b>\n";
  //Edit button
  if($action!="units" && $action!="notable" && isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='3')
  {
    echo "<a class='genericedit' href='index.php?action=editunit&amp;unit={$unitArray[id]}'>edit</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  if($unitArray[rimage]!="" && $unitArray[rimage]!=null)
  {
    echo "<div class='unitimage'>\n";
    echo "<img class='unitlogoimage' src='./images/unitimages/{$unitArray[rimage]}' alt='{$unitArray[rimage]}' />\n";
    echo "</div>\n";
    echo "<div class='unittableright'>\n";
  }
  else
  {
    echo "<div class='unittableleft'>\n";
  }
  echo "<table class='unittable' border='0'>\n";
  //unit Type
  echo "<tr>\n";
  echo "<th class='unittablecell'><b>Unit type:</b></th>\n";
  echo "<td class='unittablecell'>{$unitArray[unittype]}</td>\n";
  echo "</tr>\n";
  if($parentUnitArray!="" && $parentUnitArray!=null)
  {
    //parent unit
    echo "<tr>\n";
    echo "<th class='unittablecell'><b>Attached to:</b></th>\n";
    echo "<td class='unittablecell'><a class='personnellink' href='index.php?action={$action}&amp;unit={$parentUnitArray[id]}'>{$parentUnitArray[name]}</a></td>\n";
    echo "</tr>\n";
  }
  //SubUnits
  for ($i=0; $i<$childUniArraySize; $i++)
  {
    $temp=$childUniArray[$i];
    echo "<tr>\n";
    if($i==0)
    {
      echo "<th class='unittablecell'><b>Sub units:</b></th>\n";
    }
    else
    {
      echo "<th class='unittablecell'></th>\n";
    }
    echo "<td class='unittablecell'><a class='personnellink' href='index.php?action={$action}&amp;unit={$temp[id]}'>{$temp[name]}</a></td>\n";
    echo "</tr>\n";
  }
  //Personnel
  for ($i=0; $i<$personnelArraySize; $i++)
  {
    echo "<tr>\n";
    if($i==0)
    {
      echo "<th class='unittablecell'><b>Personnel:</b></th>\n";
    }
    else
    {
      echo "<th class='unittablecell'></th>\n";
    }
    $temp=$personnelArray[$i];
    
    $name = $temp[rankname]." ".$temp[fname];
    if($temp[callsign]!="") 
    {
    	$name = $name." \"".$temp[callsign]."\"";	
    }
    $name = $name." ".$temp[lname];
    $ename = $gblf->displayEquipmentName($temp[subtype], $temp[name], $dbf);
    if($action!="units")
    {
      echo"<td class='unittablecell'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$temp[id]}'>{$name}</a></td>\n";
      echo"<td class='unittablecell'><a class='personnellink' href='index.php?action=equipment&amp;equipment={$temp[vid]}'>{$ename}</a></td>\n";
    }
    else
    {
      echo"<td class='unittablecell'><a class='personnellink' href='index.php?action=notable&amp;personnel={$temp[id]}'>{$name}</a></td>\n";
      echo"<td class='unittablecell'><a class='personnellink' href='index.php?action=readout&amp;equipment={$temp[vid]}'>{$ename}</a></td>\n";
    }
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo "</div>\n";
  if($unitArray[text]!="" && $unitArray[text]!=null)
  {
    echo "<div class='unitnotes'>\n";
    //Notes
    $text=nl2br($unitArray[text]);
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
  $errormsg="Unit not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing unit.<br />\n";
  echo "No unit entry found or you don't have rights to access this unit.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>