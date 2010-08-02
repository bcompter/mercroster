<?php
if(!defined('kgE3c68Fg2bnM'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

echo "<div id='rightbar'>\n";

if($_GET["action"]=="information" || $_GET["action"]=="notable")
{
  $personnelResult = $dbf->queryselect("SELECT c.id, r.rankname, c.lname, c.fname FROM crew c LEFT JOIN ranks r ON c.rank=r.number WHERE c.notable='1' ORDER BY c.rank DESC, c.fname ASC, c.lname ASC;");

  echo "<div class='sidetableheader'>\n";
  echo "Notable Persons\n";
  echo "</div>\n";
  echo "<div class='sidetablebody'>\n";
  echo "<ul>\n";
  while($array = mysql_fetch_array($personnelResult, MYSQL_ASSOC))
  {
    echo "<li class='oldtopic'><small>{$array[rankname]}:</small><br />\n";
    echo "<a class='newstable' href='index.php?action=notable&amp;personnel=$array[id]'>{$array[fname]} {$array[lname]}</a></li>\n";
  }
  echo "</ul>\n";
  echo "</div>\n";
}

if($_GET["action"]=="services" || $_GET["action"]=="units")
{
  $topLvlFormationsResult = $dbf->queryselect("SELECT id, name FROM unit WHERE Parent='1' ORDER BY PrefPos");

  echo "<div class='sidetableheader'>\n";
  echo "Administrative Units\n";
  echo "</div>\n";
  echo "<div class='sidetablebody'>\n";
  echo "<ul>\n";
  while($array = mysql_fetch_array($topLvlFormationsResult, MYSQL_ASSOC))
  {
    echo "<li class='oldtopic'><a class='newstable' href='index.php?action=units&amp;unit=$array[id]'>{$array[name]}</a></li>\n";
  }
  echo "</ul>\n";
  echo "</div>\n";
}

echo "</div>\n";
?>