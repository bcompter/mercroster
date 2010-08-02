<?php
if(!defined('Ziq93mdD34'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

require("htdocs/dbsetup.php");
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

switch ($o)
{
  case 2:
    $order="g.type ASC, g.name ASC, m.sitename ASC";
    break;
  case 3:
    $order="m.sitename ASC, g.name ASC, g.type ASC";
    break;
  default:
    $order="g.name ASC, g.type ASC, m.sitename ASC";
    break;
}

require("includes/PageBar.php");
$pb=new PageBar;

$range=30;
$permission=4;
$add="?action=editgallery";
$addtype="Add Gallery";



$galleryQeury="SELECT g.id, g.user, g.type, g.name, m.sitename FROM gallery g LEFT JOIN members m ON g.user=m.username ORDER BY {$order} LIMIT $first, $range;";

$galleryResult=$dbf->queryselect($galleryQeury);
$rnumber=mysql_result($dbf->queryselect($galleryQeury), 0);

echo "<div id='content'>\n";
echo "<h1 class='headercenter'>{$title}</h1>\n";

if($rnumber>0)
{
  $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);

  echo "<hr />\n";
  echo "<table class='rostertable'>\n";
  echo "<thead class='rostertable'>\n";
  echo "<tr>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=gallerytable&amp;order=1&amp;first={$first}'>Name</a></th>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=gallerytable&amp;order=2&amp;first={$first}'>Type</a></th>\n";
  echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=gallerytable&amp;order=3&amp;first={$first}'>User</a></th>\n";
  echo "</tr>\n";
  echo "</thead>\n";
  echo "<tbody class='rostertable'>\n";

  while($array=mysql_fetch_array($galleryResult, MYSQL_ASSOC))
  {
    echo "<tr>\n";
    echo "<td class='rostertable'><a class='personnellink' href='index.php?action=gallery&amp;gallery={$array[id]}'>{$array['name']}</a></td>\n";
    echo "<td class='rostertable'>{$array['type']}</td>\n";
    if(isset($array['sitename']))
    {
      echo "<td class='rostertable'>{$array['sitename']}</td>\n";
    }
    else
    {
      echo "<td class='rostertable'>{$array['user']}</td>\n";
    }
    echo "</tr>\n";
  }
  echo "</tbody>\n";
  echo "</table>\n";

  echo "<hr />\n";

  $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);
}
else
{
  echo "<div class='genericheader'>\n";
  echo "<b>Note</b>\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
  {
    echo "<a class='genericedit' href='index.php?action=editgallery'>Add new Gallery</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  echo "No image galleries so far!\n";
  echo "</div>\n";
}
echo "</div>\n";
?>