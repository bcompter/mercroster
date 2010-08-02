<?php
if(!defined('Ksf4t6Gws3'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
include("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);
if(!isset($first))
{
  $first=0;
}

$o=$_GET['order'];
$o=stripslashes($o);
$o=mysql_real_escape_string($o);

include("includes/PageBar.php");
$pb=new PageBar;
$range=30;
$add="?action=editkill";
$addtype="Kill";
$permission=4;

//Fetching Kills data
$query="SELECT k.id, c.lname, c.fname, k.type, k.killdate, c.id, k.equipment FROM kills k LEFT JOIN crew c ON k.parent=c.ID ORDER BY";
switch ($o)
{
  case 2:
    $order="c.fname ASC, c.lname ASC,  k.killdate DESC, k.type ASC, k.equipment ASC, k.id ASC";
    break;
  case 3:
    $order="k.type ASC, k.killdate DESC, c.fname ASC, c.lname ASC,  k.equipment ASC, k.id ASC";
    break;
  case 4:
    $order="k.equipment ASC, c.fname ASC, c.lname ASC,  k.killdate DESC, k.type ASC, k.id ASC";
    break;
  default:
    $order="k.killdate DESC, c.fname ASC, c.lname ASC,  k.type ASC, k.equipment ASC, k.id ASC";
    break;
}
$killSQLQeury=$query. " " . $order . " LIMIT $first, $range;";

$killsResult=$dbf->queryselect($killSQLQeury);
$rResult=$dbf->queryselect("SELECT COUNT(*) count FROM kills;");
$rnumber=mysql_result($rResult, 0);

echo "<div id='content'>\n";
echo "<h1 class='headercenter'>Kills</h1>\n";

if($rnumber>0)
{
  $link="kill&amp;order={$o}";
  $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
  echo "<hr />\n";

  echo "<table class='rostertable'>\n";
  echo "<thead class='rostertable'>\n";
  echo "<tr>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=kill&amp;order=1&amp;first={$first}'>Date</a></th>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=kill&amp;order=3&amp;first={$first}'>Destroyed unit</a></th>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=kill&amp;order=2&amp;first={$first}'>Awarded to</a></th>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=kill&amp;order=4&amp;first={$first}'>Killed with</a></th>\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
  {
    echo "<th class='rostertable'></th>\n";
  }
  echo "</tr>\n";
  echo "</thead>\n";
  echo "<tbody class='rostertable'>\n";
  while($array=mysql_fetch_array($killsResult, MYSQL_NUM))
  {
    $date=$dp->datestring($array[4]);
    echo "<tr>\n";
    echo "<td class='rostertable'>{$date}</td>\n";
    echo "<td class='rostertable'>{$array[3]}</td>\n";
    echo "<td class='rostertable'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$array[5]}'>{$array[2]} {$array[1]}</a></td>\n";
    echo "<td class='rostertable'>{$array[6]}</td>\n";
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'5')
    {
      echo "<td class='rostertable'><a class='rostertable' href='index.php?action=editkill&amp;kill={$array[0]}'>edit</a></td>\n";
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
    echo "<a class='genericedit' href='index.php?action=editkill'>Add new Kill</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  echo "No kill so far!\n";
  echo "</div>\n";
}
echo "</div>\n";
?>