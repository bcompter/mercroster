<?php
if(!defined('d5Uy76hG54'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf=new BBFunctions;

require("includes/GlobalFunctions.php");
$gblf = new GlobalFunctions;

require("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);

$vehicleType=$_GET["type"];
$vehicleType=stripslashes($vehicleType);
$vehicleType=mysql_real_escape_string($vehicleType);

require("includes/PageBar.php");
$pb=new PageBar;

if(isset($_GET["type"]))
{
  $range=30;
  //Validating Personel Type
  $checkResult = $dbf->queryselect("SELECT name FROM equipmenttypes WHERE id='$vehicleType';");
  if(mysql_num_rows($checkResult)==1)
  {
    //Need to determine what type of roster to fecth from database
    $query = "SELECT v.id, v.type, v.name, v.subtype, v.crew, v.weight, v.regnumber, v.notes, c.lname, c.fname, et.weightscale, c.id FROM equipment v LEFT JOIN crew c ON v.Crew=c.id LEFT JOIN equipmenttypes et ON v.type=et.id WHERE v.type='$vehicleType' ORDER BY";
    switch ($_GET["order"])
    {
      case 3:
        $order="c.lname ASC, c.fname ASC, v.weight DESC, v.subtype ASC, v.name ASC, v.id ASC";
        break;
      case 4:
        $order="v.id ASC, v.subtype ASC, v.name ASC, v.weight DESC, c.lname ASC, c.fname ASC";
        break;
      case 2:
        $order="v.subtype ASC, v.name ASC, v.weight DESC, v.id ASC, c.lname ASC, c.fname ASC";
        break;
      default:
        $order="v.weight DESC, v.subtype ASC, v.name ASC, v.id ASC, c.lname ASC, c.fname ASC";
        break;
    }
    $vehicleSQLQeury = $query. " " . $order . " LIMIT $first, $range;";
    $vehicleResult = $dbf->queryselect($vehicleSQLQeury);
    $checkArray=mysql_fetch_array($checkResult, MYSQL_NUM);

    $rResult = $dbf->queryselect("SELECT COUNT(*) count FROM equipment WHERE type='$vehicleType';");
    $rnumber = mysql_result($rResult, 0);

    $equipmentTypeResult = $dbf->queryselect("SELECT id, name FROM equipmenttypes;");
    $equipmentTypeArray = $dbf->resulttoarray($equipmentTypeResult);

    $title=$checkArray[0];

    $link="equipmenttable&amp;type={$vehicleType}&amp;order={$o}&amp;";
    $permission=4;
    $add="?action=editequipment&amp;type={$vehicleType}";
    $addtype=$title;

    echo "<div id='content'>\n";
    echo "<h1 class='headercenter'>{$title}s</h1>\n";
    if($rnumber>0)
    {
      $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
      echo "<hr />\n";
      echo "<table class='rostertable'>\n";
      echo "<thead class='rostertable'>\n";
      echo "<tr>\n";
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=equipmenttable&amp;type={$vehicleType}&amp;order=4&amp;first=0'>Number</a></th>\n";
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=equipmenttable&amp;type={$vehicleType}&amp;order=2&amp;first=0'>Type</a></th>\n";
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=equipmenttable&amp;type={$vehicleType}&amp;order=1&amp;first=0'>Weight</a></th>\n";
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=equipmenttable&amp;type={$vehicleType}&amp;order=3&amp;first=0'>Assigned to</a></th>\n";
      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
      {
        echo "<th class='rostertable'></th>\n";
      }
      echo "</tr>\n";
      echo "</thead>\n";
      echo "<tbody class='rostertable'>\n";
      while($array = mysql_fetch_array($vehicleResult, MYSQL_NUM))
      {
        echo "<tr>\n";
        echo "<td class='rostertable'><a class='rostertable' href='index.php?action=equipment&amp;equipment={$array[0]}'>{$array[6]}</a></td>\n";//number
        $ename = $gblf->displayEquipmentName($array[3], $array[2], $dbf);
        echo "<td class='rostertable'>{$ename}\n";//Type
        if($array[7]!="")
        {
          echo "<img style='margin-top:2px; margin-right:2px; float:right;' src='./images/small/notes.png' alt='notes' />";
        }
        echo "</td>\n";//Type
        echo "<td class='rostertable'>{$array[5]} {$array[10]}</td>\n";//weight
        echo "<td class='rostertable'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$array[11]}'>{$array[9]} {$array[8]}</a></td>\n";//assigned to
        if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
        {
          echo "<td class='rostertable'><a class='rostertable' href='index.php?action=editequipment&amp;equipment={$array[0]}'>edit</a></td>\n";
        }
        echo "</tr>\n";
      }
      echo "</tbody>\n";
      echo "</table>\n";
      echo "<hr />\n";
      $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
    }
    else
    {
      echo "<div class='genericheader'>\n";
      echo "<b>Note</b>\n";
      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
      {
        echo "<a class='genericedit' href='index.php?action=editequipment&amp;type={$vehicleType}'>Add new Equipment</a>\n";
      }
      echo "</div>\n";
      echo "<div class='genericarea'>\n";
      echo "No Equipment of this class!\n";
      echo "</div>\n";
    }
    echo "</div>\n";
  }
  else
  {
    $error=true;
    $errormsg="Log not found";
  }
}
else
{
  $range = 30;
  $link="equipmenttable";
  $equipmentTypeResult=$dbf->queryselect("SELECT id, name, requirement, count FROM equipmenttypes e LEFT JOIN (SELECT type, COUNT(type) AS count FROM equipment GROUP BY type) v ON e.id=v.type WHERE used='1' ORDER BY e.prefpos ASC LIMIT $first, $range;");
  $rnumber=mysql_num_rows($equipmentTypeResult);

  echo "<div id='content'>\n";
  if($rnumber>0)
  {
    //Parsing personel data
    echo "<h1 class='headercenter'>{$title}</h1>\n";
    $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
    echo "<hr />\n";
    echo "<table class='rostertable'>\n";
    echo "<thead class='rostertable'>\n";
    echo "<tr>\n";
    echo "<th class='rostertabletype'>Equipment Type</th>\n";
    echo "<th class='rostertablenumber'>Number</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody class='rostertable'>\n";
    $counter=0;
    while($array = mysql_fetch_array($equipmentTypeResult, MYSQL_BOTH))
    {
      if($array['requirement']!=0 || $array['requirement']!="" || ($_SESSION['SESS_TYPE']<=4 && $_SESSION['SESS_TYPE']>=1))
      {
        echo "<tr>\n";
        echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=equipmenttable&amp;type={$array['id']}&amp;order=1&amp;first=0'>{$array['name']}</a></td>\n";
        echo "<td class='rostertablenumber'>{$array['count']}</td>\n";
        echo "</tr>\n";
        $counter++;
      }
    }
    if($counter==0)
    {
      echo "<tr>\n";
      echo "<td class='rostertablenumber'>No TROs</td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
  else
  {
    echo "<div class='genericheader'>\n";
    echo "<b>Note</b>\n";
    echo "</div>\n";
    echo "<div class='genericarea'>\n";
    echo "No Equipments of any kind!\n";
    echo "</div>\n";
  }
  echo "</div>\n";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b><b>An error has occurred</b></b> while accessing equipment table.<br />\n";
  echo "No equipment table found or you don't have rights to access this equipment table.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>