<?php
if(!defined('kgE3c68Fg2bnM'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

//Fetching list of top level formations
$topLvlFormationsResult = $dbf->queryselect("SELECT id, name FROM unit WHERE Parent='1' ORDER BY PrefPos");

//Fetching log type information
if(!isset($_SESSION['SESS_TYPE']) || $_SESSION['SESS_TYPE']=="")
{
  $permissionlimit=6;
}
else
{
  $permissionlimit=$_SESSION['SESS_TYPE'];
}
$logTypeListResult=$dbf->queryselect("SELECT l.id, l.type, max(r.id) AS lastlogid FROM logtypes l LEFT JOIN logentry r ON r.logtype=l.id WHERE l.readpermission>={$permissionlimit} GROUP BY l.id ORDER BY prefpos");

$contractResult=$dbf->queryselect("SELECT employer, target, start, end FROM contracts WHERE start<='{$currentDate}' AND end>='{$currentDate}' ORDER BY start ASC;");

$currentLocation="";
$currentEmployer="";
while($array=mysql_fetch_array($contractResult, MYSQL_ASSOC))
{
  $currentLocation=$currentLocation." ".$array[target];
  $currentEmployer=$currentEmployer." ".$array[employer];
}
if($currentLocation=="")
{
  $currentLocation="Hiring Halls";
}
if($currentEmployer=="")
{
  $currentEmployer="None";
}

echo "<div class='navigation' id='navigation'>\n";

echo "<div class='sidetableheader'>\n";
echo "Logs\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";
echo "<ul>\n";
while($listArray = mysql_fetch_array($logTypeListResult, MYSQL_NUM))
{
  $lastTopicArray=$userfuntions->getLastTopicArray();
  $logTypeArray=$userfuntions->getLogTypeArray();
  if((isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!='')) && $listArray[2]>$lastTopicArray[array_search($listArray[0], $logTypeArray)])
  {
    echo "<li class='newtopic'><a href='index.php?action=logtable&amp;type={$listArray[0]}&amp;first=0'>{$listArray[1]}s</a></li>\n";
  }
  else
  {
    echo "<li class='oldtopic'><a href='index.php?action=logtable&amp;type={$listArray[0]}&amp;first=0'>{$listArray[1]}s</a></li>\n";
  }
}
echo "</ul>\n";
echo "</div>\n";

echo "<div class='sidetableheader'>\n";
echo "Rosters and Tables\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";
echo "<ul>\n";
echo "<li class='oldtopic'><a href='index.php?action=contracttable&amp;first=0'>Contracts</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=kill&amp;first=0'>Kills</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=personneltable&amp;first=0'>Personnel</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=equipmenttable&amp;first=0'>Equipment</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=trotable&amp;first=0'>TROs</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=controlsheettable&amp;first=0'>Control Sheets</a></li>\n";
echo "<li class='oldtopic'><a href='index.php?action=gallerytable&amp;first=0'>Images</a></li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<div class='sidetableheader'>\n";
echo "Administrative Units\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";
echo "<ul>\n";
while($array = mysql_fetch_array($topLvlFormationsResult, MYSQL_NUM))
{
  if($array[0]!=null)
  {
    echo "<li class='oldtopic'><a href='index.php?action=unittable&amp;unit={$array[0]}'>{$array[1]}</a></li>\n";
  }
}
if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'4')
{
  echo "<li class='oldtopic'><a href='index.php?action=unittable&amp;unit=0'>Detached Units</a></li>\n";
}
echo "</ul>\n";
echo "</div>\n";

echo "<div class='sidetableheader'>\n";
echo "Game data\n";
echo "</div>\n";
echo "<div class='sidetablebody'>\n";
echo "<b>Date:</b><br />\n";
echo "{$currentGameDate}<br />\n";
echo "<b>Located on:</b><br />\n";
echo "{$currentLocation}<br />\n";
echo "<b>Employed by:</b><br />\n";
echo "{$currentEmployer}<br />\n";
echo "</div>\n";

if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
{
  //$users=$userfuntions->checkUsers($dbf);
  if($guests>0)
  {
    $guestString=($guests==1) ? "{$guests} Guest" : "{$guests} Guests";
  }

  echo "<div class='sidetableheader'>\n";
  echo "Users Online\n";
  echo "</div>\n";
  echo "<div class='sidetablebody'>\n";
  echo "{$guestString}";
  $i=sizeof($users);
  if($i>0)
  {
    $userString=($i==1) ? "{$i} User\n" : "{$i} Users\n";
    if($guests>0)
    {
      echo ", ";
    }
    echo "{$userString}<br />";

    foreach ($users as $username)
    {
      echo "$username <br />\n";
    }
  }
  echo "</div>\n";
}
echo "</div>\n";
?>