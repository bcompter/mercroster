<?php
if(!defined('hyk74Gd434'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

require("htdocs/dbsetup.php");
$troID=$_GET['tro'];
$troID=stripslashes($troID);
$troID=mysql_real_escape_string($troID);

$troResult = $dbf->queryselect("SELECT id, name, text FROM technicalreadouts WHERE id='$troID';");
if(mysql_num_rows($troResult)==1)
{
  $troArray = mysql_fetch_array($troResult, MYSQL_NUM);

  $text=nl2br($troArray[2]);
  $text=$troArray[2];
  $text=$bbf->addTags($text);

  echo "<div id='content'>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>{$troArray[1]}</b>\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='3')
  {
    echo "<a class='genericedit' href='index.php?action=edittro&amp;tro={$troArray[0]}'>edit</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  echo "<pre>$text</pre>\n";
  echo "</div>\n";
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Log TRO found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing TRO.<br />\n";
  echo "No TRO entry found or you don't have rights to access this TRO.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>