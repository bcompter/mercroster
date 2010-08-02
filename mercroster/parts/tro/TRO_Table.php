<?php
if(!defined('gT6uj4D67J'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

require("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);

$vehicleType=$_GET["type"];
$vehicleType=stripslashes($vehicleType);
$vehicleType=mysql_real_escape_string($vehicleType);

require("includes/PageBar.php");
$pb = new PageBar;

$range=30;

if(isset($_GET["type"]))
{
  //Validating Personel Type
  $checkResult = $dbf->queryselect("SELECT name FROM equipmenttypes WHERE id='$vehicleType';");
  if(mysql_num_rows($checkResult)==1)
  {
    //Need to determine what type of roster to fecth from database
    $query = "SELECT id, name, weight, text FROM technicalreadouts WHERE type='$vehicleType' ORDER BY";
    switch ($_GET["o"])
    {
      case 2:
        $order="weight DESC, name ASC";
        break;
      default:
        $order="name ASC, weight DESC";
        break;
    }
    $vehicleSQLQeury = $query. " " . $order . " LIMIT $first, $range;";
    $vehicleResult = $dbf->queryselect($vehicleSQLQeury);
    $checkArray=mysql_fetch_array($checkResult, MYSQL_NUM);

    $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM technicalreadouts WHERE type='$vehicleType';");
    $rnumber=mysql_result($rResult, 0);

    $title=$checkArray[0];

    echo "<div id='content'>\n";
    echo "<h1 class='headercenter'>{$title} TROs</h1>\n";
    $permission=4;

    if($rnumber>0)
    {
      $link="trotable&amp;type={$vehicleType}&amp;order={$o}&amp;";

      $add="?action=edittro&amp;type={$vehicleType}";
      $addtype=$title;
      $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
      echo "<hr />\n";
      echo "<table class='rostertable'>\n";
      echo "<thead class='rostertable'>\n";
      echo "<tr>\n";
      echo "<th class='rostertabletype'><a class='rostertabletopic' href='index.php?action=trotable&amp;type={$vehicleType}&amp;order=1&amp;first=0'>Name</a></th>\n";
      echo "<th class='rostertablenumber'><a class='rostertabletopic' href='index.php?action=trotable&amp;type={$vehicleType}&amp;order=2&amp;first=0'>Weight</a></th>\n";
      echo "</tr>\n";
      echo "</thead>\n";
      echo "<tbody class='rostertable'>\n";
      while($array=mysql_fetch_array($vehicleResult, MYSQL_NUM))
      {
        echo "<tr>\n";
        if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']=='1')
        {
          echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=tro&amp;tro={$array[0]}'>{$array[1]}</a></td>\n";
        }
        else
        {
          echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=tro&amp;tro={$array[0]}'>{$array[1]}</a></td>\n";
        }
        echo "<td class='rostertablenumber'>{$array[2]}</td>\n";
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
        echo "<a class='genericedit' href='index.php?action=edittro&amp;type={$vehicleType}'>Add new {$title} TRO</a>\n";
      }
      echo "</div>\n";
      echo "<div class='genericarea'>\n";
      echo "No TROs of this class!\n";
      echo "</div>\n";
    }
    echo "</div>\n";
  }
  else
  {
    $error=true;
    $errormsg="TRO type not found";
  }
}
else
{
  $range = 30;
  //$equipmentTypeResult = $dbf->queryselect("SELECT * FROM equipmenttypes e LEFT JOIN (SELECT type, COUNT(type) AS vcount FROM technicalreadouts GROUP BY TYPE) t ON e.id=t.type WHERE vcount>0 ORDER BY license ASC LIMIT $first, $range;");
  $equipmentTypeResult=$dbf->queryselect("SELECT e.id, e.name, t.vcount FROM equipmenttypes e LEFT JOIN (SELECT type, COUNT(type) AS vcount FROM technicalreadouts GROUP BY TYPE) t ON e.id=t.type ORDER BY prefpos ASC LIMIT $first, $range;");
  $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM equipmenttypes;");
  $rnumber=mysql_result($rResult, 0);

  echo "<div id='content'>\n";

  //Parsing personel data
  echo "<h1 class='headercenter'>{$title}</h1>\n";
  $link="trotable";
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
  while($array=mysql_fetch_array($equipmentTypeResult, MYSQL_ASSOC))
  {
    if($array['vcount']!=0 || $array['vcount']!="" || ($_SESSION['SESS_TYPE']<=4 && $_SESSION['SESS_TYPE']>=1))
    {
      echo "<tr>\n";
      echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=trotable&amp;type={$array['id']}&amp;order=1&amp;first=0'>{$array['name']}s</a></td>\n";
      echo "<td class='rostertablenumber'>{$array['vcount']}</td>\n";
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
  echo "</div>\n";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b><b>An error has occurred</b></b> while accessing TRO table.<br />\n";
  echo "No TRO table found or you don't have rights to access this TRO table.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>